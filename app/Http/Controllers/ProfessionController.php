<?php

namespace App\Http\Controllers;

use App\Models\Profession;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $professions = Profession::withCount('members')->orderBy('name')->get();

        return view('professions.index', [
            'professions' => $professions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('professions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:professions,name',
            'description' => 'nullable|string',
        ]);

        Profession::create($validated);

        return redirect()->route('professions.index')
            ->with('success', 'تم إضافة المهنة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profession $profession)
    {
        $profession->load('members');

        return view('professions.show', ['profession' => $profession]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profession $profession)
    {
        return view('professions.edit', ['profession' => $profession]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profession $profession)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:professions,name,'.$profession->id,
            'description' => 'nullable|string',
        ]);

        $profession->update($validated);

        return redirect()->route('professions.index')
            ->with('success', 'تم تحديث بيانات المهنة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profession $profession)
    {
        if ($profession->members()->count() > 0) {
            return redirect()->route('professions.index')
                ->with('error', 'لا يمكن حذف هذه المهنة لأنها مرتبطة بمنسوبين');
        }

        $profession->delete();

        return redirect()->route('professions.index')
            ->with('success', 'تم حذف المهنة بنجاح');
    }

    /**
     * Store a newly created resource via API (returns JSON).
     */
    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:professions,name',
            'description' => 'nullable|string',
        ]);

        $profession = Profession::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المهنة بنجاح',
            'profession' => $profession,
        ], 201);
    }
}
