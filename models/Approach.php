<?php namespace Pensoft\Projectinitiatives\Models;

use Model;

class Approach extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'pensoft_projectinitiatives_approaches';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];

    public $rules = [
        'title' => 'required',
    ];

    public $translatable = [
        'title',
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