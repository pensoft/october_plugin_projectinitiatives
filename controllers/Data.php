<?php namespace Pensoft\Projectinitiatives\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Pensoft\Projectinitiatives\Models\Data as DataModel;
use Flash;

class Data extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
        \Backend\Behaviors\ReorderController::class,
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Pensoft.Projectinitiatives', 'projectinitiatives', 'data');
    }

    public function onActivate()
    {
        $checked = post('checked', []);
        DataModel::whereIn('id', $checked)->update(['is_active' => true]);
        Flash::success('Selected initiatives have been activated.');
    }

    public function onDeactivate()
    {
        $checked = post('checked', []);
        DataModel::whereIn('id', $checked)->update(['is_active' => false]);
        Flash::success('Selected initiatives have been deactivated.');
    }
}
