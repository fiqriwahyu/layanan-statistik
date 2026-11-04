<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\SubCategorie;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SubCategorieController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['search' => ['nullable', 'string', 'max:255']]);
        $subcategories = SubCategorie::with('categorie')->when($request->filled('search'), fn ($query) => $query->where('sub_categorie_type', 'like', '%' . $request->search . '%'))->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.subcategorie.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Categorie::orderBy('categorie_type')->get();

        return view('admin.subcategorie.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sub_categorie_type' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        SubCategorie::create($data);

        return redirect()->route('admin.subcategorie.index')->with('success', 'Subkategori berhasil ditambahkan.');
    }

    public function edit(SubCategorie $subcategorie)
    {
        $categories = Categorie::orderBy('categorie_type')->get();

        return view('admin.subcategorie.edit', compact('subcategorie', 'categories'));
    }

    public function update(Request $request, SubCategorie $subcategorie)
    {
        $data = $request->validate([
            'sub_categorie_type' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        $subcategorie->update($data);

        return redirect()->route('admin.subcategorie.index')->with('success', 'Subkategori berhasil diperbarui.');
    }

    public function destroy(SubCategorie $subcategorie)
    {
        try { $subcategorie->delete(); } catch (QueryException) { return back()->with('error', 'Subkategori masih digunakan dan tidak dapat dihapus.'); }

        return redirect()->route('admin.subcategorie.index')->with('success', 'Subkategori berhasil dihapus.');
    }
}
