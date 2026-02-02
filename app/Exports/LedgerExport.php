<?php

namespace App\Exports;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LedgerExport implements FromView
{
    public function __construct(private int $accountId, private string $start, private string $end) {}

    public function view(): View
    {
        $ledger = app(ReportService::class)->ledger($this->accountId, $this->start, $this->end);
        return view('exports.excel.ledger', [
            'ledger' => $ledger,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }
}
