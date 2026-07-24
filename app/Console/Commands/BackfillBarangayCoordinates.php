<?php

namespace App\Console\Commands;

use App\Models\Barangay;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackfillBarangayCoordinates extends Command
{
    protected $signature = 'barangays:backfill-coordinates
        {--path=data/cabuyao-map.geojson : Path relative to public/}';

    protected $description = 'Backfill latitude/longitude on barangays from the static Cabuyao GeoJSON asset';

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

        $barangayFeatures = collect($geojson['features'])
            ->filter(fn ($f) => ($f['properties']['kind'] ?? null) === 'barangay');

        $this->info("Found {$barangayFeatures->count()} barangay features in the asset.");

        $matched = 0;
        $unmatched = [];

        foreach ($barangayFeatures as $feature) {
            $name = $feature['properties']['name'];
            [$lng, $lat] = $feature['geometry']['coordinates'];

            $barangay = Barangay::where('barangay_name', $name)->first();

            if (! $barangay) {
                $unmatched[] = $name;
                continue;
            }

            $barangay->update([
                'latitude'  => $lat,
                'longitude' => $lng,
            ]);

            $matched++;
        }

        $this->info("Updated {$matched} barangay(s).");

        if ($unmatched) {
            $this->warn('No matching Barangay row found for: ' . implode(', ', $unmatched));
        }

        $stillMissing = Barangay::whereNull('latitude')->pluck('barangay_name');
        if ($stillMissing->isNotEmpty()) {
            $this->warn('Barangays still missing coordinates (not in asset): ' . $stillMissing->implode(', '));
        }

        return self::SUCCESS;
    }
}