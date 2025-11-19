<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAddress extends Model
{
    protected $table = 'company_address';

    protected $fillable = [
        'company_name',
        'email',
        'contact_phone',
        'city',
        'sub_city',
        'building_name',
        'building_number',
        'website',
    ];
}
