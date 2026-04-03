<?php namespace Pensoft\Projectinitiatives\Models;

use Model;

class Landscape extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'pensoft_projectinitiatives_landscapes';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'description'];

    public $rules = [
        'title' => 'required',
    ];

    public $translatable = [
        'title',
        'description',
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