<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $provinces = Province::withCount(['neighborhoods', 'mosques'])->get();

        return view('provinces.index', [
            'provinces' => $provinces,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('provinces.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:provinces,name',
        ]);

        Province::create($validated);

        return redirect()->route('provinces.index')
            ->with('success', 'تم إضافة المحافظة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        $province->load(['neighborhoods.mosques']);
        return view('provinces.show', ['province' => $province]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Province $province)
    {
        return view('provinces.edit', ['province' => $province]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Province $province)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:provinces,name,' . $province->id,
        ]);

        $province->update($validated);

        return redirect()->route('provinces.index')
            ->with('success', 'تم تحديث بيانات المحافظة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province)
    {
        $province->delete();

        return redirect()->route('provinces.index')
            ->with('success', 'تم حذف المحافظة بنجاح');
    }
}
