<?php namespace Pensoft\Projectinitiatives\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Reports extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Pensoft.Projectinitiatives', 'projectinitiatives', 'reports');
    }
}
