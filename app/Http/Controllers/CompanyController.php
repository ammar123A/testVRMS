<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlCompany;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = FlCompany::when($request->name, function ($query) use ($request) {
                $query->where('name', 'ILIKE', "%{$request->name}%");
            })
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('supervisor.company.company', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roc' => 'nullable|string|max:50',
            'contact_person' => 'nullable|string|max:100',
            'tel' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'state' => 'nullable|string|max:50',
        ]);

        FlCompany::create($request->all());

        return redirect()->route('company.index')->with('success', 'Company registered successfully.');
    }
}

