<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Type;

class SeedPensoftProjectinitiativesTypes extends Seeder
{
    public function run()
    {
        $types = [
            'Arable land',
            'Grassland',
            'Permanent crops',
            'Mixed crops',
        ];

        foreach ($types as $index => $title) {
            Type::firstOrCreate(
                ['title' => $title],
                ['sort_order' => $index + 1]
            );
        }
    }
}