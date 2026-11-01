<?php

namespace App\Http\Controllers;

abstract class Controller
{
protected function logActivity(
    string $action,
    string $module,
    string $description
): void {
    \App\Models\ActivityLog::create([
        'user_id' => auth()->id(),
        'action' => $action,
        'module' => $module,
        'description' => $description,
        'ip_address' => request()->ip(),
    ]);
}

}
