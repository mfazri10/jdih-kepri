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

        // Document Types
        'document-types.view',
        'document-types.create',
        'document-types.update',
        'document-types.delete',

        // Categories
        'categories.view',
        'categories.create',
        'categories.update',
        'categories.delete',

        // Themes
        'themes.view',
        'themes.create',
        'themes.update',
        'themes.delete',

        // Documents
        'documents.view',
        'documents.create',
        'documents.update',
        'documents.delete',

        // Tags
        'tags.view',
        'tags.create',
        'tags.update',
        'tags.delete',

        // Attachments
        'attachments.view',
        'attachments.create',
        'attachments.update',
        'attachments.delete',

        // Consultations
        'consultations.view',
        'consultations.answer',
        'consultations.delete',

        // Hearings
        'hearings.view',
        'hearings.create',
        'hearings.update',
        'hearings.delete',

        // Information Requests
        'information-requests.view',
        'information-requests.respond',
        'information-requests.delete',

        // Subscriptions
        'subscriptions.view',
        'subscriptions.delete',

        // Feedbacks
        'feedbacks.view',
        'feedbacks.reply',
        'feedbacks.delete',
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
            'documents.view',
            'documents.create',
            'documents.update',
            'documents.delete',
            'document-types.view',
            'document-types.create',
            'document-types.update',
            'document-types.delete',
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'themes.view',
            'themes.create',
            'themes.update',
            'themes.delete',
            'tags.view',
            'tags.create',
            'tags.update',
            'tags.delete',
            'attachments.view',
            'attachments.create',
            'attachments.update',
            'attachments.delete',
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
            'documents.view',
            'documents.create',
            'documents.update',
            'document-types.view',
            'categories.view',
            'themes.view',
            'tags.view',
            'attachments.view',
            'attachments.create',
        ],
        
        'pegawai' => [
            'dashboard.view',
            'documents.view',
            'document-types.view',
            'categories.view',
            'themes.view',
            'tags.view',
        ],
    ],
];
