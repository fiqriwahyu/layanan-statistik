<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['search' => ['nullable', 'string', 'max:255']]);
        $works = Work::when($request->filled('search'), fn ($query) => $query->where('work_type', 'like', '%' . $request->search . '%'))->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.work.index', compact('works'));
    }

    public function create()
    {
        return view('admin.work.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'work_type' => 'required|string|max:255',
        ]);

        Work::create($data);

        return redirect()
            ->route('admin.work.index')
            ->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function edit(Work $work)
    {
        return view('admin.work.edit', compact('work'));
    }

    public function update(Request $request, Work $work)
    {
        $data = $request->validate([
            'work_type' => 'required|string|max:255',
        ]);

        $work->update($data);

        return redirect()
            ->route('admin.work.index')
            ->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(Work $work)
    {
        try { $work->delete(); } catch (QueryException) { return back()->with('error', 'Pekerjaan masih digunakan dan tidak dapat dihapus.'); }

        return redirect()
            ->route('admin.work.index')
            ->with('success', 'Pekerjaan berhasil dihapus.');
    }
}
