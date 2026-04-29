<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;

class AdminAuditController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with('user')->latest()->paginate(30);
        return view('admin.audit.index', compact('logs'));
    }
}