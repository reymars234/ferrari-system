<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogService;

class AdminUserController extends Controller
{
    public function index()
    {
        $users   = User::where('role', 'user')->latest()->paginate(20);
        $drivers = User::where('role', 'driver')->latest()->get();

        return view('admin.users.index', compact('users', 'drivers'));
    }

    public function destroy(User $user)
    {
        $email = $user->email;
        $user->delete();
        AuditLogService::log('USER_DELETED', 'Users', "User deleted: {$email}");
        return back()->with('success', 'User deleted.');
    }
}