<?php namespace Pensoft\Projectinitiatives\Models;

use Model;
use October\Rain\Database\Traits\Sluggable;

class Data extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\SoftDelete;
    use Sluggable;

    public $table = 'pensoft_projectinitiatives_data';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $slugs = ['slug' => 'title'];

    protected $fillable = ['title', 'description', 'institution', 'website', 'links', 'slug'];

    protected $jsonable = ['links'];

    public $rules = [
        'title' => 'required',
    ];

    public $translatable = [
        'title',
        'description',
    ];

    public $belongsToMany = [
        'countries' => [
            'RainLab\Location\Models\Country',
            'table' => 'pensoft_projectinitiatives_data_country',
            'key' => 'data_id',
            'otherKey' => 'country_id',
            'conditions' => 'is_enabled = true',
        ],
        'regions' => [
            'Pensoft\Projectinitiatives\Models\Region',
            'table' => 'pensoft_projectinitiatives_data_region',
            'key' => 'data_id',
            'otherKey' => 'region_id',
        ],
        'types' => [
            'Pensoft\Projectinitiatives\Models\Type',
            'table' => 'pensoft_projectinitiatives_data_type',
            'key' => 'data_id',
            'otherKey' => 'type_id',
        ],
        'approaches' => [
            'Pensoft\Projectinitiatives\Models\Approach',
            'table' => 'pensoft_projectinitiatives_data_approach',
            'key' => 'data_id',
            'otherKey' => 'approach_id',
        ],
        'landscapes' => [
            'Pensoft\Projectinitiatives\Models\Landscape',
            'table' => 'pensoft_projectinitiatives_data_landscape',
            'key' => 'data_id',
            'otherKey' => 'landscape_id',
        ],
        'fundings' => [
            'Pensoft\Projectinitiatives\Models\Funding',
            'table' => 'pensoft_projectinitiatives_data_funding',
            'key' => 'data_id',
            'otherKey' => 'funding_id',
        ],
    ];

    public $attachOne = [
        'image' => 'System\Models\File',
    ];

    public static function boot()
    {
        parent::boot();

        if (!class_exists('RainLab\Translate\Behaviors\TranslatableModel')) {
            return;
        }

        self::extend(function ($model) {
            $model->implement[] = 'RainLab.Translate.Behaviors.TranslatableModel';
        });
    }
}
