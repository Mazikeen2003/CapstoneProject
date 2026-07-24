<?php

namespace App\Console\Commands;

use App\Models\Barangay;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackfillBarangayBoundaries extends Command
{
    protected $signature = 'barangays:backfill-boundaries
        {--path=data/cabuyao-barangays.geojson : Path relative to public/}';

    protected $description = 'Backfill boundary_geojson polygons on barangays from the static Cabuyao boundary asset';

    public function handle(): int
    {
        $path = public_path($this->option('path'));

        if (! File::exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }

        $geojson = json_decode(File::get($path), true);

        if (! isset($geojson['features'])) {
            $this->error('Invalid GeoJSON: no features array found.');
            return self::FAILURE;
        }

        $matched = 0;
        $unmatched = [];

        foreach ($geojson['features'] as $feature) {
            $name = $feature['properties']['name'] ?? null;

            if (! $name) {
                continue;
            }

            $barangay = Barangay::where('barangay_name', $name)->first();

            if (! $barangay) {
                $unmatched[] = $name;
                continue;
            }

            $barangay->update([
                'boundary_geojson' => $feature['geometry'],
            ]);

            $matched++;
        }

        $this->info("Updated {$matched} barangay boundary/boundaries.");

        if ($unmatched) {
            $this->warn('No matching Barangay row found for: ' . implode(', ', $unmatched));
        }

        $stillMissing = Barangay::whereNull('boundary_geojson')->pluck('barangay_name');
        if ($stillMissing->isNotEmpty()) {
            $this->warn('Barangays still missing boundaries: ' . $stillMissing->implode(', '));
        }

        return self::SUCCESS;
    }
}