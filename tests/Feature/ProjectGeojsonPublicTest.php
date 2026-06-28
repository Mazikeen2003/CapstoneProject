<?php

namespace Tests\Feature;

use Tests\TestCase;

class ProjectGeojsonPublicTest extends TestCase
{
    public function test_public_users_can_fetch_project_geojson(): void
    {
        $response = $this->getJson('/api/projects/geojson');

        $response->assertOk()
            ->assertJsonStructure([
                'type',
                'features',
            ]);
    }
}
