<?php namespace Pensoft\Projectinitiatives\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Projectinitiatives\Models\Data;
use Pensoft\Projectinitiatives\Models\Type;
use Pensoft\Projectinitiatives\Models\Approach;
use Pensoft\Projectinitiatives\Models\Landscape;
use Pensoft\Projectinitiatives\Models\Region;
use Pensoft\Projectinitiatives\Models\Funding;
use RainLab\Location\Models\Country;
use ValidationException;
use Flash;
use Redirect;

class InitiativeForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Initiative Form',
            'description' => 'Form to submit a new project initiative'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->page['countries'] = Country::where('is_enabled', true)->orderBy('name')->get();
        $this->page['regions'] = Region::orderBy('sort_order')->get();
        $this->page['types'] = Type::orderBy('sort_order')->get();
        $this->page['approaches'] = Approach::orderBy('sort_order')->get();
        $this->page['landscapes'] = Landscape::orderBy('sort_order')->get();
        $this->page['fundings'] = Funding::orderBy('sort_order')->get();
    }

    public function onSubmit()
    {
        $data = post();

        $initiative = new Data();
        $initiative->title = $data['title'] ?? '';
        $initiative->description = $data['description'] ?? '';
        $initiative->institution = $data['institution'] ?? '';
        $initiative->website = $data['website'] ?? '';
        $initiative->is_active = false;

        $initiative->validate();
        $initiative->save();

        if (!empty($data['countries'])) {
            $initiative->countries()->sync($data['countries']);
        }
        if (!empty($data['regions'])) {
            $initiative->regions()->sync($data['regions']);
        }
        if (!empty($data['types'])) {
            $initiative->types()->sync($data['types']);
        }
        if (!empty($data['approaches'])) {
            $initiative->approaches()->sync($data['approaches']);
        }
        if (!empty($data['landscapes'])) {
            $initiative->landscapes()->sync($data['landscapes']);
        }
        if (!empty($data['fundings'])) {
            $initiative->fundings()->sync($data['fundings']);
        }

        if ($data['image'] ?? null) {
            $initiative->image = \Input::file('image');
            $initiative->save();
        }

        Flash::success('Initiative submitted successfully!');
        return Redirect::to('/project-initiatives');
    }
}
