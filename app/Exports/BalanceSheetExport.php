<?php

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BalanceSheetExport implements FromView
{
    public function __construct(private string $end) {}

    public function view(): View
    {
        $data = app(ReportService::class)->balanceSheet($this->end);
        return view('exports.excel.balance_sheet', $data);
    }
}
