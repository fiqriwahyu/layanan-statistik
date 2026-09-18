<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\SubCategorie;
use Illuminate\Http\Request;

class SubCategorieController extends Controller
{
    public function index()
    {
        $subcategories = SubCategorie::with('categorie')->orderBy('created_at', 'desc')->paginate(15);

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
        $subcategorie->delete();

        return redirect()->route('admin.subcategorie.index')->with('success', 'Subkategori berhasil dihapus.');
    }
}
