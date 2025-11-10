<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class P_Configs extends Model
{
    use HasUuids;

    protected $table = 'p_configs';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'academic_year',
        'is_active',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function ($config) {
            if ($config->is_active) {
                // Nonaktifkan semua konfigurasi lainnya
                self::where('id', '!=', $config->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function getActiveAcademicYear()
    {
        $currentYear = now()->year; // contoh: 2026

        return self::all()->first(function ($config) use ($currentYear) {
            [$startYear, $endYear] = explode('/', $config->academic_year);

            return $currentYear >= intval($startYear) && $currentYear <= intval($endYear);
        });
    }


    public function handlings()
    {
        return $this->hasMany(P_Config_Handlings::class, 'p_config_id');
    }


    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
