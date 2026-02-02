<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePeriodRequest;
use App\Http\Requests\UpdatePeriodRequest;
use App\Models\AccountingPeriod;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index(Request $request)
    {
        $periods = AccountingPeriod::orderByDesc('tahun')->orderByDesc('bulan')
            ->paginate(15)->withQueryString();
        return view('master.periods.index', compact('periods'));
    }

    public function create()
    {
        return view('master.periods.create');
    }

    public function store(StorePeriodRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        AccountingPeriod::create($data);

        return redirect()->route('periods.index')->with('success', 'Periode dibuat.');
    }

    public function edit(AccountingPeriod $period)
    {
        return view('master.periods.edit', compact('period'));
    }

    public function update(UpdatePeriodRequest $request, AccountingPeriod $period)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;
        $period->update($data);

        return redirect()->route('periods.index')->with('success', 'Periode diubah.');
    }

    public function destroy(AccountingPeriod $period)
    {
        $period->update(['deleted_by' => request()->user()->id]);
        $period->delete();
        return redirect()->route('periods.index')->with('success', 'Periode dihapus.');
    }
}
