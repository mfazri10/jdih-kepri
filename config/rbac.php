<?php

return [
    'guard_name' => env('RBAC_GUARD', env('AUTH_GUARD', 'web')),

    'permissions' => [
        // Dashboard
        'dashboard.view',
        
        // User Management
        'users.view',
        'users.create',
        'users.update',
        'users.delete',
        
        // Role Management
        'roles.view',
        'roles.create',
        'roles.update',
        'roles.delete',
        
        // Permission Management
        'permissions.view',
        'permissions.create',
        'permissions.update',
        'permissions.delete',
        
        // Menu Management
        'menus.view',
        'menus.create',
        'menus.update',
        'menus.delete',
        
        // Login Logs (Audit Log Login)
        'login-logs.view',
        
        // Settings
        'settings.view',
        'settings.update',
    ],

    'roles' => [
        'super-admin' => ['*'],
        
        'admin-opd' => [
            'dashboard.view',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'menus.view',
            'login-logs.view',
            'settings.view',
            'settings.update',
        ],
        
        'bendahara' => [
            'dashboard.view',
        ],
        
        'ppk-pimpinan' => [
            'dashboard.view',
        ],
        
        'operator' => [
            'dashboard.view',
            'users.view',
        ],
        
        'pegawai' => [
            'dashboard.view',
        ],
    ],
];
