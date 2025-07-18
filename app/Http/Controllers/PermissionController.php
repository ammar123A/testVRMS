<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlPermission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = FlPermission::query();

        if ($request->em_id) {
            $query->where('em_id', 'like', '%' . $request->em_id . '%');
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $permissions = $query->orderBy('updated_at', 'desc')->paginate(10);

        return view('supervisor.permission.permission', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'em_id' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|max:50',
        ]);

        FlPermission::create($request->all());

        return redirect()->route('permission.index')->with('success', 'Permission saved.');
    }
}

