<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['posts', 'comments']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn ($s) =>
                $s->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
                  ->orWhere('username', 'like', "%{$q}%")
            );
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        // Prevent admin from demoting themselves
        abort_if($user->id === auth()->id(), 403, 'لا يمكنك تغيير دورك الخاص.');

        $validated = $request->validate([
            'role' => ['required', Rule::in(['user', 'moderator', 'admin'])],
        ]);

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "تم تغيير دور {$user->name} إلى {$validated['role']}.");
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'لا يمكنك حذف حسابك الخاص من هنا.');

        $user->delete();

        return back()->with('success', 'تم حذف المستخدم.');
    }
}
