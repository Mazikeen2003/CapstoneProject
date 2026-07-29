<?php

namespace App\Services;

use App\Models\PortalVisit;
use Illuminate\Support\Facades\Auth;

class PortalVisitService
{
    public static function logVisit(string $pageType): void
    {
        try {
            if (!Auth::guest()) {
                return;
            }

            PortalVisit::create([
                'page' => $pageType,
                'ip_address' => request()->ip(),
                'visited_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Non-critical analytics; failures should not break the public page.
        }
    }
}
