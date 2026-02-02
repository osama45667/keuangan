<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use App\Models\FamilyCategory;
use Illuminate\Http\Request;

class FamilyCategoryController extends Controller
{
    public function index(Request $request)
    {
        $q = FamilyCategory::query()->orderBy('type')->orderBy('name');
        if ($request->filled('search')) {
            $q->where('name', 'like', '%'.$request->search.'%');
        }
        $categories = $q->paginate(15)->withQueryString();
        return view('family.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('family.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:income,expense'],
            'is_active' => ['boolean'],
        ]);
        $data['created_by'] = $request->user()->id;
        FamilyCategory::create($data);
        return redirect()->route('family.categories.index')->with('success', 'Kategori dibuat.');
    }

    public function edit(FamilyCategory $category)
    {
        return view('family.categories.edit', compact('category'));
    }

    public function update(Request $request, FamilyCategory $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:income,expense'],
            'is_active' => ['boolean'],
        ]);
        $data['updated_by'] = $request->user()->id;
        $category->update($data);
        return redirect()->route('family.categories.index')->with('success', 'Kategori diubah.');
    }

    public function destroy(Request $request, FamilyCategory $category)
    {
        $category->update(['deleted_by' => $request->user()->id]);
        $category->delete();
        return redirect()->route('family.categories.index')->with('success', 'Kategori dihapus.');
    }
}
