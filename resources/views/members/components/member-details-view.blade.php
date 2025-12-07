<div class="space-y-6" 
     x-data="{
         showDocuments: false,
         get documentsCount() {
             if (!documents || !selectedMember) return 0;
             return documents.filter(d => d.memberId === selectedMember.id).length;
         }
     }">
    <div class="grid grid-cols-2 gap-4">
        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">الاسم الكامل</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.name || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">المسجد</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.mosque?.name || selectedMember?.mosque || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">المحافظة</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.mosque?.neighborhood?.province?.name || selectedMember?.province || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">الحي السكني</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.mosque?.neighborhood?.name || selectedMember?.neighborhood || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">نوع العلاقة</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.relationshipType || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">الرقم الوظيفي</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.employee_number || selectedMember?.employeeNumber || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">الفئة</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.category || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">المهنة</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.profession || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">رقم الهاتف</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.phone || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">رقم الهوية</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.national_id || selectedMember?.nationalId || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">قرار التعيين</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.appointment_decision || selectedMember?.appointmentDecision || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">تاريخ التعيين</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.appointment_date || selectedMember?.appointmentDate || '-'"></p>
        </div>

        <div class="p-4 bg-slate-50 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">حالة المنسوب</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.status || '-'"></p>
        </div>

        <template x-if="selectedMember?.housing">
            <div class="p-4 bg-slate-50 rounded-lg">
                <p class="text-sm text-slate-600 mb-1">السكن</p>
                <p class="font-semibold text-slate-800" x-text="selectedMember?.housing?.name || selectedMember?.housing || '-'"></p>
            </div>
        </template>
    </div>

    <!-- قسم الوثائق -->
    <div class="border-t border-slate-200 pt-6">
        <button
            @click="showDocuments = !showDocuments"
            class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium mb-4"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>إدارة الوثائق (<span x-text="documentsCount"></span>)</span>
        </button>

        <template x-if="showDocuments">
            @include('members.components.documents-view')
        </template>
    </div>
</div>

