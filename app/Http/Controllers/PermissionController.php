<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlPermission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions with optional filtering.
     */
    public function index(Request $request)
    {
        $permissions = FlPermission::query()
            ->when($request->filled('em_id'), fn($query) =>
                $query->where('em_id', 'like', '%' . $request->input('em_id') . '%')
            )
            ->when($request->filled('name'), fn($query) =>
                $query->where('name', 'like', '%' . $request->input('name') . '%')
            )
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('supervisor.permission.permission', compact('permissions'));
    }

    /**
     * Store a newly created permission in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'em_id' => 'required|string|max:20',
            'name'  => 'required|string|max:255',
            'email' => 'nullable|string|email|max:50',
        ]);

        FlPermission::create($validated);

        return redirect()
            ->route('permission.index')
            ->with('success', 'Permission saved successfully.');
    }
}
