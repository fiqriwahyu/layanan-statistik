<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->paginate(15);

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
        $service->delete();

        return redirect()->route('admin.service.index')->with('success', 'Service berhasil dihapus.');
    }
}
