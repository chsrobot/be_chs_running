<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payment_evidences';
    protected $fillable = [
        'email_uploader',
        'picture_path',
        'status',
        'email_checker',
        'timestamp',
    ];
}
