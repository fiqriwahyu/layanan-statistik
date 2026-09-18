<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.education.index', compact('educations'));
    }

    public function create()
    {
        return view('admin.education.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'education_type' => 'required|string|max:255',
        ]);

        Education::create($data);

        return redirect()->route('admin.education.index')->with('success', 'Pendidikan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $education = Education::findOrFail($id);

        return view('admin.education.edit', compact('education'));
    }

    public function update(Request $request, $id)
    {
        $education = Education::findOrFail($id);
        $data = $request->validate([
            'education_type' => 'required|string|max:255',
        ]);

        $education->update($data);

        return redirect()->route('admin.education.index')->with('success', 'Pendidikan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $education = Education::findOrFail($id);
        $education->delete();

        return redirect()->route('admin.education.index')->with('success', 'Pendidikan berhasil dihapus.');
    }
}
