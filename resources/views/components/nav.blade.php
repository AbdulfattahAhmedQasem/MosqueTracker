<nav class="mb-6 bg-white rounded-xl shadow-lg p-4">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <!-- Logo and Title -->
        <div class="flex items-center gap-2">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <h1 class="text-2xl font-bold text-slate-800">نظام إدارة المساجد</h1>
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('dashboard') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                الرئيسية
            </a>
            <a href="{{ route('members.index') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('members.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                المنسوبين
            </a>
            <a href="{{ route('mosques.index') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('mosques.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                المساجد
            </a>
            <a href="{{ route('provinces.index') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('provinces.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                المحافظات
            </a>
            <a href="{{ route('neighborhoods.index') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('neighborhoods.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                الأحياء
            </a>
            <a href="{{ route('housing.index') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('housing.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                السكن
            </a>
            <a href="{{ route('categories.index') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('categories.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                الفئات
            </a>
            <a href="{{ route('professions.index') }}" 
               class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('professions.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                المهن
            </a>
            
            <!-- Admin Section (Super Admin Only) -->
            @role('super-admin')
                <div class="w-px h-6 bg-slate-300 mx-2"></div>
                <a href="{{ route('users.index') }}" 
                   class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    المستخدمين
                </a>
                <a href="{{ route('roles.index') }}" 
                   class="px-4 py-2 rounded-lg transition-colors {{ request()->routeIs('roles.*') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    الأدوار
                </a>
            @endrole

            <!-- User Info & Logout -->
            <div class="w-px h-6 bg-slate-300 mx-2"></div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-2 bg-slate-100 rounded-lg">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors flex items-center gap-2"
                            title="تسجيل الخروج">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        خروج
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

