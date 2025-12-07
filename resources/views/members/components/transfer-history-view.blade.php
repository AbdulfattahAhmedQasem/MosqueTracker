<div x-data="{
    get memberTransfers() {
        if (!entities.transferHistory || !selectedMember) return [];
        return entities.transferHistory.filter(t => t.memberId === selectedMember.id);
    }
}">
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-sm text-blue-700 mb-1">المنسوب</p>
        <p class="font-bold text-blue-900" x-text="selectedMember?.name"></p>
    </div>

    <div class="space-y-3">
        <template x-if="memberTransfers.length > 0">
            <template x-for="transfer in memberTransfers" :key="transfer.id">
                <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-slate-800">
                                <span x-text="transfer.fromMosque"></span> ← <span x-text="transfer.toMosque"></span>
                            </p>
                            <p class="text-sm text-slate-600 mt-1" x-text="transfer.reason"></p>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded" x-text="transfer.transferDate"></span>
                    </div>
                    <div class="flex gap-4 text-xs text-slate-500 mt-2">
                        <span>تم بواسطة: <span x-text="transfer.transferredBy"></span></span>
                        <template x-if="transfer.oldCategory !== transfer.newCategory">
                            <span>الفئة: <span x-text="transfer.oldCategory"></span> ← <span x-text="transfer.newCategory"></span></span>
                        </template>
                    </div>
                </div>
            </template>
        </template>

        <template x-if="memberTransfers.length === 0">
            <p class="text-center text-slate-500 py-8">لا توجد تحويلات سابقة</p>
        </template>
    </div>
</div>

