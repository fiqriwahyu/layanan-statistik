<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['search' => ['nullable', 'string', 'max:255']]);
        $services = Service::when($request->filled('search'), fn ($query) => $query->where('service_type', 'like', '%' . $request->search . '%'))->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_type' => 'required|string|max:255',
        ]);

        Service::create($data);

        return redirect()->route('admin.service.index')->with('success', 'Service berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);

        return view('admin.service.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $data = $request->validate([
            'service_type' => 'required|string|max:255',
        ]);

        $service->update($data);

        return redirect()->route('admin.service.index')->with('success', 'Service berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        try { $service->delete(); } catch (QueryException) { return back()->with('error', 'Layanan masih digunakan dan tidak dapat dihapus.'); }

        return redirect()->route('admin.service.index')->with('success', 'Service berhasil dihapus.');
    }
}
