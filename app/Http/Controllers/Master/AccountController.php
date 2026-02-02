<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $q = Account::query()->orderBy('kode_akun');
        if ($request->filled('search')) {
            $q->where('nama_akun', 'like', '%'.$request->search.'%')
              ->orWhere('kode_akun', 'like', '%'.$request->search.'%');
        }
        $accounts = $q->paginate(15)->withQueryString();
        return view('master.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $parents = Account::orderBy('kode_akun')->get();
        return view('master.accounts.create', compact('parents'));
    }

    public function store(StoreAccountRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        Account::create($data);

        return redirect()->route('accounts.index')->with('success', 'COA dibuat.');
    }

    public function edit(Account $account)
    {
        $parents = Account::where('id', '!=', $account->id)->orderBy('kode_akun')->get();
        return view('master.accounts.edit', compact('account', 'parents'));
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;
        $account->update($data);

        return redirect()->route('accounts.index')->with('success', 'COA diubah.');
    }

    public function destroy(Account $account)
    {
        $account->update(['deleted_by' => request()->user()->id]);
        $account->delete();
        return redirect()->route('accounts.index')->with('success', 'COA dihapus.');
    }
}
