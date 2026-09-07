<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KelolaUsersController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $query = User::with('profile');
        if ($search) {
            $query->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");
        }
        $users = $query->latest()->paginate(20)->withQueryString();
        return view('kelola-users.index', compact('users', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|unique:users,email,' . null,
            'password'  => 'required|string|min:6',
            'role'      => 'required|in:admin_master,manager,admin_ts,sampling,analis,client',
            'full_name' => 'nullable|string',
            'phone'     => 'nullable|string',
        ]);

        $email = str_contains($request->username, '@') ? $request->username : $request->username . '@lab.id';

        if (User::where('email', $email)->exists()) {
            return response()->json(['success' => false, 'message' => 'Username sudah digunakan.'], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $email,
            'password' => Hash::make($request->password),
        ]);

        Profile::create([
            'user_id'   => $user->id,
            'full_name' => $request->full_name ?? $request->name,
            'role'      => $request->role,
            'phone'     => $request->phone,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan.']);
        }

        return redirect()->back()->with('success', 'Akun staf berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->only(['name']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        if ($user->profile) {
            $user->profile->update($request->only(['full_name', 'role', 'phone']));
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Data staf berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Tidak dapat menghapus akun sendiri.'], 403);
        }
        
        $user->profile()->delete();
        $user->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Akun staf berhasil dihapus.');
    }
}
