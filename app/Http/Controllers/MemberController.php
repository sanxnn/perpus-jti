<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->latest()->paginate(10);
        return view('members', compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'npm' => 'required|string|unique:users',
            'phone' => 'nullable|string|max:15',
            'department' => 'required|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'npm.required' => 'NIM/NIP wajib diisi.',
            'npm.unique' => 'NIM/NIP sudah terdaftar.',
            'department.required' => 'Program studi wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $password = $request->password ?: 'password';

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'npm' => $request->npm,
            'phone' => $request->phone,
            'department' => $request->department,
            'password' => Hash::make($password),
            'role' => 'user',
        ]);

        return redirect()->route('members.index')
            ->with('success', ' Anggota berhasil ditambahkan. Password: `' . $password . '`');
    }

    public function update(Request $request, User $user)
    {
        // 🔒 Proteksi: Pastikan hanya user biasa yang bisa di-update
        if ($user->role !== 'user') {
            return redirect()->back()->withErrors([
                'error' => '❌ Tidak bisa mengedit akun admin.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'npm' => 'required|string|unique:users,npm,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'department' => 'required|string|max:100',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh anggota lain.',
            'npm.unique' => 'NIM/NIP sudah terdaftar.',
            'department.required' => 'Program studi wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'edit') // ✅ untuk $errors->edit
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'npm' => $request->npm,
            'phone' => $request->phone,
            'department' => $request->department,
        ]);

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('members.index')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // 🔒 Proteksi: hanya user biasa yang bisa dihapus
        if ($user->role !== 'user') {
            return redirect()->back()->withErrors([
                'error' => '❌ Tidak bisa menghapus akun admin.'
            ]);
        }

        $user->delete();

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}