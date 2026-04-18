<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class AuditController extends Controller
{
    public function index()
    {
        $audits = Activity::with(['causer', 'subject'])->latest()->limit(20)->get();
        return response()->json([
            'data' => $audits
        ]);
    }
}
