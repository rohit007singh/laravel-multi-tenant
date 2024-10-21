<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'domain',
        'visitor_count',
        'category'
    ];

    public function tokens()
    {
        return $this->hasMany(Token::class, 'domain_id');
    }
}
