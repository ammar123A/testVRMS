<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlPart;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $query = FlPart::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        $parts = $query->orderBy('updated_at', 'desc')->get();
        $categories = ['ACCESSORIES', 'ENGINE', 'BATTERY', 'TYRE', 'ELECTRICAL']; // replace with your actual categories

        return view('supervisor.part.part', compact('parts', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        FlPart::create($request->only('category', 'name'));

        return redirect()->route('parts.index')->with('success', 'Part/Item added successfully!');
    }
}
