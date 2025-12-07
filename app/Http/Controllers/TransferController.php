<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Mosque;
use App\Models\TransferHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{
    public function transferForm(Member $member)
    {
        $mosques = Mosque::all();
        return view('members.transfer', [
            'member' => $member,
            'mosques' => $mosques,
        ]);
    }

    public function transfer(Request $request, Member $member)
    {
        $validated = $request->validate([
            'to_mosque_id' => 'required|exists:mosques,id',
            'transfer_date' => 'required|date',
            'reason' => 'required|string|max:1000',
            'change_category' => 'nullable|boolean',
            'new_category' => 'required_if:change_category,1|in:أ,ب,ج',
        ]);

        $member->load('category');
        $fromMosque = $member->mosque;
        $toMosque = Mosque::findOrFail($validated['to_mosque_id']);

        // حفظ الفئة القديمة
        $oldCategory = $member->category->name ?? null;
        $newCategory = $validated['change_category'] ? $validated['new_category'] : $oldCategory;

        // البحث عن ID الفئة الجديدة إذا تم تغييرها
        $newCategoryId = null;
        if ($validated['change_category']) {
            $newCategoryModel = \App\Models\Category::where('name', $validated['new_category'])->first();
            $newCategoryId = $newCategoryModel?->id;
        }

        // تحديث بيانات المنسوب
        $updateData = [
            'mosque_id' => $validated['to_mosque_id'],
        ];
        if ($newCategoryId) {
            $updateData['category_id'] = $newCategoryId;
        }
        $member->update($updateData);

        // إنشاء سجل التحويل
        TransferHistory::create([
            'member_id' => $member->id,
            'from_mosque' => $fromMosque->name ?? 'غير محدد',
            'to_mosque' => $toMosque->name,
            'transfer_date' => $validated['transfer_date'],
            'transferred_by' => Auth::check() ? Auth::user()->name : 'نظام',
            'reason' => $validated['reason'],
            'old_category' => $oldCategory,
            'new_category' => $newCategory,
        ]);

        $message = "تم نقل المنسوب من {$fromMosque->name} إلى {$toMosque->name} بنجاح";
        if ($validated['change_category']) {
            $message .= " وتغيير الفئة من {$oldCategory} إلى {$newCategory}";
        }

        return redirect()->route('members.show', $member)
            ->with('success', $message);
    }

    public function changeCategoryForm(Member $member)
    {
        return view('members.change-category', [
            'member' => $member,
        ]);
    }

    public function changeCategory(Request $request, Member $member)
    {
        $validated = $request->validate([
            'new_category' => 'required|in:أ,ب,ج',
            'reason' => 'required|string|max:1000',
        ]);

        $member->load('category');
        $oldCategory = $member->category->name ?? null;
        $newCategory = $validated['new_category'];

        // البحث عن ID الفئة الجديدة
        $newCategoryModel = \App\Models\Category::where('name', $validated['new_category'])->first();
        if (!$newCategoryModel) {
            return redirect()->back()->withErrors(['new_category' => 'الفئة المحددة غير موجودة']);
        }

        // تحديث الفئة
        $member->update([
            'category_id' => $newCategoryModel->id,
        ]);

        // إنشاء سجل التحويل (تغيير فئة فقط)
        TransferHistory::create([
            'member_id' => $member->id,
            'from_mosque' => $member->mosque->name ?? 'غير محدد',
            'to_mosque' => $member->mosque->name ?? 'غير محدد',
            'transfer_date' => now(),
            'transferred_by' => Auth::check() ? Auth::user()->name : 'نظام',
            'reason' => $validated['reason'],
            'old_category' => $oldCategory,
            'new_category' => $newCategory,
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', "تم تغيير الفئة من {$oldCategory} إلى {$newCategory} بنجاح");
    }

    public function history(Member $member)
    {
        $member->load('category', 'mosque');
        
        $transferHistories = TransferHistory::where('member_id', $member->id)
            ->orderBy('transfer_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('members.transfer-history', [
            'member' => $member,
            'transferHistories' => $transferHistories,
        ]);
    }
}
