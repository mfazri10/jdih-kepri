<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class LoginLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:login-logs.view', only: ['index']),
        ];
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $status = trim((string) $request->input('status'));

        $query = LoginLog::query()->with('user');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.login_logs.index', [
            'logs' => $logs,
            'search' => $search,
            'status' => $status,
        ]);
    }
}
