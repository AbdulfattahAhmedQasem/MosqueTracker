<?php

namespace App\Exports;

use App\Models\Member;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $members;

    public function __construct(Collection $members)
    {
        $this->members = $members;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->members;
    }

    public function headings(): array
    {
        return [
            'الاسم',
            'المسجد',
            'الحي',
            'المحافظة',
            'السكن',
            'الفئة',
            'المهنة',
            'الرقم الوظيفي',
            'رقم الهاتف',
            'رقم الهوية',
            'قرار التعيين',
            'تاريخ التعيين',
            'الحالة',
        ];
    }

    /**
     * @param  Member  $member
     */
    public function map($member): array
    {
        return [
            $member->name,
            $member->mosque->name ?? '-',
            $member->mosque->neighborhood->name ?? '-',
            $member->mosque->neighborhood->province->name ?? '-',
            $member->housing->name ?? '-',
            $member->category?->name ?? $member->category ?? '-',
            $member->profession?->name ?? $member->profession ?? '-',
            $member->employee_number,
            $member->phone,
            $member->national_id,
            $member->appointment_decision ?? '-',
            $member->appointment_date ? $member->appointment_date->format('Y-m-d') : '-',
            $member->status,
        ];
    }

    /**
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
