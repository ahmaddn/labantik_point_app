<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class P_PointReduction extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'p_point_reductions';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    protected $fillable = [
        'ref_student_id',
        'academic_year',
        'points_reduced',
        'reason',
        'created_by'
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\RefStudent::class, 'ref_student_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
