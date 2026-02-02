<?php

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TrialBalanceExport implements FromView
{
    public function __construct(private string $start, private string $end) {}

    public function view(): View
    {
        $rows = app(ReportService::class)->trialBalance($this->start, $this->end);
        return view('exports.excel.trial_balance', [
            'rows' => $rows,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }
}
