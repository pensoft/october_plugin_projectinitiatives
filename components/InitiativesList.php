<?php namespace Pensoft\Projectinitiatives\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Projectinitiatives\Models\Data;
use Pensoft\Projectinitiatives\Models\Type;
use Pensoft\Projectinitiatives\Models\Approach;
use Pensoft\Projectinitiatives\Models\Landscape;
use Pensoft\Projectinitiatives\Models\Region;
use Pensoft\Projectinitiatives\Models\Funding;
use RainLab\Location\Models\Country;

class InitiativesList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Initiatives List',
            'description' => 'Displays a filterable list of project initiatives'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $slug = $this->param('slug');

        if ($slug) {
            $this->page['initiative'] = Data::where('slug', $slug)->active()->first();
            $this->page['is_detail_page'] = (bool)$this->page['initiative'];
        } else {
            $this->page['is_detail_page'] = false;
        }

        $this->page['records'] = Data::active()->orderBy('sort_order')->get();
        $this->page['totalCount'] = $this->page['records']->count();
        $this->page['available_countries'] = $this->getAvailableCountries();
        $this->page['regions'] = Region::orderBy('sort_order')->get();
        $this->page['types'] = Type::orderBy('sort_order')->get();
        $this->page['approaches'] = Approach::orderBy('sort_order')->get();
        $this->page['landscapes'] = Landscape::orderBy('sort_order')->get();
        $this->page['fundings'] = Funding::orderBy('sort_order')->get();
    }

    protected function getAvailableCountries()
    {
        $countryIds = \Db::table('pensoft_projectinitiatives_data_country')->pluck('country_id')->unique();
        return Country::whereIn('id', $countryIds)->where('is_enabled', true)->orderBy('name')->get();
//        return Country::where('is_enabled', true)->orderBy('name')->get();
    }

    public function onSearchRecords()
    {
        $searchTerms = post('searchTerms');
        $sortCountry = post('sortCountry');
        $sortRegion = post('sortRegion');
        $sortType = post('sortType');
        $sortApproach = post('sortApproach');
        $sortLandscape = post('sortLandscape');
        $sortFunding = post('sortFunding');
        $page = post('page', 1);

        $this->page['records'] = $this->searchRecords(
            $searchTerms, $sortCountry, $sortRegion, $sortType,
            $sortApproach, $sortLandscape, $sortFunding
        );
        $this->page['totalCount'] = $this->page['records']->count();
        $this->page['available_countries'] = $this->getAvailableCountries();

        return ['#recordsContainer' => $this->renderPartial('initiatives_records')];
    }

    protected function searchRecords(
        $searchTerms = '',
        $sortCountry = 0,
        $sortRegion = 0,
        $sortType = 0,
        $sortApproach = 0,
        $sortLandscape = 0,
        $sortFunding = 0
    ) {
        $query = Data::active()->orderBy('sort_order');

        // Text search
        $searchTerms = is_string($searchTerms) ? json_decode($searchTerms, true) : (array)$searchTerms;
        $searchTerms = array_filter($searchTerms);
        if (!empty($searchTerms)) {
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->orWhere('title', 'ilike', "%{$term}%")
                      ->orWhere('description', 'ilike', "%{$term}%")
                      ->orWhere('institution', 'ilike', "%{$term}%");
                }
            });
        }

        if ($sortCountry) {
            $query->whereHas('countries', function ($q) use ($sortCountry) {
                $q->where('rainlab_location_countries.id', $sortCountry);
            });
        }

        if ($sortRegion) {
            $query->whereHas('regions', function ($q) use ($sortRegion) {
                $q->where('pensoft_projectinitiatives_regions.id', $sortRegion);
            });
        }

        if ($sortType) {
            $query->whereHas('types', function ($q) use ($sortType) {
                $q->where('pensoft_projectinitiatives_types.id', $sortType);
            });
        }

        if ($sortApproach) {
            $query->whereHas('approaches', function ($q) use ($sortApproach) {
                $q->where('pensoft_projectinitiatives_approaches.id', $sortApproach);
            });
        }

        if ($sortLandscape) {
            $query->whereHas('landscapes', function ($q) use ($sortLandscape) {
                $q->where('pensoft_projectinitiatives_landscapes.id', $sortLandscape);
            });
        }

        if ($sortFunding) {
            $query->whereHas('fundings', function ($q) use ($sortFunding) {
                $q->where('pensoft_projectinitiatives_fundings.id', $sortFunding);
            });
        }

        return $query->get();
    }
}
