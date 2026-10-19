<?php namespace Pensoft\Projectinitiatives\Models;

use Model;

class Report extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;

    public $table = 'pensoft_projectinitiatives_reports';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = ['issue_type', 'message', 'reporter_name', 'reporter_email', 'status', 'admin_notes'];

    public $rules = [
        'data_id' => 'required',
        'issue_type' => 'required',
        'message' => 'required',
        'reporter_email' => 'nullable|email',
    ];

    public $belongsTo = [
        'initiative' => [
            'Pensoft\Projectinitiatives\Models\Data',
            'key' => 'data_id',
        ],
    ];

    public static function getIssueTypes()
    {
        return [
            'incorrect' => 'Incorrect information',
            'missing' => 'Missing information',
            'broken_link' => 'Broken or outdated link',
            'other' => 'Other',
        ];
    }

    public static function getStatuses()
    {
        return [
            'open' => 'Open',
            'resolved' => 'Resolved',
            'dismissed' => 'Dismissed',
        ];
    }

    public function getIssueTypeOptions()
    {
        return static::getIssueTypes();
    }

    public function getStatusOptions()
    {
        return static::getStatuses();
    }

    public function getIssueTypeLabelAttribute()
    {
        return static::getIssueTypes()[$this->issue_type] ?? $this->issue_type;
    }
}
