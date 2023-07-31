<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;
    protected $table = 'information';
    protected $fillable = [
        'gender', 'type_run', 'size','birthdate', 'bibnumber', 'phonenumber',
        'idcard', 'healthproblems', 'emergencyphone', 'emergencontact',
        'relationship', 'bloodtype', 'email'
    ];
}
