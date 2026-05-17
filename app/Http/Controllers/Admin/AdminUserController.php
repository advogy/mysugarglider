<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $q      = $request->input('q');
        $filter = $request->input('filter', 'all');

        $users = User::where('id', '!=', Auth::id())
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            }))
            ->when($filter === 'admin',  fn($query) => $query->where('role', 'admin'))
            ->when($filter === 'banned', fn($query) => $query->where('status', false))
            ->when($filter === 'user',   fn($query) => $query->where('role', 'user')->where('status', true))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.v_index', compact('users', 'q', 'filter'));
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat mengubah status akun sendiri.');
        }

        $user->status = !$user->status;
        $user->save();

        $label = $user->status ? 'diaktifkan' : 'diblokir';
        return back()->with('pesan', "Pengguna {$user->name} berhasil {$label}.");
    }

    public function toggleRole(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat mengubah role akun sendiri.');
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();

        $label = $user->role === 'admin' ? 'dijadikan Admin' : 'dikembalikan ke User';
        return back()->with('pesan', "Pengguna {$user->name} berhasil {$label}.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return back()->with('pesan', "Pengguna {$user->name} berhasil dihapus.");
    }
}
