<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['latitude', 'longitude', 'distance_meters', 'requested_at'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'distance_meters' => 'integer',
        'requested_at' => 'datetime',
    ];
}