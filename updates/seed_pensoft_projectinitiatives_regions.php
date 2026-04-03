<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Region;

class SeedPensoftProjectinitiativesRegions extends Seeder
{
    public function run()
    {
        $regions = [
            'Northern Europe',
            'Eastern Europe',
            'Southern Europe',
            'Western Europe',
        ];

        foreach ($regions as $index => $title) {
            Region::firstOrCreate(
                ['title' => $title],
                ['sort_order' => $index + 1]
            );
        }
    }
}
