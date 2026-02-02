<?php

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProfitLossExport implements FromView
{
    public function __construct(private string $start, private string $end) {}

    public function view(): View
    {
        $data = app(ReportService::class)->profitLoss($this->start, $this->end);
        return view('exports.excel.profit_loss', $data);
    }
}
