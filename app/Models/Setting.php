<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'app_name',
        'logo',
        'favicon',
        'lembaga',
        'admin_email',
        'admin_password',
    ];
}
