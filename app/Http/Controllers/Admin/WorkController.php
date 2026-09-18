<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Work;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function index()
    {
        $works = Work::orderBy('created_at', 'desc')->paginate(15);

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

        return redirect()->route('admin.work.index')->with('success', 'Pekerjaan berhasil ditambahkan.');
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

        return redirect()->route('admin.work.index')->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(Work $work)
    {
        $work->delete();

        return redirect()->route('admin.work.index')->with('success', 'Pekerjaan berhasil dihapus.');
    }
}
