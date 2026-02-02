<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\Models\Account;
use App\Exports\TrialBalanceExport;
use App\Exports\ProfitLossExport;
use App\Exports\BalanceSheetExport;
use App\Exports\CashFlowExport;
use App\Exports\LedgerExport;
use App\Exports\JournalExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Journal;

class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function trialBalance(Request $request)
    {
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        $rows = $this->service->trialBalance($start, $end);
        return view('reports.trial_balance', compact('rows', 'start', 'end'));
    }

    public function profitLoss(Request $request)
    {
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        $data = $this->service->profitLoss($start, $end);
        return view('reports.profit_loss', $data);
    }

    public function balanceSheet(Request $request)
    {
        $end = $request->end ?? now()->endOfMonth()->toDateString();
        $data = $this->service->balanceSheet($end);
        return view('reports.balance_sheet', $data);
    }

    public function cashFlow(Request $request)
    {
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        $data = $this->service->cashFlowIndirect($start, $end);
        return view('reports.cash_flow', $data);
    }

    public function ledger(Request $request)
    {
        $accountId = $request->account_id;
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        $accounts = Account::orderBy('kode_akun')->get();
        $ledger = $accountId ? $this->service->ledger($accountId, $start, $end) : null;

        return view('reports.ledger', compact('accounts', 'ledger', 'start', 'end', 'accountId'));
    }

    public function journal(Request $request)
    {
        return redirect()->route('journals.index');
    }

    public function export(Request $request, string $type)
    {
        $start = $request->start ?? now()->startOfMonth()->toDateString();
        $end = $request->end ?? now()->endOfMonth()->toDateString();

        if ($request->format === 'excel') {
            return match ($type) {
                'trial-balance' => Excel::download(new TrialBalanceExport($start, $end), 'trial_balance.xlsx'),
                'profit-loss' => Excel::download(new ProfitLossExport($start, $end), 'profit_loss.xlsx'),
                'balance-sheet' => Excel::download(new BalanceSheetExport($end), 'balance_sheet.xlsx'),
                'cash-flow' => Excel::download(new CashFlowExport($start, $end), 'cash_flow.xlsx'),
                'ledger' => Excel::download(new LedgerExport($request->account_id, $start, $end), 'ledger.xlsx'),
                'journal' => Excel::download(new JournalExport($start, $end), 'journal.xlsx'),
                default => abort(404),
            };
        }

        $view = match ($type) {
            'trial-balance' => 'exports.pdf.trial_balance',
            'profit-loss' => 'exports.pdf.profit_loss',
            'balance-sheet' => 'exports.pdf.balance_sheet',
            'cash-flow' => 'exports.pdf.cash_flow',
            'ledger' => 'exports.pdf.ledger',
            'journal' => 'exports.pdf.journal',
            default => abort(404),
        };

        $data = match ($type) {
            'trial-balance' => ['rows' => $this->service->trialBalance($start, $end), 'start' => $start, 'end' => $end],
            'profit-loss' => $this->service->profitLoss($start, $end),
            'balance-sheet' => $this->service->balanceSheet($end),
            'cash-flow' => $this->service->cashFlowIndirect($start, $end),
            'ledger' => [
                'ledger' => $this->service->ledger($request->account_id, $start, $end),
                'start' => $start,
                'end' => $end,
            ],
            'journal' => [
                'journals' => Journal::with('lines.account')->whereBetween('tanggal', [$start, $end])->orderBy('tanggal')->get(),
                'start' => $start,
                'end' => $end,
            ],
        };

        $pdf = Pdf::loadView($view, $data)->setPaper('a4');
        return $pdf->download($type.'.pdf');
    }
}
