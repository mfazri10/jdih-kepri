<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use App\Models\Document;
use App\Models\Category;
use App\Models\DocumentType;
use App\Models\Consultation;
use App\Models\Feedback;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dashboard.view'),
        ];
    }

    public function index(): View
    {
        return view('admin.dashboard.index', [
            'stats' => [
                'roles' => Role::count(),
                'permissions' => Permission::count(),
                'users' => User::count(),
                'menus' => Menu::active()->count(),
                // JDIH Stats
                'documents'      => Document::count(),
                'documents_active' => Document::where('status', 'berlaku')->count(),
                'categories'     => Category::active()->count(),
                'types'          => DocumentType::active()->count(),
                'consultations_pending' => Consultation::pending()->count(),
                'feedbacks_new'  => Feedback::new()->count(),
                'total_views'    => Document::sum('views_count'),
                'total_downloads'=> Document::sum('downloads_count'),
            ],
            'roles' => Role::query()
                ->withCount('permissions')
                ->orderBy('name')
                ->get(),
            'recentUsers' => User::query()
                ->with('roles')
                ->latest()
                ->limit(5)
                ->get(),
            'menus' => Menu::query()
                ->ordered()
                ->limit(8)
                ->get(),
            'recentDocuments' => Document::with(['type', 'category'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
