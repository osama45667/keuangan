<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AccountingPeriod;

class StoreJournalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage journals');
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'period_id' => ['required', 'exists:accounting_periods,id'],
            'deskripsi' => ['nullable', 'string'],
            'reference_no' => ['nullable', 'string'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:accounts,id'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.kredit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.memo' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $period = AccountingPeriod::find($this->period_id);
            if ($period && $period->status === 'closed') {
                $v->errors()->add('period_id', 'Periode sudah ditutup.');
            }

            $totalDebit = 0;
            $totalKredit = 0;
            foreach ($this->lines as $line) {
                $d = floatval($line['debit'] ?? 0);
                $k = floatval($line['kredit'] ?? 0);
                if ($d > 0 && $k > 0) {
                    $v->errors()->add('lines', 'Satu baris tidak boleh debit dan kredit sekaligus.');
                }
                $totalDebit += $d;
                $totalKredit += $k;
            }
            if (round($totalDebit, 2) !== round($totalKredit, 2)) {
                $v->errors()->add('lines', 'Total debit harus sama dengan total kredit.');
            }
        });
    }
}
