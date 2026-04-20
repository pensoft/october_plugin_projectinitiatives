<?php namespace Pensoft\Projectinitiatives;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            \Pensoft\Projectinitiatives\Components\InitiativesList::class => 'initiativesList',
            \Pensoft\Projectinitiatives\Components\InitiativeForm::class => 'initiativeForm',
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'pensoft.projectinitiatives::mail.confirm' => 'Confirmation email sent to the initiative submitter.',
            'pensoft.projectinitiatives::mail.notify-admin' => 'Notification email sent to admin after submitter confirms.',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerPermissions()
    {
        return [
            'pensoft.projectinitiatives.access' => [
                'tab' => 'Project Initiatives',
                'label' => 'Manage project initiatives'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'projectinitiatives' => [
                'label'       => 'Project Initiatives',
                'url'         => \Backend::url('pensoft/projectinitiatives/data'),
                'icon'        => 'icon-pagelines',
                'permissions' => ['pensoft.projectinitiatives.*'],
                'sideMenu' => [
                    'data' => [
                        'label'       => 'Initiatives',
                        'url'         => \Backend::url('pensoft/projectinitiatives/data'),
                        'icon'        => 'icon-database',
                        'permissions' => ['pensoft.projectinitiatives.*'],
                    ],
                    'types' => [
                        'label'       => 'Types',
                        'url'         => \Backend::url('pensoft/projectinitiatives/types'),
                        'icon'        => 'icon-list',
                        'permissions' => ['pensoft.projectinitiatives.*'],
                    ],
                    'approaches' => [
                        'label'       => 'Approaches',
                        'url'         => \Backend::url('pensoft/projectinitiatives/approaches'),
                        'icon'        => 'icon-compass',
                        'permissions' => ['pensoft.projectinitiatives.*'],
                    ],
                    'landscapes' => [
                        'label'       => 'Landscapes',
                        'url'         => \Backend::url('pensoft/projectinitiatives/landscapes'),
                        'icon'        => 'icon-tree',
                        'permissions' => ['pensoft.projectinitiatives.*'],
                    ],
                    'regions' => [
                        'label'       => 'Regions',
                        'url'         => \Backend::url('pensoft/projectinitiatives/regions'),
                        'icon'        => 'icon-globe',
                        'permissions' => ['pensoft.projectinitiatives.*'],
                    ],
                    'fundings' => [
                        'label'       => 'Fundings',
                        'url'         => \Backend::url('pensoft/projectinitiatives/fundings'),
                        'icon'        => 'icon-money',
                        'permissions' => ['pensoft.projectinitiatives.*'],
                    ],

                ]
            ],
        ];
    }
}
