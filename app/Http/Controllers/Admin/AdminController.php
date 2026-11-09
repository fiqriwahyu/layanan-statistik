<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

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

        $admin = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);

        try {
            $admin->sendEmailVerificationNotification();
        } catch (TransportExceptionInterface) {
            return redirect()
                ->route('superadmin.admin.index')
                ->with('error', 'Akun admin berhasil dibuat, tetapi email verifikasi gagal dikirim. Kirim ulang dari daftar admin.');
        }

        return redirect()
            ->route('superadmin.admin.index')
            ->with('success', 'Admin berhasil ditambahkan. Email verifikasi telah dikirim.');
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

        $emailChanged = $admin->email !== $data['email'];
        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if ($emailChanged) {
            $admin->email_verified_at = null;
        }

        $admin->save();

        if (!empty($data['password'])) {
            $admin->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        if ($emailChanged) {
            try {
                $admin->sendEmailVerificationNotification();
            } catch (TransportExceptionInterface) {
                return redirect()
                    ->route('superadmin.admin.index')
                    ->with('error', 'Data admin berhasil diperbarui, tetapi email verifikasi gagal dikirim. Kirim ulang dari daftar admin.');
            }
        }

        return redirect()
            ->route('superadmin.admin.index')
            ->with('success', $emailChanged
                ? 'Admin berhasil diperbarui. Email verifikasi baru telah dikirim.'
                : 'Admin berhasil diperbarui.');
    }

    public function resendVerification(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        if ($admin->hasVerifiedEmail()) {
            return redirect()
                ->route('superadmin.admin.index')
                ->with('error', 'Email admin tersebut sudah terverifikasi.');
        }

        try {
            $admin->sendEmailVerificationNotification();
        } catch (TransportExceptionInterface) {
            return redirect()
                ->route('superadmin.admin.index')
                ->with('error', 'Email verifikasi gagal dikirim. Periksa konfigurasi email lalu coba lagi.');
        }

        return redirect()
            ->route('superadmin.admin.index')
            ->with('success', 'Email verifikasi telah dikirim ulang kepada admin.');
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
