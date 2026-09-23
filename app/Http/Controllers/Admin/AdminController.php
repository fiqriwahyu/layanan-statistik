<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('superadmin.admin.index', compact('admins'));
    }

    public function create()
    {
        return view('superadmin.admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);

        return redirect()
            ->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        return view('superadmin.admin.edit', compact('admin'));
    }

    public function update(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $admin->id,
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $admin->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (!empty($data['password'])) {
            $admin->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        return redirect()
            ->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->delete();

        return redirect()
            ->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}