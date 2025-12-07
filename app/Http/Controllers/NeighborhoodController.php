<?php

namespace App\Http\Controllers;

use App\Models\Neighborhood;
use App\Models\Province;
use Illuminate\Http\Request;

class NeighborhoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $neighborhoods = Neighborhood::with(['province'])->withCount(['mosques', 'members'])->get();
        $provinces = Province::all();

        return view('neighborhoods.index', [
            'neighborhoods' => $neighborhoods,
            'provinces' => $provinces,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $provinces = Province::all();
        return view('neighborhoods.create', ['provinces' => $provinces]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
        ]);

        Neighborhood::create($validated);

        return redirect()->route('neighborhoods.index')
            ->with('success', 'تم إضافة الحي بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Neighborhood $neighborhood)
    {
        $neighborhood->load(['province', 'mosques']);
        return view('neighborhoods.show', ['neighborhood' => $neighborhood]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Neighborhood $neighborhood)
    {
        $provinces = Province::all();
        return view('neighborhoods.edit', [
            'neighborhood' => $neighborhood,
            'provinces' => $provinces,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Neighborhood $neighborhood)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|exists:provinces,id',
        ]);

        $neighborhood->update($validated);

        return redirect()->route('neighborhoods.index')
            ->with('success', 'تم تحديث بيانات الحي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Neighborhood $neighborhood)
    {
        $neighborhood->delete();

        return redirect()->route('neighborhoods.index')
            ->with('success', 'تم حذف الحي بنجاح');
    }
}
