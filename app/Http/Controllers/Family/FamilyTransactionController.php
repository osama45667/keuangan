<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\FamilyCategory;
use App\Models\FamilyTransaction;
use Illuminate\Http\Request;

class FamilyTransactionController extends Controller
{
    public function index(Request $request)
    {
        $q = FamilyTransaction::with('category')->orderByDesc('tanggal');

        if ($request->filled('type')) {
            $q->where('type', $request->type);
        }
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->category_id);
        }
        if ($request->filled('member_name')) {
            $q->where('member_name', 'like', '%'.$request->member_name.'%');
        }
        if ($request->filled('start') && $request->filled('end')) {
            $q->whereBetween('tanggal', [$request->start, $request->end]);
        }

        $transactions = $q->paginate(15)->withQueryString();
        $categories = FamilyCategory::where('is_active', true)->orderBy('type')->orderBy('name')->get();

        return view('family.transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = FamilyCategory::where('is_active', true)->orderBy('type')->orderBy('name')->get();
        return view('family.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rows = $request->input('transactions');
        $mapType = function (string $type): array {
            return match ($type) {
                'income' => ['type' => 'income', 'member' => null],
                'expense_father' => ['type' => 'expense', 'member' => 'Ayah'],
                'expense_mother' => ['type' => 'expense', 'member' => 'Ibu'],
                'expense_total' => ['type' => 'expense', 'member' => 'Umum'],
                default => ['type' => $type, 'member' => null],
            };
        };

        if (is_array($rows)) {
            $request->validate([
                'transactions' => ['required', 'array', 'min:1'],
                'transactions.*.tanggal' => ['required', 'date'],
                'transactions.*.type' => ['required', 'in:income,expense_father,expense_mother,expense_total'],
                'transactions.*.category_id' => ['required', 'exists:family_categories,id'],
                'transactions.*.member_name' => ['nullable', 'string', 'in:Ayah,Ibu,Anak,Umum'],
                'transactions.*.amount' => ['required', 'numeric', 'min:0'],
                'transactions.*.note' => ['nullable', 'string', 'max:255'],
            ]);

            foreach ($rows as $row) {
                $mapped = $mapType($row['type']);
                $row['type'] = $mapped['type'];
                $row['member_name'] = $mapped['member'];
                $row['created_by'] = $request->user()->id;
                FamilyTransaction::create($row);
            }
        } else {
            $data = $request->validate([
                'tanggal' => ['required', 'date'],
                'type' => ['required', 'in:income,expense_father,expense_mother,expense_total'],
                'category_id' => ['required', 'exists:family_categories,id'],
                'member_name' => ['nullable', 'string', 'in:Ayah,Ibu,Anak,Umum'],
                'amount' => ['required', 'numeric', 'min:0'],
                'note' => ['nullable', 'string', 'max:255'],
            ]);
            $mapped = $mapType($data['type']);
            $data['type'] = $mapped['type'];
            $data['member_name'] = $mapped['member'];
            $data['created_by'] = $request->user()->id;
            FamilyTransaction::create($data);
        }

        return redirect()->route('family.transactions.index')->with('success', 'Transaksi disimpan.');
    }

    public function edit(FamilyTransaction $transaction)
    {
        $categories = FamilyCategory::where('is_active', true)->orderBy('type')->orderBy('name')->get();
        return view('family.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, FamilyTransaction $transaction)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:family_categories,id'],
            'member_name' => ['nullable', 'string', 'max:120'],
            'amount' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        $data['updated_by'] = $request->user()->id;
        $transaction->update($data);

        return redirect()->route('family.transactions.index')->with('success', 'Transaksi diubah.');
    }

    public function destroy(Request $request, FamilyTransaction $transaction)
    {
        $transaction->update(['deleted_by' => $request->user()->id]);
        $transaction->delete();
        return redirect()->route('family.transactions.index')->with('success', 'Transaksi dihapus.');
    }
}
