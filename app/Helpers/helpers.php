<?php

if (!function_exists('hasModuleAccess')) {
    function hasModuleAccess($moduleName)
    {
        $user = auth()->user();
        if (!$user) return false;

        // Super admin punya akses semua
        if ($user->role === 'super_admin') return true;

        // Cek akses modul untuk role user
        $access = \App\Models\ModuleAccess::where('role', $user->role)
                    ->where('module_name', $moduleName)
                    ->first();

        return $access && $access->is_active;
    }
}

if (!function_exists('getUserRole')) {
    function getUserRole()
    {
        return auth()->user()->role ?? null;
    }
}

if (!function_exists('approvalLevelForRole')) {
    /**
     * Mapping role user ke level approval yang menjadi tugasnya.
     */
    function approvalLevelForRole($role)
    {
        $map = [
            'atasan_l1' => 'l1',
            'atasan_l2' => 'l2',
            'company_head' => 'company_head',
            'ceo' => 'ceo',
            'it_staff' => 'it_department',
            'tech_department' => 'tech_department',
            'head_tech' => 'head_tech',
            'direktur_am' => 'direktur_am',
            'admin_am' => 'admin_am',
            'manager_am' => 'manager_am',
            'admin_ga' => 'admin_ga',
            'manager_ga' => 'manager_ga',
        ];

        return $map[$role] ?? null;
    }
}

if (!function_exists('approverRoleForLevel')) {
    /**
     * Mapping level approval ke role user yang berhak menyetujui.
     */
    function approverRoleForLevel($level)
    {
        $map = [
            'l1' => 'atasan_l1',
            'l2' => 'atasan_l2',
            'company_head' => 'company_head',
            'ceo' => 'ceo',
            'it_department' => 'it_staff',
            'tech_department' => 'tech_department',
            'head_tech' => 'head_tech',
            'direktur_am' => 'direktur_am',
            'admin_am' => 'admin_am',
            'manager_am' => 'manager_am',
            'admin_ga' => 'admin_ga',
            'manager_ga' => 'manager_ga',
        ];

        return $map[$level] ?? null;
    }
}
