<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['id','doctor_id','patient_id','title','date','start_date','end_date','status','comment','review','updated_at'];
}
