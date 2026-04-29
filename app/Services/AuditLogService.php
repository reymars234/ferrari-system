<?php
namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    public static function log(
        string $action,
        string $module,
        string $description = '',
        array  $oldValues = [],
        array  $newValues = []
    ): void {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'user_agent'  => Request::userAgent(),
            'old_values'  => $oldValues ?: null,
            'new_values'  => $newValues ?: null,
        ]);
    }
}