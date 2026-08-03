<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefClassAcademicYear extends Model
{
    use HasUuids;

    protected $table = 'ref_classes_academic_years';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'classes_id',
        'academic_year',
        'created_by',
        'updated_by',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(RefClass::class, 'classes_id');
    }
}
