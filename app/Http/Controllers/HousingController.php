<?php

namespace App\Http\Controllers;

use App\Models\Housing;
use App\Models\Mosque;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class HousingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view housings', only: ['index', 'show']),
            new Middleware('permission:create housings', only: ['create', 'store']),
            new Middleware('permission:edit housings', only: ['edit', 'update']),
            new Middleware('permission:delete housings', only: ['destroy']),
        ];
    }

    public function index()
    {
        $housings = Housing::with(['mosque', 'member'])->get();
        $mosques = Mosque::all();

        return view('housing.index', [
            'housings' => $housings,
            'mosques' => $mosques,
        ]);
    }

    public function create()
    {
        $mosques = Mosque::all();
        return view('housing.create', ['mosques' => $mosques]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mosque_id' => 'required|exists:mosques,id',
        ]);

        Housing::create($validated);

        return redirect()->route('housing.index')
            ->with('success', 'تم إضافة السكن بنجاح');
    }

    public function show(Housing $housing)
    {
        $housing->load(['mosque', 'member']);
        return view('housing.show', ['housing' => $housing]);
    }

    public function edit(Housing $housing)
    {
        $mosques = Mosque::all();
        return view('housing.edit', [
            'housing' => $housing,
            'mosques' => $mosques,
        ]);
    }

    public function update(Request $request, Housing $housing)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mosque_id' => 'required|exists:mosques,id',
        ]);

        $housing->update($validated);

        return redirect()->route('housing.index')
            ->with('success', 'تم تحديث بيانات السكن بنجاح');
    }

    public function destroy(Housing $housing)
    {
        $housing->delete();

        return redirect()->route('housing.index')
            ->with('success', 'تم حذف السكن بنجاح');
    }
}
