<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage coa');
    }

    public function rules(): array
    {
        return [
            'kode_akun' => ['required', 'string', 'max:50', 'unique:accounts,kode_akun'],
            'nama_akun' => ['required', 'string'],
            'tipe' => ['required', 'in:Asset,Liability,Equity,Revenue,Expense'],
            'parent_id' => ['nullable', 'exists:accounts,id'],
            'normal_balance' => ['nullable', 'in:D,K'],
            'is_cash_bank' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }
}
