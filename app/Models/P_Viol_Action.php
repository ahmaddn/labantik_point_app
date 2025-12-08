<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class P_Viol_Action extends Model
{
    use HasUuids;

    protected $table = 'p_viol_actions';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'p_student_academic_year_id',
        'handling_id',
        'handled_by',
        'activity',
        'description',
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

    public function academicYear()
    {
        return $this->belongsTo(RefStudentAcademicYear::class, 'p_student_academic_year_id');
    }

    public function handling()
    {
        return $this->belongsTo(P_Config_Handlings::class, 'handling_id');
    }

    public function handle()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function detail()
    {
        return $this->hasOne(P_Viol_Action_Detail::class, 'p_viol_action_id');
    }
}
