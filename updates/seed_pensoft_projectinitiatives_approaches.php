<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Approach;

class SeedPensoftProjectinitiativesApproaches extends Seeder
{
    public function run()
    {
        $approaches = [
            'Labels and certification',
            'Markets and sales',
            'Volunteer mobilization and citizen involvement',
            'Knowledge networks and Living Labs',
            'Research and pilot study',
            'Subsidies',
            'Policy support',
            'Governmental project',
            'NGO or civil society organization project',
        ];

        foreach ($approaches as $index => $title) {
            Approach::firstOrCreate(
                ['title' => $title],
                ['sort_order' => $index + 1]
            );
        }
    }
}