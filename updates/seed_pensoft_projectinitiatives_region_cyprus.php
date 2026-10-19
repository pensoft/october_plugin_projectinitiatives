<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Region;
use RainLab\Location\Models\Country;

/**
 * Adds Cyprus to Southern Europe (the UN geoscheme places it in Western Asia).
 * Kept separate from the region pre-fill so it also applies where that has already run.
 */
class SeedPensoftProjectinitiativesRegionCyprus extends Seeder
{
    public function run()
    {
        $region = Region::where('title', 'ilike', '%south%')->first();
        $cyprus = Country::where('code', 'CY')->first();

        if ($region && $cyprus) {
            $region->countries()->syncWithoutDetaching([$cyprus->id]);
        }
    }
}
