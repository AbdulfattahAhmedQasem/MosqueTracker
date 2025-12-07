<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إضافة دور جديد - نظام إدارة المساجد</title>
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
    
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">إضافة دور جديد</h1>
            <p class="text-slate-600">أدخل اسم الدور وحدد الصلاحيات الممنوحة له</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-red-600">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- اسم الدور -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">اسم الدور</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
            </div>

            <!-- الصلاحيات -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-4">الصلاحيات</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($permissions as $permission)
                        <div class="flex items-center p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                            <input type="checkbox" 
                                   id="permission_{{ $permission->id }}" 
                                   name="permissions[]" 
                                   value="{{ $permission->name }}"
                                   class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                            <label for="permission_{{ $permission->id }}" class="mr-2 text-sm text-slate-700 cursor-pointer w-full">
                                {{ \App\Helpers\PermissionHelper::translate($permission->name) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-slate-100">
                <a href="{{ route('roles.index') }}" 
                   class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                    حفظ الدور
                </button>
            </div>
        </form>
    </div>
</body>
</html>
