<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إضافة منسوب جديد - نظام إدارة المساجد</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.mosquesData = {!! json_encode($mosques, JSON_UNESCAPED_UNICODE) !!};
        window.housingsData = {!! json_encode($housings, JSON_UNESCAPED_UNICODE) !!};
        window.neighborhoodsData = {!! json_encode($neighborhoods, JSON_UNESCAPED_UNICODE) !!};
        window.provincesData = {!! json_encode($provinces, JSON_UNESCAPED_UNICODE) !!};
        
        function memberFormData() {
            return {
                selectedMosque: '',
                mosques: [],
                housings: [],
                documents: [],
                neighborhoods: [],
                provinces: [],
                showCategoryModal: false,
                showProfessionModal: false,
                showMosqueModal: false,
                newCategory: { name: '', description: '' },
                newProfession: { name: '', description: '' },
                newMosque: { name: '', type: 'مسجد', neighborhood_id: '' },
                loading: false,
                documentTypes: [
                    { value: 'طي_القيد', label: 'وثيقة طي القيد' },
                    { value: 'تعيين_السكن', label: 'وثيقة تعيين السكن' },
                    { value: 'عقد_السكن', label: 'عقد السكن' },
                    { value: 'الغياب', label: 'وثيقة الغياب' },
                    { value: 'أخرى', label: 'وثيقة أخرى' }
                ],
                get availableHousing() {
                    if (!this.selectedMosque || this.selectedMosque === '') return [];
                    if (!this.housings || !Array.isArray(this.housings) || this.housings.length === 0) return [];
                    
                    const selectedMosqueId = Number(this.selectedMosque);
                    if (!selectedMosqueId || isNaN(selectedMosqueId)) return [];
                    
                    return this.housings.filter(h => {
                        const housingMosqueId = Number(h.mosque_id);
                        return housingMosqueId === selectedMosqueId;
                    });
                },
                addDocument() {
                    this.documents.push({
                        id: Date.now(),
                        file: null,
                        document_type: '',
                        document_name: '',
                        upload_date: '',
                        notes: ''
                    });
                },
                removeDocument(index) {
                    this.documents.splice(index, 1);
                },
                handleFileChange(index, event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.documents[index].file = file;
                        if (!this.documents[index].document_name) {
                            this.documents[index].document_name = file.name.replace(/\.[^/.]+$/, '');
                        }
                    }
                },
                formatFileSize(bytes) {
                    if (!bytes) return '';
                    if (bytes < 1024) return bytes + ' B';
                    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
                    return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                },
                async addCategory() {
                    if (!this.newCategory.name.trim()) {
                        alert('يرجى إدخال اسم الفئة');
                        return;
                    }
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('api.categories.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.newCategory)
                        });
                        const data = await response.json();
                        if (data.success) {
                            const select = document.querySelector('select[name="category_id"]');
                            const option = document.createElement('option');
                            option.value = data.category.id;
                            option.textContent = data.category.name;
                            option.selected = true;
                            select.appendChild(option);
                            this.showCategoryModal = false;
                            this.newCategory = { name: '', description: '' };
                            alert('تم إضافة الفئة بنجاح');
                        } else {
                            alert('حدث خطأ: ' + (data.message || 'فشل إضافة الفئة'));
                        }
                    } catch (error) {
                        alert('حدث خطأ: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },
                async addProfession() {
                    if (!this.newProfession.name.trim()) {
                        alert('يرجى إدخال اسم المهنة');
                        return;
                    }
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('api.professions.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.newProfession)
                        });
                        const data = await response.json();
                        if (data.success) {
                            const select = document.querySelector('select[name="profession_id"]');
                            const option = document.createElement('option');
                            option.value = data.profession.id;
                            option.textContent = data.profession.name;
                            option.selected = true;
                            select.appendChild(option);
                            this.showProfessionModal = false;
                            this.newProfession = { name: '', description: '' };
                            alert('تم إضافة المهنة بنجاح');
                        } else {
                            alert('حدث خطأ: ' + (data.message || 'فشل إضافة المهنة'));
                        }
                    } catch (error) {
                        alert('حدث خطأ: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },
                async addMosque() {
                    if (!this.newMosque.name.trim()) {
                        alert('يرجى إدخال اسم المسجد');
                        return;
                    }
                    if (!this.newMosque.neighborhood_id) {
                        alert('يرجى اختيار الحي');
                        return;
                    }
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route('api.mosques.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(this.newMosque)
                        });
                        const data = await response.json();
                        if (data.success) {
                            const select = document.querySelector('select[name="mosque_id"]');
                            const option = document.createElement('option');
                            option.value = data.mosque.id;
                            option.textContent = data.mosque.name + ' (' + data.mosque.type + ')';
                            option.selected = true;
                            select.appendChild(option);
                            this.mosques.push(data.mosque);
                            this.selectedMosque = data.mosque.id.toString();
                            this.showMosqueModal = false;
                            this.newMosque = { name: '', type: 'مسجد', neighborhood_id: '' };
                            alert('تم إضافة المسجد بنجاح');
                        } else {
                            alert('حدث خطأ: ' + (data.message || 'فشل إضافة المسجد'));
                        }
                    } catch (error) {
                        alert('حدث خطأ: ' + error.message);
                    } finally {
                        this.loading = false;
                    }
                },
                init() {
                    this.mosques = window.mosquesData || [];
                    this.neighborhoods = window.neighborhoodsData || [];
                    this.provinces = window.provincesData || [];
                    const rawHousings = window.housingsData || [];
                    if (rawHousings && Array.isArray(rawHousings) && rawHousings.length > 0) {
                        this.housings = rawHousings.map(h => ({
                            id: Number(h.id),
                            name: h.name,
                            mosque_id: Number(h.mosque_id),
                            created_at: h.created_at,
                            updated_at: h.updated_at
                        }));
                    } else {
                        this.housings = [];
                    }
                }
            };
        }
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
      x-data="memberFormData()"
      x-init="init()">
    @include('components.nav')
    
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">إضافة منسوب جديد</h1>
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

        <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الاسم الكامل *</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        placeholder="مثال: أحمد محمد علي"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">المسجد *</label>
                    <div class="flex gap-2">
                        <select 
                            name="mosque_id" 
                            required 
                            x-model="selectedMosque"
                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">اختر المسجد</option>
                            @foreach($mosques as $mosque)
                                <option value="{{ $mosque->id }}">{{ $mosque->name }} ({{ $mosque->type }})</option>
                            @endforeach
                        </select>
                        <button
                            type="button"
                            @click="showMosqueModal = true"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center gap-1"
                            title="إضافة مسجد جديد"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>إضافة</span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">السكن (اختياري)</label>
                    <select 
                        name="housing_id"
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
                    <div class="flex gap-2">
                        <select 
                            name="category_id" 
                            required 
                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">اختر الفئة</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <button
                            type="button"
                            @click="showCategoryModal = true"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center gap-1"
                            title="إضافة فئة جديدة"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>إضافة</span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">المهنة *</label>
                    <div class="flex gap-2">
                        <select 
                            name="profession_id" 
                            required 
                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">اختر المهنة</option>
                            @foreach($professions as $profession)
                                <option value="{{ $profession->id }}" {{ old('profession_id') == $profession->id ? 'selected' : '' }}>
                                    {{ $profession->name }}
                                </option>
                            @endforeach
                        </select>
                        <button
                            type="button"
                            @click="showProfessionModal = true"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors flex items-center gap-1"
                            title="إضافة مهنة جديدة"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>إضافة</span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الرقم الوظيفي *</label>
                    <input
                        type="text"
                        name="employee_number"
                        value="{{ old('employee_number') }}"
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
                        value="{{ old('phone') }}"
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
                        value="{{ old('national_id') }}"
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
                        value="{{ old('appointment_decision') }}"
                        placeholder="قرار رقم 123/2024"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ التعيين *</label>
                    <input
                        type="date"
                        name="appointment_date"
                        value="{{ old('appointment_date') }}"
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
                        <option value="">اختر الحالة</option>
                        <option value="نشط" {{ old('status') == 'نشط' ? 'selected' : '' }}>نشط</option>
                        <option value="غير نشط" {{ old('status') == 'غير نشط' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
            </div>

            <!-- قسم الوثائق -->
            <div class="border-t border-slate-200 pt-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800">الوثائق (اختياري)</h2>
                        <p class="text-sm text-slate-600 mt-1">يمكنك رفع الوثائق المرتبطة بالمنسوب الآن</p>
                    </div>
                    <button
                        type="button"
                        @click="addDocument()"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إضافة وثيقة
                    </button>
                </div>

                <template x-if="documents.length === 0">
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-8 text-center">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-slate-500">لا توجد وثائق مرفوعة</p>
                        <p class="text-sm text-slate-400 mt-1">يمكنك إضافة وثائق الآن أو لاحقاً من صفحة تفاصيل المنسوب</p>
                    </div>
                </template>

                <div class="space-y-4">
                    <template x-for="(doc, index) in documents" :key="doc.id">
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="text-lg font-semibold text-slate-800">وثيقة <span x-text="index + 1"></span></h3>
                                <button
                                    type="button"
                                    @click="removeDocument(index)"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                    title="حذف الوثيقة"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">نوع الوثيقة *</label>
                                    <select
                                        :name="`documents[${index}][document_type]`"
                                        x-model="doc.document_type"
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                        <option value="">اختر نوع الوثيقة</option>
                                        <template x-for="type in documentTypes" :key="type.value">
                                            <option :value="type.value" x-text="type.label"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">اسم الوثيقة</label>
                                    <input
                                        type="text"
                                        :name="`documents[${index}][document_name]`"
                                        x-model="doc.document_name"
                                        placeholder="اسم الوثيقة (اختياري)"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ الرفع *</label>
                                    <input
                                        type="date"
                                        :name="`documents[${index}][upload_date]`"
                                        x-model="doc.upload_date"
                                        required
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">رفع الملف *</label>
                                    <div class="flex items-center gap-2">
                                        <label class="flex-1 cursor-pointer">
                                            <div class="flex items-center justify-center gap-2 px-4 py-2 border-2 border-dashed border-slate-300 rounded-lg hover:border-blue-500 transition-colors">
                                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <span class="text-slate-600 text-sm" x-text="doc.file ? doc.file.name : 'اختر ملف'"></span>
                                            </div>
                                            <input
                                                type="file"
                                                :name="`documents[${index}][file]`"
                                                @change="handleFileChange(index, $event)"
                                                required
                                                class="hidden"
                                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                            />
                                        </label>
                                        <template x-if="doc.file">
                                            <span class="text-xs text-slate-500" x-text="formatFileSize(doc.file.size)"></span>
                                        </template>
                                    </div>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">ملاحظات</label>
                                    <textarea
                                        :name="`documents[${index}][notes]`"
                                        x-model="doc.notes"
                                        rows="2"
                                        placeholder="ملاحظات حول الوثيقة (اختياري)"
                                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ route('members.index') }}" 
                   class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    حفظ
                </button>
            </div>
        </form>
    </div>

    <!-- Modal لإضافة فئة جديدة -->
    <div 
        x-show="showCategoryModal"
        x-cloak
        class="fixed inset-0 flex items-center justify-center z-50"
        @click.self="showCategoryModal = false"
        x-transition
    >
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-slate-800">إضافة فئة جديدة</h3>
                <button @click="showCategoryModal = false" class="text-slate-500 hover:text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">اسم الفئة *</label>
                    <input
                        type="text"
                        x-model="newCategory.name"
                        placeholder="مثال: أ"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الوصف (اختياري)</label>
                    <textarea
                        x-model="newCategory.description"
                        rows="3"
                        placeholder="وصف الفئة..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button
                    @click="showCategoryModal = false"
                    class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                >
                    إلغاء
                </button>
                <button
                    @click="addCategory()"
                    :disabled="loading"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-50"
                >
                    <span x-show="!loading">إضافة</span>
                    <span x-show="loading">جاري الإضافة...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة مهنة جديدة -->
    <div 
        x-show="showProfessionModal"
        x-cloak
        class="fixed inset-0 flex items-center justify-center z-50"
        @click.self="showProfessionModal = false"
        x-transition
    >
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-slate-800">إضافة مهنة جديدة</h3>
                <button @click="showProfessionModal = false" class="text-slate-500 hover:text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">اسم المهنة *</label>
                    <input
                        type="text"
                        x-model="newProfession.name"
                        placeholder="مثال: إمام"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الوصف (اختياري)</label>
                    <textarea
                        x-model="newProfession.description"
                        rows="3"
                        placeholder="وصف المهنة..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    ></textarea>
                </div>
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button
                    @click="showProfessionModal = false"
                    class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                >
                    إلغاء
                </button>
                <button
                    @click="addProfession()"
                    :disabled="loading"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-50"
                >
                    <span x-show="!loading">إضافة</span>
                    <span x-show="loading">جاري الإضافة...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal لإضافة مسجد جديد -->
    <div 
        x-show="showMosqueModal"
        x-cloak
        class="fixed inset-0 flex items-center justify-center z-50"
        @click.self="showMosqueModal = false"
        x-transition
    >
        <div class="bg-white rounded-xl shadow-xl p-6 max-w-md w-full mx-4" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-slate-800">إضافة مسجد جديد</h3>
                <button @click="showMosqueModal = false" class="text-slate-500 hover:text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">اسم المسجد *</label>
                    <input
                        type="text"
                        x-model="newMosque.name"
                        placeholder="مثال: مسجد النور"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">نوع المسجد *</label>
                    <select
                        x-model="newMosque.type"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="مسجد">مسجد</option>
                        <option value="جامع">جامع</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الحي *</label>
                    <select
                        x-model="newMosque.neighborhood_id"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">اختر الحي</option>
                        <template x-for="neighborhood in neighborhoods" :key="neighborhood.id">
                            <option :value="neighborhood.id" x-text="neighborhood.name + ' - ' + (neighborhood.province ? neighborhood.province.name : '')"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 justify-end mt-6">
                <button
                    @click="showMosqueModal = false"
                    class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                >
                    إلغاء
                </button>
                <button
                    @click="addMosque()"
                    :disabled="loading"
                    class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-50"
                >
                    <span x-show="!loading">إضافة</span>
                    <span x-show="loading">جاري الإضافة...</span>
                </button>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>

