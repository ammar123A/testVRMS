<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SystemAdminController extends Controller
{
    public function userPage(Request $request)
    {
        $query = User::query();

        if ($request->filled('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        if ($request->filled('em_id')) {
            $query->where('em_id', 'like', '%' . $request->em_id . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        $users = $query->get();

        $roles = ['MAINTENANCE', 'SUPERVISOR', 'FLEETMANAGEMENT', 'ADMIN']; // Adjust as needed

        return view('system-admin.user', compact('users', 'roles'));
    }

    public function RegisterForm()
    {
        $users = User::orderBy('id', 'desc')->get();

        $roles = ['staff', 'student'];

        return view('system-admin.user-register', compact('users', 'roles'));
    }


    public function fetchUserData(Request $request)
    {
        $query = User::query(); // or your user model

        if ($request->filled('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        if ($request->filled('em_id')) {
            $query->where('em_id', 'like', '%' . $request->em_id . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('faculty')) {
            $query->where('faculty', $request->faculty);
        }

        $users = $query->get();

        return view('system-admin.user', compact('users'));
    }


    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'em_id' => 'required',
            'email' => 'nullable|email',
            'faculty' => 'required',
            'role' => 'required|in:staff,student',
        ]);

        $validated['password'] = Hash::make('default123');

        User::create($validated);

        // Optional: Log, notify, or email the plain password to the user
        return redirect()->back()->with('success', 'User registered with password: ');
    }

}
