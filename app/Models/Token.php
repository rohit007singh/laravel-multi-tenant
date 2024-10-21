<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'domain_id',
        'token',
        'location'
    ];

    public function website()
    {
        return $this->belongsTo(Website::class, 'domain_id');
    }
}
