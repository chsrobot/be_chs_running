<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OthersInformation extends Model
{
    use HasFactory;
    protected $table = 'others_informations';
    protected $fillable = [
        'gender','name','lastname', 'type_run', 'birthdate', 'bibnumber', 'phonenumber',
        'idcard', 'healthproblems', 'emergencyphone', 'emergencontact',
        'relationship', 'bloodtype', 'email'
    ];
}
