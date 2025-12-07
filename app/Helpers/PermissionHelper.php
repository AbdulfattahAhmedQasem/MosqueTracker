<?php

namespace App\Helpers;

class PermissionHelper
{
    /**
     * ترجمة اسم الصلاحية من الإنجليزية إلى العربية
     */
    public static function translate(string $permissionName): string
    {
        $translations = [
            // Members
            'view members' => 'عرض المنسوبين',
            'create members' => 'إضافة منسوبين',
            'edit members' => 'تعديل منسوبين',
            'delete members' => 'حذف منسوبين',
            
            // Mosques
            'view mosques' => 'عرض المساجد',
            'create mosques' => 'إضافة مساجد',
            'edit mosques' => 'تعديل مساجد',
            'delete mosques' => 'حذف مساجد',
            
            // Housings
            'view housings' => 'عرض السكن',
            'create housings' => 'إضافة سكن',
            'edit housings' => 'تعديل سكن',
            'delete housings' => 'حذف سكن',
            
            // Neighborhoods
            'view neighborhoods' => 'عرض الأحياء',
            'create neighborhoods' => 'إضافة أحياء',
            'edit neighborhoods' => 'تعديل أحياء',
            'delete neighborhoods' => 'حذف أحياء',
            
            // Provinces
            'view provinces' => 'عرض المحافظات',
            'create provinces' => 'إضافة محافظات',
            'edit provinces' => 'تعديل محافظات',
            'delete provinces' => 'حذف محافظات',
            
            // Categories
            'view categories' => 'عرض الفئات',
            'create categories' => 'إضافة فئات',
            'edit categories' => 'تعديل فئات',
            'delete categories' => 'حذف فئات',
            
            // Professions
            'view professions' => 'عرض المهن',
            'create professions' => 'إضافة مهن',
            'edit professions' => 'تعديل مهن',
            'delete professions' => 'حذف مهن',
        ];

        return $translations[$permissionName] ?? $permissionName;
    }
}
