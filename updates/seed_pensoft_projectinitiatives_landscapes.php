<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Landscape;

class SeedPensoftProjectinitiativesLandscapes extends Seeder
{
    public function run()
    {
        $landscapes = [
            'Woody',
            'Wet',
            'Grassy',
            'Stony',
            'All landscape features',
        ];

        foreach ($landscapes as $index => $title) {
            Landscape::firstOrCreate(
                ['title' => $title],
                ['sort_order' => $index + 1]
            );
        }
    }
}
