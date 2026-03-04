<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
        })

        ->latest()
        ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,manager,staff', 
        ]);

        $this->userService->createUser($data);
        return back()->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8',
            'role'     => 'required|in:admin,manager,staff',
        ]);

        $this->userService->updateUser($id, $data);
        return back()->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Mencegah user menghapus dirinya sendiri
        if (auth()->id() == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $this->userService->deleteUser($id);
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}