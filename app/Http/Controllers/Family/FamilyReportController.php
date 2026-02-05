<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\FamilyTransaction;
use App\Models\FamilyCategory;
use App\Exports\FamilyReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilyReportController extends Controller
{
    public function summary(Request $request)
    {
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        $base = FamilyTransaction::whereBetween('tanggal', [$start, $end]);

        $income = (clone $base)->where('type', 'income')->sum('amount');
        $expense = (clone $base)->where('type', 'expense')->sum('amount');
        $net = $income - $expense;

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

        $expenseByMember = [
            'Ayah' => $expenseByMemberRaw['ayah'] ?? 0,
            'Ibu' => $expenseByMemberRaw['ibu'] ?? 0,
            'Umum' => ($expenseByMemberRaw['ayah'] ?? 0) + ($expenseByMemberRaw['ibu'] ?? 0) + ($expenseByMemberRaw['umum'] ?? 0),
        ];

        $byCategory = FamilyTransaction::select('category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('tanggal', [$start, $end])
            ->groupBy('category_id')
            ->get()
            ->map(function ($row) {
                $row->category = FamilyCategory::find($row->category_id);
                return $row;
            });

        $byMember = FamilyTransaction::select('member_name', DB::raw('SUM(amount) as total'))
            ->whereBetween('tanggal', [$start, $end])
            ->where('type', 'expense')
            ->groupBy('member_name')
            ->orderByDesc('total')
            ->get();

        $transactions = FamilyTransaction::with('category')
            ->whereBetween('tanggal', [$start, $end])
            ->orderByDesc('tanggal')
            ->get();


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

        $expenseDetailByMember = FamilyTransaction::with('category')
            ->whereBetween('tanggal', [$start, $end])
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

        return view('family.reports.summary', compact(
            'start',
            'end',
            'income',
            'expense',
            'net',
            'expenseByMember',
            'byCategory',
            'byMember',
            'transactions',
            'labels',
            'incomeData',
            'expenseData',
            'expenseDetailByMember',
            'expenseTotalsByMember'
        ));
    }

    public function export(Request $request, string $format)
    {
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        if ($format === 'excel') {
            return Excel::download(new FamilyReportExport($start, $end), 'laporan_keluarga.xlsx');
        }

        $data = app(self::class)->summary($request)->getData();
        $pdf = Pdf::loadView('exports.pdf.family_summary', (array) $data)->setPaper('a4');
        return $pdf->download('laporan_keluarga.pdf');
    }
}
