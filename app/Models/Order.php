<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_email', 'product_name', 'quantity'];

    // Define the relationship between Order and User (assuming 'email' is the primary key for the users table)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }
}
