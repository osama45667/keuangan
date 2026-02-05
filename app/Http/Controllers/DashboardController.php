<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Models\Account;
use App\Models\JournalLine;
use App\Models\FamilyTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(ReportService $service)
    {
        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();

        $from = now()->subMonths(11)->startOfMonth()->toDateString();
        $to = now()->endOfMonth()->toDateString();

        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $ymExpr = "strftime('%Y-%m', tanggal)";
        } elseif ($driver === 'pgsql') {
            $ymExpr = "to_char(tanggal, 'YYYY-MM')";
        } else {
            $ymExpr = "DATE_FORMAT(tanggal, '%Y-%m')";
        }

        $monthlyIncome = FamilyTransaction::select(
                DB::raw("$ymExpr as ym"),
                DB::raw('SUM(amount) as total')
            )
            ->where('type', 'income')
            ->whereBetween('tanggal', [$from, $to])
            ->groupBy('ym')
            ->pluck('total', 'ym')
            ->all();

        $monthlyExpense = FamilyTransaction::select(
                DB::raw("$ymExpr as ym"),
                DB::raw('SUM(amount) as total')
            )
            ->where('type', 'expense')
            ->whereBetween('tanggal', [$from, $to])
            ->groupBy('ym')
            ->pluck('total', 'ym')
            ->all();

        $labels = [];
        $incomeData = [];
        $expenseData = [];
        for ($i = 11; $i >= 0; $i--) {
            $ym = now()->subMonths($i)->format('Y-m');
            $labels[] = $ym;
            $incomeData[] = $monthlyIncome[$ym] ?? 0;
            $expenseData[] = $monthlyExpense[$ym] ?? 0;
        }

        $familyIncome = FamilyTransaction::whereBetween('tanggal', [$start, $end])
            ->where('type', 'income')
            ->sum('amount');
        $familyExpense = FamilyTransaction::whereBetween('tanggal', [$start, $end])
            ->where('type', 'expense')
            ->sum('amount');
        $familyNet = $familyIncome - $familyExpense;

        $expenseByMemberRaw = FamilyTransaction::select(
                DB::raw("LOWER(COALESCE(member_name, '')) as member"),
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('tanggal', [$start, $end])
            ->where('type', 'expense')
            ->groupBy('member')
            ->get()
            ->pluck('total', 'member')
            ->all();

        $familyExpenseAyah = $expenseByMemberRaw['ayah'] ?? 0;
        $familyExpenseIbu = $expenseByMemberRaw['ibu'] ?? 0;

        $familyRecent = FamilyTransaction::with('category')
            ->orderByDesc('tanggal')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'labels' => $labels,
            'incomeData' => $incomeData,
            'expenseData' => $expenseData,
            'family_income' => $familyIncome,
            'family_expense' => $familyExpense,
            'family_net' => $familyNet,
            'family_expense_ayah' => $familyExpenseAyah,
            'family_expense_ibu' => $familyExpenseIbu,
            'family_recent' => $familyRecent,
        ]);
    }
}
