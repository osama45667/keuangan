<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalLine;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function trialBalance(string $start, string $end)
    {
        return JournalLine::select(
                'accounts.id',
                'accounts.kode_akun',
                'accounts.nama_akun',
                'accounts.tipe',
                DB::raw('SUM(journal_lines.debit) as total_debit'),
                DB::raw('SUM(journal_lines.kredit) as total_kredit')
            )
            ->join('accounts', 'accounts.id', '=', 'journal_lines.account_id')
            ->join('journals', 'journals.id', '=', 'journal_lines.journal_id')
            ->whereBetween('journals.tanggal', [$start, $end])
            ->groupBy('accounts.id', 'accounts.kode_akun', 'accounts.nama_akun', 'accounts.tipe')
            ->orderBy('accounts.kode_akun')
            ->get();
    }

    public function profitLoss(string $start, string $end): array
    {
        $revenue = $this->sumByType('Revenue', $start, $end);
        $expense = $this->sumByType('Expense', $start, $end);
        $net = $revenue['total'] - $expense['total'];

        return compact('revenue', 'expense', 'net', 'start', 'end');
    }

    public function balanceSheet(string $end): array
    {
        $assets = $this->sumByType('Asset', null, $end);
        $liabilities = $this->sumByType('Liability', null, $end);
        $equity = $this->sumByType('Equity', null, $end);

        return compact('assets', 'liabilities', 'equity', 'end');
    }

    public function cashFlowIndirect(string $start, string $end): array
    {
        $profitLoss = $this->profitLoss($start, $end);
        $net = $profitLoss['net'];

        $cashAccounts = Account::where('is_cash_bank', true)->pluck('id')->all();

        $cashChange = JournalLine::select(DB::raw('SUM(debit - kredit) as delta'))
            ->join('journals', 'journals.id', '=', 'journal_lines.journal_id')
            ->whereBetween('journals.tanggal', [$start, $end])
            ->whereIn('account_id', $cashAccounts)
            ->value('delta') ?? 0;

        return [
            'net_income' => $net,
            'cash_change' => $cashChange,
            'assumptions' => 'Metode tidak langsung minimal: rekonsiliasi perubahan kas dari akun is_cash_bank; klasifikasi arus kas disederhanakan.',
            'start' => $start,
            'end' => $end,
        ];
    }

    public function ledger(int $accountId, string $start, string $end): array
    {
        $account = Account::findOrFail($accountId);
        $normal = $account->normal_balance ?: ($account->tipe === 'Asset' || $account->tipe === 'Expense' ? 'D' : 'K');

        $opening = JournalLine::select(DB::raw('SUM(debit) as debit_sum'), DB::raw('SUM(kredit) as kredit_sum'))
            ->join('journals', 'journals.id', '=', 'journal_lines.journal_id')
            ->where('account_id', $accountId)
            ->whereDate('journals.tanggal', '<', $start)
            ->first();

        $openingBalance = $normal === 'D'
            ? ($opening->debit_sum - $opening->kredit_sum)
            : ($opening->kredit_sum - $opening->debit_sum);

        $lines = JournalLine::with(['journal'])
            ->where('account_id', $accountId)
            ->whereHas('journal', function ($q) use ($start, $end) {
                $q->whereBetween('tanggal', [$start, $end]);
            })
            ->orderBy('journal_id')
            ->get();

        $running = $openingBalance;
        foreach ($lines as $line) {
            $delta = $normal === 'D'
                ? ($line->debit - $line->kredit)
                : ($line->kredit - $line->debit);
            $running += $delta;
            $line->running_balance = $running;
        }

        return [
            'account' => $account,
            'opening' => $openingBalance,
            'lines' => $lines,
            'normal' => $normal,
        ];
    }

    private function sumByType(string $type, ?string $start, string $end): array
    {
        $query = JournalLine::select(
                'accounts.id',
                'accounts.kode_akun',
                'accounts.nama_akun',
                DB::raw('SUM(journal_lines.debit) as total_debit'),
                DB::raw('SUM(journal_lines.kredit) as total_kredit')
            )
            ->join('accounts', 'accounts.id', '=', 'journal_lines.account_id')
            ->join('journals', 'journals.id', '=', 'journal_lines.journal_id')
            ->where('accounts.tipe', $type);

        if ($start && $end) {
            $query->whereBetween('journals.tanggal', [$start, $end]);
        } else {
            $query->whereDate('journals.tanggal', '<=', $end);
        }

        $rows = $query->groupBy('accounts.id', 'accounts.kode_akun', 'accounts.nama_akun')->get();

        $total = 0;
        foreach ($rows as $r) {
            $delta = ($type === 'Revenue')
                ? ($r->total_kredit - $r->total_debit)
                : ($r->total_debit - $r->total_kredit);
            $total += $delta;
        }

        return ['rows' => $rows, 'total' => $total];
    }
}
