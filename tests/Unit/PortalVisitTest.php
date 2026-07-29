<?php

namespace Tests\Unit;

use App\Models\PortalVisit;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PortalVisitTest extends TestCase
{
    public function test_for_page_scope_uses_existing_page_column(): void
    {
        if (! Schema::hasTable('portal_visits')) {
            Schema::create('portal_visits', function (Blueprint $table) {
                $table->id('visit_id');
                $table->string('page')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamp('visited_at')->useCurrent();
            });
        }

        DB::table('portal_visits')->truncate();

        PortalVisit::create([
            'page' => 'map',
            'ip_address' => '127.0.0.1',
            'visited_at' => now(),
        ]);

        PortalVisit::create([
            'page' => 'analytics',
            'ip_address' => '127.0.0.1',
            'visited_at' => now(),
        ]);

        $this->assertSame(1, PortalVisit::query()->forPage('map')->count());
        $this->assertSame(1, PortalVisit::query()->forPage('analytics')->count());
    }

    public function test_for_page_scope_supports_page_type_column(): void
    {
        Schema::dropIfExists('portal_visits');

        Schema::create('portal_visits', function (Blueprint $table) {
            $table->id('visit_id');
            $table->string('page_type')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('visited_at')->useCurrent();
        });

        PortalVisit::create([
            'page_type' => 'map',
            'ip_address' => '127.0.0.1',
            'visited_at' => now(),
        ]);

        PortalVisit::create([
            'page_type' => 'analytics',
            'ip_address' => '127.0.0.1',
            'visited_at' => now(),
        ]);

        $this->assertSame(1, PortalVisit::query()->forPage('map')->count());
        $this->assertSame(1, PortalVisit::query()->forPage('analytics')->count());
    }
}
