<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purpose;
use Illuminate\Http\Request;

class PurposeController extends Controller
{
    public function index()
    {
        $purposes = Purpose::orderBy('created_at', 'desc')->paginate(15);

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
        $purpose->delete();

        return redirect()->route('admin.purpose.index')->with('success', 'Tujuan berhasil dihapus.');
    }
}
