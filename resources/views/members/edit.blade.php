<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تعديل بيانات المنسوب - {{ $member->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.mosquesData = {!! json_encode($mosques, JSON_UNESCAPED_UNICODE) !!};
        window.housingsData = {!! json_encode($housings, JSON_UNESCAPED_UNICODE) !!};
    </script>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0; min-height: 100vh; width: 100%;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; direction: rtl;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6"
      x-data="{
          selectedMosque: '{{ old('mosque_id', $member->mosque_id) }}',
          selectedHousing: '{{ old('housing_id', $member->housing_id ?? '') }}',
          mosques: [],
          housings: [],
          get availableHousing() {
              if (!this.selectedMosque || this.selectedMosque === '') return [];
              if (!this.housings || !Array.isArray(this.housings) || this.housings.length === 0) return [];
              
              // تحويل selectedMosque إلى number
              const selectedMosqueId = Number(this.selectedMosque);
              if (!selectedMosqueId || isNaN(selectedMosqueId)) return [];
              
              // فلترة السكن حسب المسجد المختار فقط
              return this.housings.filter(h => {
                  // التأكد من أن mosque_id هو number
                  const housingMosqueId = Number(h.mosque_id);
                  return housingMosqueId === selectedMosqueId;
              });
          }
      }"
      x-init="
          mosques = window.mosquesData || [];
          const rawHousings = window.housingsData || [];
          if (rawHousings && Array.isArray(rawHousings) && rawHousings.length > 0) {
              housings = rawHousings.map(h => ({
                  id: Number(h.id),
                  name: h.name,
                  mosque_id: Number(h.mosque_id),
                  created_at: h.created_at,
                  updated_at: h.updated_at
              }));
          } else {
              housings = [];
          }
      ">
    @include('components.nav')
    
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">تعديل بيانات المنسوب</h1>
            <a href="{{ route('members.show', $member) }}" class="text-blue-500 hover:text-blue-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                العودة لتفاصيل المنسوب
            </a>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('members.update', $member) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الاسم الكامل *</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $member->name) }}"
                        required
                        placeholder="مثال: أحمد محمد علي"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">المسجد *</label>
                    <select 
                        name="mosque_id" 
                        required 
                        x-model="selectedMosque"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">اختر المسجد</option>
                        @foreach($mosques as $mosque)
                            <option value="{{ $mosque->id }}" {{ old('mosque_id', $member->mosque_id) == $mosque->id ? 'selected' : '' }}>
                                {{ $mosque->name }} ({{ $mosque->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">السكن (اختياري)</label>
                    <select 
                        name="housing_id"
                        x-model="selectedHousing"
                        :disabled="!selectedMosque || availableHousing.length === 0"
                        :class="!selectedMosque || availableHousing.length === 0 ? 'bg-slate-100 cursor-not-allowed' : ''"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="" x-text="!selectedMosque ? 'اختر المسجد أولاً' : (availableHousing.length === 0 ? 'لا يوجد سكن متاح لهذا المسجد' : 'بدون سكن')"></option>
                        <template x-for="h in availableHousing" :key="h.id">
                            <option :value="h.id" x-text="h.name"></option>
                        </template>
                    </select>
                    <template x-if="selectedMosque && availableHousing.length > 0">
                        <p class="text-xs text-slate-500 mt-1">
                            <span x-text="availableHousing.length"></span> سكن متاح لهذا المسجد
                        </p>
                    </template>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الفئة *</label>
                    <select 
                        name="category_id" 
                        required 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">اختر الفئة</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $member->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">المهنة *</label>
                    <select 
                        name="profession_id" 
                        required 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">اختر المهنة</option>
                        @foreach($professions as $profession)
                            <option value="{{ $profession->id }}" {{ old('profession_id', $member->profession_id) == $profession->id ? 'selected' : '' }}>
                                {{ $profession->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الرقم الوظيفي *</label>
                    <input
                        type="text"
                        name="employee_number"
                        value="{{ old('employee_number', $member->employee_number) }}"
                        required
                        placeholder="E-2024-001"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">رقم الهاتف *</label>
                    <input
                        type="tel"
                        name="phone"
                        value="{{ old('phone', $member->phone) }}"
                        required
                        placeholder="05xxxxxxxx"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">رقم الهوية *</label>
                    <input
                        type="text"
                        name="national_id"
                        value="{{ old('national_id', $member->national_id) }}"
                        required
                        pattern="[0-9]{10}"
                        maxlength="10"
                        minlength="10"
                        placeholder="1234567890"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('national_id') border-red-500 @enderror"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    />
                    <p class="mt-1 text-sm text-slate-500">يجب أن يكون بالضبط 10 أرقام</p>
                    @error('national_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">قرار التعيين</label>
                    <input
                        type="text"
                        name="appointment_decision"
                        value="{{ old('appointment_decision', $member->appointment_decision) }}"
                        placeholder="قرار رقم 123/2024"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ التعيين *</label>
                    <input
                        type="date"
                        name="appointment_date"
                        value="{{ old('appointment_date', $member->appointment_date?->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">حالة المنسوب *</label>
                    <select 
                        name="status" 
                        required 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="نشط" {{ old('status', $member->status) == 'نشط' ? 'selected' : '' }}>نشط</option>
                        <option value="غير نشط" {{ old('status', $member->status) == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ route('members.show', $member) }}" 
                   class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</body>
</html>

