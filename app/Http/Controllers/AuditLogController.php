<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $auditLogs = AuditLog::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('action', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(20) // paginate 20 logs per page
            ->withQueryString(); // keeps search term in pagination links

        return view('audit.index', compact('auditLogs', 'search'));
    }
}
