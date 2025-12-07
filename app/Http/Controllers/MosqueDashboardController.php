<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Document;
use App\Models\Housing;
use App\Models\Member;
use App\Models\Mosque;
use App\Models\Neighborhood;
use App\Models\Profession;
use App\Models\Province;
use App\Models\TransferHistory;

class MosqueDashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request): \Illuminate\Contracts\View\View
    {
        $provinces = Province::withCount(['neighborhoods', 'mosques'])->get();
        $neighborhoods = Neighborhood::with(['province'])->withCount(['mosques', 'members'])->get();
        $mosques = Mosque::with(['neighborhood.province'])->withCount(['housings', 'members'])->get();
        $members = Member::with(['mosque', 'housing'])->get();
        $housings = Housing::with(['mosque', 'member'])->get();
        $documents = Document::all();
        $transferHistory = TransferHistory::all();

        $mainStats = [
            'neighborhoods' => Neighborhood::count(),
            'provinces' => Province::count(),
            'mosques' => Mosque::count(),
            'members' => Member::count(),
            'housings' => Housing::count(),
            'categories' => Category::count(),
            'professions' => Profession::count(),
        ];

        // تحديد الصفحة الحالية من route name
        $currentView = 'dashboard';
        $routeName = $request->route()->getName();

        if ($routeName === 'members') {
            $currentView = 'members';
        } elseif ($routeName === 'mosques') {
            $currentView = 'mosques';
        } elseif ($routeName === 'provinces') {
            $currentView = 'provinces';
        } elseif ($routeName === 'neighborhoods') {
            $currentView = 'neighborhoods';
        } elseif ($routeName === 'housing') {
            $currentView = 'housing';
        }

        return view('mosque-dashboard', [
            'provinces' => $provinces,
            'neighborhoods' => $neighborhoods,
            'mosques' => $mosques,
            'members' => $members,
            'housings' => $housings,
            'documents' => $documents,
            'transferHistory' => $transferHistory,
            'mainStats' => $mainStats,
            'currentView' => $currentView,
        ]);
    }
}
