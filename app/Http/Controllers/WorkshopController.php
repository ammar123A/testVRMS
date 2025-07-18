<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlWs;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        $query = FlWs::query();

        if ($request->has('search')) {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        $workshops = $query->orderBy('updated_at', 'desc')->paginate(10);
        $editWorkshop = null;

        if ($request->has('edit')) {
            $editWorkshop = FlWs::find($request->edit);
        }

        return view('supervisor.workshop.workshop', compact('workshops', 'editWorkshop'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'tel' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
        ]);

        FlWs::create($request->only(['name', 'contact_person', 'tel', 'fax']));

        return redirect()->route('workshop.index')->with('success', 'Workshop created successfully.');
    }

    public function update(Request $request, $id)
    {
        $workshop = FlWs::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'tel' => 'nullable|string|max:20',
            'fax' => 'nullable|string|max:20',
        ]);

        $workshop->update($request->only(['name', 'contact_person', 'tel', 'fax']));

        return redirect()->route('workshop.index')->with('success', 'Workshop updated successfully.');
    }

    public function destroy($id)
    {
        FlWs::findOrFail($id)->delete();
        return redirect()->route('workshop.index')->with('success', 'Workshop deleted.');
    }

}
