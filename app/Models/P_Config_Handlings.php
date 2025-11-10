<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class P_Config_Handlings extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $table = 'p_config_handlings';

    protected $fillable = [
        'id',
        'p_config_id',
        'handling_point',
        'handling_action'
    ];

    public function config()
    {
        return $this->belongsTo(P_Configs::class, 'p_config_id');
    }
}
