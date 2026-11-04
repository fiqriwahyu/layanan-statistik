<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purpose;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PurposeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['search' => ['nullable', 'string', 'max:255']]);
        $purposes = Purpose::when($request->filled('search'), fn ($query) => $query->where('purpose_type', 'like', '%' . $request->search . '%'))->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.purpose.index', compact('purposes'));
    }

    public function create()
    {
        return view('admin.purpose.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'purpose_type' => 'required|string|max:255',
        ]);

        Purpose::create($data);

        return redirect()->route('admin.purpose.index')->with('success', 'Tujuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $purpose = Purpose::findOrFail($id);

        return view('admin.purpose.edit', compact('purpose'));
    }

    public function update(Request $request, $id)
    {
        $purpose = Purpose::findOrFail($id);
        $data = $request->validate([
            'purpose_type' => 'required|string|max:255',
        ]);

        $purpose->update($data);

        return redirect()->route('admin.purpose.index')->with('success', 'Tujuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $purpose = Purpose::findOrFail($id);
        try { $purpose->delete(); } catch (QueryException) { return back()->with('error', 'Tujuan masih digunakan dan tidak dapat dihapus.'); }

        return redirect()->route('admin.purpose.index')->with('success', 'Tujuan berhasil dihapus.');
    }
}
