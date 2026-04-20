<?php namespace Pensoft\Projectinitiatives\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Projectinitiatives\Models\Data;
use Pensoft\Projectinitiatives\Models\Type;
use Pensoft\Projectinitiatives\Models\Approach;
use Pensoft\Projectinitiatives\Models\Landscape;
use Pensoft\Projectinitiatives\Models\Region;
use Pensoft\Projectinitiatives\Models\Funding;
use RainLab\Location\Models\Country;
use Flash;
use Mail;
use Redirect;
use Str;

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
        return [
            'recipientEmail' => [
                'title' => 'Recipient Email',
                'description' => 'Email address to notify when a submission is confirmed',
                'default' => 'kkaleva@pensoft.net',
                'type' => 'string',
            ],
        ];
    }

    public function onRun()
    {
        $this->page['countries'] = Country::where('is_enabled', true)->orderBy('name')->get();
        $this->page['regions'] = Region::orderBy('sort_order')->get();
        $this->page['types'] = Type::orderBy('sort_order')->get();
        $this->page['approaches'] = Approach::orderBy('sort_order')->get();
        $this->page['landscapes'] = Landscape::orderBy('sort_order')->get();
        $this->page['fundings'] = Funding::orderBy('sort_order')->get();

        // Handle confirmation via URL token
        $token = input('confirm');
        if ($token) {
            $this->handleConfirmation($token);
        }
    }

    public function onSubmit()
    {
        $data = post();

        $rules = [
            'submitter_name' => 'required',
            'submitter_email' => 'required|email',
            'title' => 'required',
        ];
        $validation = \Validator::make($data, $rules, [
            'submitter_name.required' => 'Please enter your name.',
            'submitter_email.required' => 'Please enter your email.',
            'submitter_email.email' => 'Please enter a valid email address.',
            'title.required' => 'Please enter the initiative name.',
        ]);
        if ($validation->fails()) {
            throw new \ValidationException($validation);
        }

        $token = Str::random(64);

        $initiative = new Data();
        $initiative->title = $data['title'] ?? '';
        $initiative->description = $data['description'] ?? '';
        $initiative->institution = $data['institution'] ?? '';
        $initiative->website = $data['website'] ?? '';
        $initiative->submitter_name = $data['submitter_name'] ?? '';
        $initiative->submitter_email = $data['submitter_email'] ?? '';
        $initiative->is_active = false;
        $initiative->is_confirmed = false;
        $initiative->confirmation_token = $token;

        $initiative->save();

        // Countries is now a single value, wrap in array for sync
        $countries = array_filter((array)($data['countries'] ?? []));
        if (!empty($countries)) {
            $initiative->countries()->sync($countries);
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

        // Send confirmation email to submitter
        $confirmationUrl = url('/add-initiative') . '?confirm=' . $token;

        Mail::send('pensoft.projectinitiatives::mail.confirm', [
            'name' => $initiative->submitter_name,
            'title' => $initiative->title,
            'confirmation_url' => $confirmationUrl,
        ], function ($message) use ($initiative) {
            $message->to($initiative->submitter_email, $initiative->submitter_name);
        });

        Flash::success('Thank you! Please check your email to confirm your submission.');
        return Redirect::to('/add-initiative');
    }

    protected function handleConfirmation($token)
    {
        $initiative = Data::where('confirmation_token', $token)
            ->whereRaw('is_confirmed = false')
            ->first();

        if (!$initiative) {
            Flash::error('Invalid or already confirmed submission.');
            return;
        }

        $initiative->is_confirmed = true;
        $initiative->confirmation_token = null;
        $initiative->save();

        // Send notification to admin/recipient
        $recipientEmail = $this->property('recipientEmail', 'kkaleva@pensoft.net');
        $backendUrl = url('/admin/pensoft/projectinitiatives/data/update/' . $initiative->id);

        Mail::send('pensoft.projectinitiatives::mail.notify-admin', [
            'title' => $initiative->title,
            'name' => $initiative->submitter_name,
            'email' => $initiative->submitter_email,
            'backend_url' => $backendUrl,
        ], function ($message) use ($recipientEmail) {
            $message->to($recipientEmail);
        });

        Flash::success('Your submission has been confirmed. Thank you!');
    }
}
