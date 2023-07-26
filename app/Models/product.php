<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $fillable = [ 
        'type_hat',
        'type_shirt',
        'price_hat',
        'price_shirt'
    ];
}
