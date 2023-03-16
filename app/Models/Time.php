<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;
    protected $table = 'times';
    protected $fillable = [
        'start',
        'finish',
        'state',     
        'day',
        'date',
        'appointment_id',
        'expert_id',
        'user_name',
        'price'
    ];
}
/*
            $table->time('start');
            $table->time('finish');
	        $tabel->string('day');
            $table->date('date');
            $tabel->float('price')->default(0);
            $table->boolean('state')->default(true);
            $table->string('user_name')->default(null);
            $table->foreignId('appointment_id')->constrained('appointments')->cascadeOnDelete();
 */