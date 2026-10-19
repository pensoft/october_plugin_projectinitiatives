<?php namespace Pensoft\Projectinitiatives\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Projectinitiatives\Models\Data;
use Pensoft\Projectinitiatives\Models\Type;
use Pensoft\Projectinitiatives\Models\Approach;
use Pensoft\Projectinitiatives\Models\Landscape;
use Pensoft\Projectinitiatives\Models\Region;
use Pensoft\Projectinitiatives\Models\Funding;
use Pensoft\Projectinitiatives\Models\Report;
use RainLab\Location\Models\Country;
use ApplicationException;
use Backend;
use Mail;
use Multiwebinc\Recaptcha\Validators\RecaptchaValidator;
use Multiwebinc\Recaptcha\Models\Settings as RecaptchaSettings;

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
        return [
            'recipientEmail' => [
                'title' => 'Report Recipient Email(s)',
                'description' => 'Email addresses to notify when a visitor reports an issue on an initiative. Comma separated for multiple recipients.',
                'default' => 'kkaleva@pensoft.net',
                'type' => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $slug = $this->param('slug');

        if ($slug) {
            $this->page['initiative'] = Data::where('slug', $slug)->active()->first();
            $this->page['is_detail_page'] = (bool)$this->page['initiative'];
            $this->page['report_issue_types'] = Report::getIssueTypes();
            $this->page['recaptchaSiteKey'] = RecaptchaSettings::get('site_key');
        } else {
            $this->page['is_detail_page'] = false;
        }

        $this->page['records'] = Data::active()->orderBy('sort_order')->get();
        $this->page['totalCount'] = $this->page['records']->count();
        $this->page['available_countries'] = $this->getAvailableCountries();
        $this->page['regions'] = Region::with('countries')->orderBy('sort_order')->get();
        $this->page['region_countries'] = $this->getRegionCountries($this->page['regions']);
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

    /**
     * Maps each region ID to the country IDs assigned to it, as JSON for the country filter.
     */
    protected function getRegionCountries($regions)
    {
        $map = [];
        foreach ($regions as $region) {
            $map[$region->id] = $region->countries->pluck('id')->map(function ($id) {
                return (string)$id;
            })->values()->all();
        }

        return json_encode((object)$map);
    }

    /**
     * Saves an issue reported by a visitor on an initiative page and notifies the admin recipients.
     */
    public function onReportIssue()
    {
        $initiative = Data::where('slug', $this->param('slug'))->active()->first();
        if (!$initiative) {
            throw new ApplicationException('This initiative could not be found.');
        }

        $data = post();

        $validation = \Validator::make($data, [
            'issue_type' => 'required|in:' . implode(',', array_keys(Report::getIssueTypes())),
            'message' => 'required|max:5000',
            'reporter_name' => 'required|max:255',
            'reporter_email' => 'required|email|max:255',
            'g-recaptcha-response' => ['required', new RecaptchaValidator],
        ], [
            'issue_type.required' => 'Please choose the type of issue.',
            'issue_type.in' => 'Please choose the type of issue.',
            'message.required' => 'Please describe the issue.',
            'message.max' => 'Please keep the description under 5000 characters.',
            'reporter_name.required' => 'Please enter your name.',
            'reporter_email.required' => 'Please enter your email.',
            'reporter_email.email' => 'Please enter a valid email address.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);
        if ($validation->fails()) {
            throw new \ValidationException($validation);
        }

        $report = new Report();
        $report->data_id = $initiative->id;
        $report->issue_type = $data['issue_type'];
        $report->message = trim($data['message']);
        $report->reporter_name = trim($data['reporter_name']);
        $report->reporter_email = trim($data['reporter_email']);
        $report->status = 'open';
        $report->save();

        $recipients = array_filter(array_map('trim', explode(',', $this->property('recipientEmail', 'kkaleva@pensoft.net'))));
        if (!empty($recipients)) {
            Mail::send('pensoft.projectinitiatives::mail.notify-report', [
                'title' => $initiative->title,
                'issue_type' => $report->issue_type_label,
                'message' => $report->message,
                'name' => $report->reporter_name,
                'email' => $report->reporter_email,
                'page_url' => url('/project-initiatives/' . $initiative->slug),
                'backend_url' => Backend::url('pensoft/projectinitiatives/reports/update/' . $report->id),
            ], function ($message) use ($recipients, $report) {
                foreach ($recipients as $email) {
                    $message->to($email);
                }
                if ($report->reporter_email) {
                    $message->replyTo($report->reporter_email, $report->reporter_name);
                }
            });
        }

        return ['success' => true];
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
            // Each search term matches when all of its words appear somewhere in the initiative
            // (title, description, institution or any related country/region/type/etc.).
            // Multiple search terms are OR-ed together.
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $words = $this->splitSearchWords($term);
                    if (empty($words)) {
                        continue;
                    }
                    $q->orWhere(function ($termQuery) use ($words) {
                        foreach ($words as $word) {
                            $termQuery->where(function ($wordQuery) use ($word) {
                                $this->applyWordMatch($wordQuery, $word);
                            });
                        }
                    });
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

    /**
     * Splits a search phrase into words, ignoring punctuation and single characters.
     */
    protected function splitSearchWords($term)
    {
        $words = preg_split('/[\s\p{P}]+/u', (string)$term, -1, PREG_SPLIT_NO_EMPTY);

        return array_values(array_unique(array_filter($words, function ($word) {
            return mb_strlen($word) > 1;
        })));
    }

    /**
     * Matches a single word against the initiative's own fields and its related taxonomies.
     */
    protected function applyWordMatch($query, $word)
    {
        $like = '%' . addcslashes($word, '%_\\') . '%';

        $query->where('title', 'ilike', $like)
            ->orWhere('description', 'ilike', $like)
            ->orWhere('institution', 'ilike', $like)
            ->orWhereHas('countries', function ($q) use ($like) {
                $q->where('rainlab_location_countries.name', 'ilike', $like);
            });

        $relations = [
            'regions' => 'pensoft_projectinitiatives_regions',
            'types' => 'pensoft_projectinitiatives_types',
            'approaches' => 'pensoft_projectinitiatives_approaches',
            'landscapes' => 'pensoft_projectinitiatives_landscapes',
            'fundings' => 'pensoft_projectinitiatives_fundings',
        ];

        foreach ($relations as $relation => $table) {
            $query->orWhereHas($relation, function ($q) use ($table, $like) {
                $q->where("{$table}.title", 'ilike', $like);
            });
        }
    }
}
