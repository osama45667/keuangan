<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJournalRequest;
use App\Http\Requests\UpdateJournalRequest;
use App\Models\Journal;
use App\Models\Account;
use App\Models\AccountingPeriod;
use App\Services\JournalService;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function __construct(private JournalService $service) {}

    public function index(Request $request)
    {
        $q = Journal::with('period')->orderByDesc('tanggal');

        if ($request->filled('start') && $request->filled('end')) {
            $q->whereBetween('tanggal', [$request->start, $request->end]);
        }
        if ($request->filled('period_id')) {
            $q->where('period_id', $request->period_id);
        }
        if ($request->filled('search')) {
            $q->where('nomor_jurnal', 'like', '%'.$request->search.'%');
        }

        $journals = $q->paginate(15)->withQueryString();
        $periods = AccountingPeriod::orderByDesc('tahun')->orderByDesc('bulan')->get();

        return view('transactions.journals.index', compact('journals', 'periods'));
    }

    public function create()
    {
        $accounts = Account::where('is_active', true)->orderBy('kode_akun')->get();
        $periods = AccountingPeriod::where('status', 'open')->orderByDesc('tahun')->orderByDesc('bulan')->get();

        return view('transactions.journals.create', compact('accounts', 'periods'));
    }

    public function store(StoreJournalRequest $request)
    {
        $journal = $this->service->create(
            $request->validated(),
            $request->validated()['lines'],
            $request->file('attachments'),
            $request->user()->id
        );

        return redirect()->route('journals.show', $journal->id)->with('success', 'Jurnal dibuat.');
    }

    public function show(Journal $journal)
    {
        $journal->load('lines.account', 'attachments', 'period');
        return view('transactions.journals.show', compact('journal'));
    }

    public function edit(Journal $journal)
    {
        $journal->load('lines');
        $accounts = Account::where('is_active', true)->orderBy('kode_akun')->get();
        $periods = AccountingPeriod::where('status', 'open')->orderByDesc('tahun')->orderByDesc('bulan')->get();

        return view('transactions.journals.edit', compact('journal', 'accounts', 'periods'));
    }

    public function update(UpdateJournalRequest $request, Journal $journal)
    {
        $this->service->update(
            $journal,
            $request->validated(),
            $request->validated()['lines'],
            $request->file('attachments'),
            $request->user()->id
        );

        return redirect()->route('journals.show', $journal->id)->with('success', 'Jurnal diubah.');
    }

    public function destroy(Journal $journal)
    {
        $journal->update(['deleted_by' => request()->user()->id]);
        $journal->delete();
        return redirect()->route('journals.index')->with('success', 'Jurnal dihapus.');
    }
}
