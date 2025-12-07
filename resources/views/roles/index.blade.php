<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة الأدوار - نظام إدارة المساجد</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0; min-height: 100vh; width: 100%;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; direction: rtl;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    @include('components.nav')
    
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-slate-800 mb-2">إدارة الأدوار والصلاحيات</h1>
            </div>
            <a href="{{ route('roles.create') }}"
               class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة دور جديد
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">اسم الدور</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الصلاحيات</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">عدد المستخدمين</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-slate-700 font-medium">{{ $role->name }}</td>
                                <td class="px-4 py-4 text-slate-700">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($role->permissions->take(5) as $permission)
                                            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">{{ \App\Helpers\PermissionHelper::translate($permission->name) }}</span>
                                        @endforeach
                                        @if($role->permissions->count() > 5)
                                            <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">+{{ $role->permissions->count() - 5 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-700">{{ $role->users()->count() }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex gap-1">
                                        <a href="{{ route('roles.edit', $role) }}"
                                           class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors" title="تعديل">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if($role->name !== 'super-admin')
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="حذف">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">لا توجد أدوار مسجلة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
