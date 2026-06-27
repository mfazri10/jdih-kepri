<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $action = $request->input('action');

        $logs = AuditLog::with('user')
            ->when($action, fn($q) => $q->where('action', $action))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.audit-logs.index', compact('logs', 'action'));
    }
}
