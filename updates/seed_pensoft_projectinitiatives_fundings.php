<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Funding;

class SeedPensoftProjectinitiativesFundings extends Seeder
{
    public function run()
    {
        $fundings = [
            'Private',
            'Public',
            'Mixed funding',
        ];

        foreach ($fundings as $index => $title) {
            Funding::firstOrCreate(
                ['title' => $title],
                ['sort_order' => $index + 1]
            );
        }
    }
}
