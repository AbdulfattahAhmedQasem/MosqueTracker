<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function store(Request $request, Member $member): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'document_name' => 'nullable|string|max:255',
            'document_type' => 'required|string|in:طي_القيد,تعيين_السكن,عقد_السكن,الغياب,أخرى',
            'upload_date' => 'required|date',
            'notes' => 'nullable|string',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::random(10) . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'private');

        $documentName = $validated['document_name'] ?? $this->getDocumentTypeLabel($validated['document_type']);

        Document::create([
            'member_id' => $member->id,
            'document_name' => $documentName,
            'document_type' => $validated['document_type'],
            'upload_date' => $validated['upload_date'],
            'notes' => $validated['notes'] ?? null,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'file_path' => $filePath,
        ]);

        return redirect()->route('members.show', $member)
            ->with('success', 'تم رفع الوثيقة بنجاح');
    }

    public function show(Document $document): \Symfony\Component\HttpFoundation\StreamedResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        $filePath = Storage::disk('private')->path($document->file_path);
        $mimeType = Storage::disk('private')->mimeType($document->file_path);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $document->file_name . '"',
        ]);
    }

    public function download(Document $document): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        if (!Storage::disk('private')->exists($document->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        return Storage::disk('private')->download($document->file_path, $document->file_name);
    }

    public function destroy(Document $document): \Illuminate\Http\RedirectResponse
    {
        $member = $document->member;

        // Delete the file
        if (Storage::disk('private')->exists($document->file_path)) {
            Storage::disk('private')->delete($document->file_path);
        }

        // Delete the document record
        $document->delete();

        return redirect()->route('members.show', $member)
            ->with('success', 'تم حذف الوثيقة بنجاح');
    }

    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $query = Document::with('member');

        // Filter by date
        if ($request->has('upload_date') && $request->upload_date) {
            $query->whereDate('upload_date', $request->upload_date);
        }

        // Filter by document type
        if ($request->has('document_type') && $request->document_type) {
            $query->where('document_type', $request->document_type);
        }

        // Filter by member
        if ($request->has('member_id') && $request->member_id) {
            $query->where('member_id', $request->member_id);
        }

        $documents = $query->orderBy('upload_date', 'desc')->paginate(20);
        $members = Member::all();

        return view('documents.index', [
            'documents' => $documents,
            'members' => $members,
        ]);
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
}
