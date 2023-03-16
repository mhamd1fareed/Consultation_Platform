<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;
    protected $table = 'informations';
    protected $fillable = [
        'expert_name',
        'experiences',
        'image_url',
        'contact_info',
        'consultation_type',
        'expert_id',
    ];
}
