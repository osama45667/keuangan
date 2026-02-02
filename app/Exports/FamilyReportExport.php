<?php

namespace App\Exports;

use App\Models\FamilyTransaction;
use App\Models\FamilyCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class FamilyReportExport implements FromView
{
    public function __construct(private string $start, private string $end) {}

    public function view(): View
    {
        $income = FamilyTransaction::whereBetween('tanggal', [$this->start, $this->end])
            ->where('type', 'income')
            ->sum('amount');
        $expense = FamilyTransaction::whereBetween('tanggal', [$this->start, $this->end])
            ->where('type', 'expense')
            ->sum('amount');
        $net = $income - $expense;

        $expenseByMemberRaw = FamilyTransaction::select(
                DB::raw("LOWER(COALESCE(member_name, '')) as member"),
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('tanggal', [$this->start, $this->end])
            ->where('type', 'expense')
            ->groupBy('member')
            ->get()
            ->pluck('total', 'member')
            ->all();

        $expenseByMember = [
            'Ayah' => $expenseByMemberRaw['ayah'] ?? 0,
            'Ibu' => $expenseByMemberRaw['ibu'] ?? 0,
            'Umum' => $expenseByMemberRaw['umum'] ?? 0,
        ];

        $byCategory = FamilyTransaction::select('category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('tanggal', [$this->start, $this->end])
            ->groupBy('category_id')
            ->get()
            ->map(function ($row) {
                $row->category = FamilyCategory::find($row->category_id);
                return $row;
            });

        $byMember = FamilyTransaction::select('member_name', DB::raw('SUM(amount) as total'))
            ->whereBetween('tanggal', [$this->start, $this->end])
            ->where('type', 'expense')
            ->groupBy('member_name')
            ->orderByDesc('total')
            ->get();

        $transactions = FamilyTransaction::with('category')
            ->whereBetween('tanggal', [$this->start, $this->end])
            ->orderByDesc('tanggal')
            ->get();

        $expenseDetailByMember = FamilyTransaction::with('category')
            ->whereBetween('tanggal', [$this->start, $this->end])
            ->where('type', 'expense')
            ->orderBy('member_name')
            ->orderBy('tanggal')
            ->get()
            ->groupBy(function ($row) {
                return $row->member_name ?: 'Umum';
            });

        $expenseTotalsByMember = [
            'Ayah' => ($expenseDetailByMember['Ayah'] ?? collect())->sum('amount'),
            'Ibu' => ($expenseDetailByMember['Ibu'] ?? collect())->sum('amount'),
            'Umum' => ($expenseDetailByMember['Ayah'] ?? collect())->sum('amount')
                + ($expenseDetailByMember['Ibu'] ?? collect())->sum('amount')
                + ($expenseDetailByMember['Umum'] ?? collect())->sum('amount'),
        ];

        return view('exports.excel.family_summary', [
            'start' => $this->start,
            'end' => $this->end,
            'income' => $income,
            'expense' => $expense,
            'net' => $net,
            'expenseByMember' => $expenseByMember,
            'byCategory' => $byCategory,
            'byMember' => $byMember,
            'transactions' => $transactions,
            'expenseDetailByMember' => $expenseDetailByMember,
            'expenseTotalsByMember' => $expenseTotalsByMember,
        ]);
    }
}
