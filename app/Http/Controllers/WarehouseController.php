<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Warehouse;

use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index()
    {        $user = Auth::user();

        $warehouses = Warehouse::all();
        return view('warehouses.index', compact('user','warehouses'));


    }

    public function create()
    {
        $user = Auth::user();
        return view('warehouses.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
            'location' => 'required|max:255',

        ]);

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')->with('success', 'Warehouse created successfully.');
    }

    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse'));
    }

    public function edit(Warehouse $warehouse)
    {        $user = Auth::user();

        return view('warehouses.edit', compact('warehouse','user'));
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|max:100',
            'location' => 'required|max:255',

        ]);

        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('warehouses.index')->with('success', 'Warehouse deleted successfully.');
    }
}
