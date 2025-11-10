<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class P_Violations extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'p_violations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'p_category_id',
        'name',
        'point',
    ];

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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'p_category_id' => 'string',
        'point' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the violation.
     */
    public function category()
    {
        return $this->belongsTo(P_Categories::class, 'p_category_id');
    }

    /**
     * Scope a query to filter violations by category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('p_category_id', $categoryId);
    }

    /**
     * Scope a query to filter violations by minimum point.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $minPoint
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinimumPoint($query, $minPoint)
    {
        return $query->where('point', '>=', $minPoint);
    }

    /**
     * Get the formatted point with suffix.
     *
     * @return string
     */
    public function getFormattedPointAttribute()
    {
        return $this->point . ' Point' . ($this->point > 1 ? 's' : '');
    }
    public function recaps()
    {
        return $this->hasMany(P_Recaps::class, 'p_violation_id');
    }
}
