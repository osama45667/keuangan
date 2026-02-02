<?php

namespace App\Exports;

use App\Models\Journal;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JournalExport implements FromView
{
    public function __construct(private string $start, private string $end) {}

    public function view(): View
    {
        $journals = Journal::with('lines.account')
            ->whereBetween('tanggal', [$this->start, $this->end])
            ->orderBy('tanggal')
            ->get();

        return view('exports.excel.journal', [
            'journals' => $journals,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }
}
