<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'jours',
        'debut',
        'fin',
        'dure',
        'doctor_id',
    ];

    public function time()
    {
        return $this->belongsTo(User::class ,'id');
    }
    protected $casts = [
        'jours' => 'array'
    ];
}
