<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\Neighborhood;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MosqueController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view mosques', only: ['index', 'show']),
            new Middleware('permission:create mosques', only: ['create', 'store']),
            new Middleware('permission:edit mosques', only: ['edit', 'update']),
            new Middleware('permission:delete mosques', only: ['destroy']),
        ];
    }

    public function index()
    {
        $mosques = Mosque::with(['neighborhood.province'])->withCount(['housings', 'members'])->get();
        $neighborhoods = Neighborhood::with('province')->get();

        return view('mosques.index', [
            'mosques' => $mosques,
            'neighborhoods' => $neighborhoods,
        ]);
    }

    public function create()
    {
        $neighborhoods = Neighborhood::with('province')->get();
        return view('mosques.create', ['neighborhoods' => $neighborhoods]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'neighborhood_id' => 'required|exists:neighborhoods,id',
        ]);

        Mosque::create($validated);

        return redirect()->route('mosques.index')
            ->with('success', 'تم إضافة المسجد بنجاح');
    }

    public function show(Mosque $mosque)
    {
        $mosque->load(['neighborhood.province', 'housings', 'members']);
        return view('mosques.show', ['mosque' => $mosque]);
    }

    public function edit(Mosque $mosque)
    {
        $neighborhoods = Neighborhood::with('province')->get();
        return view('mosques.edit', [
            'mosque' => $mosque,
            'neighborhoods' => $neighborhoods,
        ]);
    }

    public function update(Request $request, Mosque $mosque)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'neighborhood_id' => 'required|exists:neighborhoods,id',
        ]);

        $mosque->update($validated);

        return redirect()->route('mosques.index')
            ->with('success', 'تم تحديث بيانات المسجد بنجاح');
    }

    public function destroy(Mosque $mosque)
    {
        $mosque->delete();

        return redirect()->route('mosques.index')
            ->with('success', 'تم حذف المسجد بنجاح');
    }

    /**
     * Store a newly created resource via API (returns JSON).
     */
    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:مسجد,جامع',
            'neighborhood_id' => 'required|exists:neighborhoods,id',
        ]);

        $mosque = Mosque::create($validated);
        $mosque->load('neighborhood.province');

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المسجد بنجاح',
            'mosque' => $mosque,
        ], 201);
    }
}
