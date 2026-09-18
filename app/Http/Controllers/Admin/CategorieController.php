<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('subCategories')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.categorie.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categorie.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categorie_type' => 'required|string|max:255',
        ]);

        Categorie::create($data);

        return redirect()->route('admin.categorie.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Categorie $categorie)
    {
        return view('admin.categorie.edit', ['kategori' => $categorie]);
    }

    public function update(Request $request, Categorie $categorie)
    {
        $data = $request->validate([
            'categorie_type' => 'required|string|max:255',
        ]);

        $categorie->update($data);

        return redirect()->route('admin.categorie.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return redirect()->route('admin.categorie.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
