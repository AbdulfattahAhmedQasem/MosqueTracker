<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفاصيل المنسوب - {{ $member->name }}</title>
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
    
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">تفاصيل المنسوب</h1>
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

        <!-- أزرار الإجراءات -->
        <div class="mb-6 flex gap-3 flex-wrap">
            <a href="{{ route('members.export.single', $member) }}"
               class="bg-indigo-500 text-white px-6 py-2 rounded-lg hover:bg-indigo-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                تصدير إلى Excel
            </a>
            <a href="{{ route('members.transfer', $member) }}"
               class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                نقل المنسوب
            </a>
            <a href="{{ route('members.change-category', $member) }}"
               class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تغيير الفئة
            </a>
            <a href="{{ route('members.transfer-history', $member) }}"
               class="bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                سجل التحويلات
            </a>
            <a href="{{ route('members.edit', $member) }}"
               class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل البيانات
            </a>
        </div>

        <!-- معلومات المنسوب -->
        <div class="bg-slate-50 rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">المعلومات الأساسية</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-600 mb-1">الاسم الكامل</p>
                    <p class="font-semibold text-slate-800">{{ $member->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">المسجد</p>
                    <p class="font-semibold text-slate-800">{{ $member->mosque->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">المحافظة</p>
                    <p class="font-semibold text-slate-800">{{ $member->mosque->neighborhood->province->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">الحي</p>
                    <p class="font-semibold text-slate-800">{{ $member->mosque->neighborhood->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">الرقم الوظيفي</p>
                    <p class="font-semibold text-slate-800">{{ $member->employee_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">الفئة</p>
                    <p class="font-semibold text-slate-800">{{ $member->category->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">المهنة</p>
                    <p class="font-semibold text-slate-800">{{ $member->profession->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">رقم الهاتف</p>
                    <p class="font-semibold text-slate-800">{{ $member->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">رقم الهوية</p>
                    <p class="font-semibold text-slate-800">{{ $member->national_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">قرار التعيين</p>
                    <p class="font-semibold text-slate-800">{{ $member->appointment_decision ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">تاريخ التعيين</p>
                    <p class="font-semibold text-slate-800">{{ $member->appointment_date?->format('Y-m-d') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">حالة المنسوب</p>
                    <p class="font-semibold text-slate-800">{{ $member->status }}</p>
                </div>
                @if($member->housing)
                <div>
                    <p class="text-sm text-slate-600 mb-1">السكن</p>
                    <p class="font-semibold text-slate-800">{{ $member->housing->name }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- قسم الوثائق -->
        @include('members.components.documents-view', ['member' => $member, 'documents' => $member->documents])
    </div>

    <script>
        function documentsManager() {
            return {
                showUploadForm: false,
                documents: [],
                member: null,
                init(docs, mem) {
                    this.documents = docs || [];
                    this.member = mem || null;
                },
                get filteredDocuments() {
                    try {
                        if (!this.documents || !this.member) return [];
                        
                        let filtered = this.documents.filter(doc => doc.member_id == this.member.id);
                        
                        return filtered.sort((a, b) => {
                            const dateA = new Date(a.upload_date);
                            const dateB = new Date(b.upload_date);
                            return dateB - dateA;
                        });
                    } catch (e) {
                        return [];
                    }
                },
                handleDocumentDownload(documentId) {
                    window.location.href = '{{ route('documents.download', ':id') }}'.replace(':id', documentId);
                },
                async handleDocumentDelete(documentId) {
                    if (!confirm('هل أنت متأكد من حذف هذه الوثيقة؟')) return;
                    
                    try {
                        const response = await fetch('{{ route('documents.destroy', ':id') }}'.replace(':id', documentId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            }
                        });
                        
                        if (response.ok) {
                            this.documents = this.documents.filter(doc => doc.id != documentId);
                            window.location.reload();
                        } else {
                            alert('حدث خطأ أثناء حذف الوثيقة');
                        }
                    } catch (error) {
                        alert('حدث خطأ أثناء حذف الوثيقة');
                    }
                }
            }
        }
    </script>
</body>
</html>

