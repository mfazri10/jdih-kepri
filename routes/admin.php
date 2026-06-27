<?php

require __DIR__.'/admin/dashboard.php';
require __DIR__.'/admin/roles.php';
require __DIR__.'/admin/permissions.php';
require __DIR__.'/admin/users.php';
require __DIR__.'/admin/menus.php';
require __DIR__.'/admin/login_logs.php';
require __DIR__.'/admin/document_types.php';
require __DIR__.'/admin/categories.php';
require __DIR__.'/admin/themes.php';
require __DIR__.'/admin/documents.php';
require __DIR__.'/admin/tags.php';
require __DIR__.'/admin/consultations.php';
require __DIR__.'/admin/hearings.php';
require __DIR__.'/admin/information-requests.php';
require __DIR__.'/admin/subscriptions.php';
require __DIR__.'/admin/feedbacks.php';
require __DIR__.'/admin/audit-logs.php';
// Optional & planned feature routes
foreach ([
    'categories.php',
    'tags.php',
    'posts.php',
    'settings.php',
    'media.php',
    'pages.php',
    'members.php',
    'factions.php',
    'committees.php',
    'target_areas.php',
    'announcements.php',
    'agendas.php',
    'guest_visits.php',
    'aspirations.php',
    'legal_documents.php',
    'legal_document_categories.php',
    'landingpage_menus.php',
    'sliders.php',
    'photos.php',
    'videos.php',
    'features.php',
] as $file) {
    $path = __DIR__.'/admin/'.$file;
    if (file_exists($path)) {
        require $path;
    }
}

