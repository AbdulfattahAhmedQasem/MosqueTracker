<?php

namespace App\Http\Controllers;

use App\Exports\MembersExport;
use App\Models\Category;
use App\Models\Document;
use App\Models\Housing;
use App\Models\Member;
use App\Models\Mosque;
use App\Models\Neighborhood;
use App\Models\Profession;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MemberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view members', only: ['index', 'show', 'export', 'exportAll', 'exportByMosque', 'exportSingle']),
            new Middleware('permission:create members', only: ['create', 'store']),
            new Middleware('permission:edit members', only: ['edit', 'update']),
            new Middleware('permission:delete members', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Member::with(['mosque.neighborhood.province', 'housing', 'category', 'profession']);

        // البحث بالاسم
        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        // الفلترة حسب المسجد
        if ($request->filled('mosque_id')) {
            $query->where('mosque_id', $request->mosque_id);
        }

        // الفلترة حسب الحي
        if ($request->filled('neighborhood_id')) {
            $query->whereHas('mosque', function ($q) use ($request) {
                $q->where('neighborhood_id', $request->neighborhood_id);
            });
        }

        // الفلترة حسب المحافظة
        if ($request->filled('province_id')) {
            $query->whereHas('mosque.neighborhood', function ($q) use ($request) {
                $q->where('province_id', $request->province_id);
            });
        }

        // الفلترة حسب الفئة
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // الفلترة حسب المهنة
        if ($request->filled('profession_id')) {
            $query->where('profession_id', $request->profession_id);
        }

        // الفلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // الفلترة حسب الفترة الزمنية
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $members = $query->orderBy('name')->get();

        // جلب البيانات للقوائم المنسدلة
        $mosques = Mosque::orderBy('name')->get();
        $neighborhoods = Neighborhood::with('province')->orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $professions = Profession::orderBy('name')->get();
        $statuses = ['نشط', 'غير نشط'];

        return view('members.index', [
            'members' => $members,
            'mosques' => $mosques,
            'neighborhoods' => $neighborhoods,
            'provinces' => $provinces,
            'categories' => $categories,
            'professions' => $professions,
            'statuses' => $statuses,
            'filters' => $request->only(['name', 'mosque_id', 'neighborhood_id', 'province_id', 'category_id', 'profession_id', 'status', 'date_from', 'date_to']),
        ]);
    }

    public function create()
    {
        $mosques = Mosque::all();
        $housings = Housing::all();
        $categories = Category::orderBy('name')->get();
        $professions = Profession::orderBy('name')->get();
        $neighborhoods = Neighborhood::with('province')->orderBy('name')->get();
        $provinces = Province::orderBy('name')->get();

        return view('members.create', [
            'mosques' => $mosques,
            'housings' => $housings,
            'categories' => $categories,
            'professions' => $professions,
            'neighborhoods' => $neighborhoods,
            'provinces' => $provinces,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mosque_id' => 'required|exists:mosques,id',
            'housing_id' => 'nullable|exists:housings,id',
            'category_id' => 'required|exists:categories,id',
            'profession_id' => 'required|exists:professions,id',
            'employee_number' => 'required|string|max:255|unique:members,employee_number',
            'phone' => 'required|string|max:255',
            'national_id' => 'required|string|size:10|regex:/^[0-9]{10}$/|unique:members,national_id',
            'appointment_decision' => 'nullable|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|string|max:255',
            'documents' => 'nullable|array',
            'documents.*.document_type' => 'required_with:documents|string|in:طي_القيد,تعيين_السكن,عقد_السكن,الغياب,أخرى',
            'documents.*.document_name' => 'nullable|string|max:255',
            'documents.*.upload_date' => 'required_with:documents|date',
            'documents.*.notes' => 'nullable|string',
            'documents.*.file' => 'required_with:documents|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ], [
            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.size' => 'رقم الهوية يجب أن يكون بالضبط 10 أرقام.',
            'national_id.regex' => 'رقم الهوية يجب أن يحتوي على أرقام فقط (10 أرقام بالضبط).',
            'national_id.unique' => 'رقم الهوية هذا مستخدم بالفعل. يرجى التحقق من الرقم.',
            'employee_number.unique' => 'الرقم الوظيفي هذا مستخدم بالفعل. يرجى التحقق من الرقم.',
        ]);

        // حفظ بيانات المنسوب أولاً (استخدام فقط الحقول المطلوبة)
        $memberData = collect($validated)->only([
            'name',
            'mosque_id',
            'housing_id',
            'category_id',
            'profession_id',
            'employee_number',
            'phone',
            'national_id',
            'appointment_decision',
            'appointment_date',
            'status',
        ])->toArray();
        
        $member = Member::create($memberData);

        // معالجة الوثائق إذا كانت موجودة
        if ($request->has('documents') && is_array($request->documents)) {
            foreach ($request->documents as $index => $documentData) {
                if ($request->hasFile("documents.{$index}.file")) {
                    $file = $request->file("documents.{$index}.file");
                    $fileName = time().'_'.Str::random(10).'_'.$file->getClientOriginalName();
                    $filePath = $file->storeAs('documents', $fileName, 'private');

                    $documentName = $documentData['document_name'] ?? $this->getDocumentTypeLabel($documentData['document_type']);

                    Document::create([
                        'member_id' => $member->id,
                        'document_name' => $documentName,
                        'document_type' => $documentData['document_type'],
                        'upload_date' => $documentData['upload_date'],
                        'notes' => $documentData['notes'] ?? null,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'file_type' => $file->getMimeType(),
                        'file_path' => $filePath,
                    ]);
                }
            }
        }

        $message = 'تم إضافة المنسوب بنجاح';
        if ($request->has('documents') && count($request->documents) > 0) {
            $message .= ' وتم رفع '.count($request->documents).' وثيقة';
        }

        return redirect()->route('members.show', $member)
            ->with('success', $message);
    }

    private function getDocumentTypeLabel(string $type): string
    {
        $labels = [
            'طي_القيد' => 'وثيقة طي القيد',
            'تعيين_السكن' => 'وثيقة تعيين السكن',
            'عقد_السكن' => 'عقد السكن',
            'الغياب' => 'وثيقة الغياب',
            'أخرى' => 'وثيقة أخرى',
        ];

        return $labels[$type] ?? 'وثيقة';
    }

    public function show(Member $member)
    {
        $member->load(['mosque.neighborhood.province', 'housing', 'category', 'profession', 'documents', 'transferHistories']);

        return view('members.show', ['member' => $member]);
    }

    public function edit(Member $member)
    {
        $mosques = Mosque::all();
        $housings = Housing::all();
        $categories = Category::orderBy('name')->get();
        $professions = Profession::orderBy('name')->get();

        return view('members.edit', [
            'member' => $member,
            'mosques' => $mosques,
            'housings' => $housings,
            'categories' => $categories,
            'professions' => $professions,
        ]);
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mosque_id' => 'required|exists:mosques,id',
            'housing_id' => 'nullable|exists:housings,id',
            'category_id' => 'required|exists:categories,id',
            'profession_id' => 'required|exists:professions,id',
            'employee_number' => 'required|string|max:255|unique:members,employee_number,'.$member->id,
            'phone' => 'required|string|max:255',
            'national_id' => 'required|string|size:10|regex:/^[0-9]{10}$/|unique:members,national_id,'.$member->id,
            'appointment_decision' => 'nullable|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|string|max:255',
        ], [
            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.size' => 'رقم الهوية يجب أن يكون بالضبط 10 أرقام.',
            'national_id.regex' => 'رقم الهوية يجب أن يحتوي على أرقام فقط (10 أرقام بالضبط).',
            'national_id.unique' => 'رقم الهوية هذا مستخدم بالفعل. يرجى التحقق من الرقم.',
            'employee_number.unique' => 'الرقم الوظيفي هذا مستخدم بالفعل. يرجى التحقق من الرقم.',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'تم تحديث بيانات المنسوب بنجاح');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')
            ->with('success', 'تم حذف المنسوب بنجاح');
    }

    public function export(Request $request)
    {
        $query = Member::with(['mosque.neighborhood.province', 'housing', 'category', 'profession']);

        // تطبيق نفس الفلاتر المستخدمة في index
        if ($request->filled('name')) {
            $query->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->filled('mosque_id')) {
            $query->where('mosque_id', $request->mosque_id);
        }

        if ($request->filled('neighborhood_id')) {
            $query->whereHas('mosque', function ($q) use ($request) {
                $q->where('neighborhood_id', $request->neighborhood_id);
            });
        }

        if ($request->filled('province_id')) {
            $query->whereHas('mosque.neighborhood', function ($q) use ($request) {
                $q->where('province_id', $request->province_id);
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('profession_id')) {
            $query->where('profession_id', $request->profession_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $members = $query->orderBy('name')->get();

        $fileName = 'منسوبين_'.now()->format('Y-m-d_His').'.xlsx';

        return Excel::download(new MembersExport($members), $fileName);
    }

    public function exportAll()
    {
        $members = Member::with(['mosque.neighborhood.province', 'housing', 'categoryModel', 'professionModel'])
            ->orderBy('name')
            ->get();

        $fileName = 'جميع_المنسوبين_'.now()->format('Y-m-d_His').'.xlsx';

        return Excel::download(new MembersExport($members), $fileName);
    }

    public function exportByMosque(Mosque $mosque)
    {
        $members = Member::with(['mosque.neighborhood.province', 'housing', 'categoryModel', 'professionModel'])
            ->where('mosque_id', $mosque->id)
            ->orderBy('name')
            ->get();

        $fileName = 'منسوبين_'.str_replace(' ', '_', $mosque->name).'_'.now()->format('Y-m-d_His').'.xlsx';

        return Excel::download(new MembersExport($members), $fileName);
    }

    public function exportSingle(Member $member)
    {
        $members = collect([$member->load(['mosque.neighborhood.province', 'housing', 'categoryModel', 'professionModel'])]);

        $fileName = 'منسوب_'.str_replace(' ', '_', $member->name).'_'.now()->format('Y-m-d_His').'.xlsx';

        return Excel::download(new MembersExport($members), $fileName);
    }
}
