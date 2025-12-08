<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::paginate(15);
        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,perawat,dokter,rekam_medis,farmasi',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'role.required' => 'Role harus dipilih',
        ]);

        try {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            return redirect()->route('users.index')
                           ->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambahkan user: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        return view('pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,perawat,dokter,rekam_medis,farmasi',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'role.required' => 'Role harus dipilih',
        ]);

        try {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->role = $data['role'];
            
            if ($data['password']) {
                $user->password = Hash::make($data['password']);
            }
            
            $user->save();

            return redirect()->route('users.show', $user->id)
                           ->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui user: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')
                           ->with('success', 'User berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus user: ' . $e->getMessage()]);
        }
    }
}
