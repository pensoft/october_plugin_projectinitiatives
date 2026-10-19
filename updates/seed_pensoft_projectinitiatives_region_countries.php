<?php namespace Pensoft\Projectinitiatives\Updates;

use October\Rain\Database\Updates\Seeder;
use Pensoft\Projectinitiatives\Models\Region;
use RainLab\Location\Models\Country;

/**
 * Pre-fills the European regions with their countries, following the UN geoscheme (M49).
 * Regions are matched by a keyword in their title, and a region that already has countries
 * assigned is left untouched, so manual changes in the backend are never overwritten.
 */
class SeedPensoftProjectinitiativesRegionCountries extends Seeder
{
    public function run()
    {
        $regions = [
            'north' => [
                'AX', 'DK', 'EE', 'FO', 'FI', 'GG', 'IS', 'IE', 'IM', 'JE',
                'LV', 'LT', 'NO', 'SJ', 'SE', 'GB',
            ],
            'east' => [
                'BY', 'BG', 'CZ', 'HU', 'MD', 'PL', 'RO', 'RU', 'SK', 'UA',
            ],
            'south' => [
                'AL', 'AD', 'BA', 'HR', 'GI', 'GR', 'VA', 'IT', 'XK', 'MT',
                'ME', 'MK', 'PT', 'SM', 'RS', 'SI', 'ES',
            ],
            'west' => [
                'AT', 'BE', 'FR', 'DE', 'LI', 'LU', 'MC', 'NL', 'CH',
            ],
        ];

        foreach ($regions as $keyword => $codes) {
            $region = Region::where('title', 'ilike', "%{$keyword}%")->first();
            if (!$region || $region->countries()->count()) {
                continue;
            }

            $countryIds = Country::whereIn('code', $codes)->pluck('id')->all();
            if (!empty($countryIds)) {
                $region->countries()->sync($countryIds);
            }
        }
    }
}
