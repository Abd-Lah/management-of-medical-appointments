<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * @method static whereRoleIs(string $string)
 * @method static wherePermissionIs($name)
 */
class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'id',
        'nom',
        'prenom',
        'photo',
        'email',
        'password',
        'specialite',
        'ville',
        'cabinet_adresse',
        'registercomerce',
        'tele',
        'tele_cabinet',
        'description',
        'prix',
        'status',
        'score',
        'nombre_reservations'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $attributes = [
        'status' => false,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function RendezVous()
    {
        return $this->belongsToMany(User::class, 'reservations', 'doctor_id' , 'patient_id','id','id')
            ->withPivot(['id','title','date','start_date','end_date','comment','status','review','updated_at']);
    }

    public function Doctors()
    {
        return $this->belongsToMany(User::class, 'reservations', 'patient_id' , 'doctor_id')
            ->withPivot(['id','title','date','start_date','end_date','updated_at','comment','status','review','updated_at']);
    }

    public function timework()
    {
        return $this->hasOne(WorkTime::class,'doctor_id');
    }
}
