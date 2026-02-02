<?php

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CashFlowExport implements FromView
{
    public function __construct(private string $start, private string $end) {}

    public function view(): View
    {
        $data = app(ReportService::class)->cashFlowIndirect($this->start, $this->end);
        return view('exports.excel.cash_flow', $data);
    }
}
