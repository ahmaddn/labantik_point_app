<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class P_Viol_Action_Detail extends Model
{
    use HasUuids;

    protected $table = 'p_viol_action_details';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'p_viol_action_id',
        'parent_name',
        'student_name',
        'prey',
        'action_date',
        'reference_number',
        'time',
        'room',
        'facing',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function violAction()
    {
        return $this->belongsTo(P_Viol_Action::class, 'p_viol_action_id');
    }
}
