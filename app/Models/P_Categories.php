<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class P_Categories extends Model
{
    protected $table = 'p_categories';
    public $incrementing = false; // Karena bukan auto increment
    protected $keyType = 'string'; // UUID adalah string

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function violations()
    {
        return $this->hasMany(P_Violations::class, 'p_category_id', 'id');
    }
}
