<?php

namespace App\Traits;

use App\Exceptions\Patient\InvalidRendezVousException;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

trait GeneralCode {

    /**
     * @param Request $request
     */
    public function search($var, $champ, $status) {

        return Response::json([
            'status' => true,
            'data_search' =>User::whereRoleIs('doctor')->where($champ,$var)
                ->where('status',$status)
                ->orderBy('score','desc')
                ->orderBy('nombre_reservations','desc')
                ->orderBy('created_at','asc')
                ->select
                (
                    'id',
                    'nom',
                    'prenom',
                    'photo',
                    'email',
                    'specialite',
                    'ville',
                    'cabinet_adresse',
                    'registercomerce',
                    'tele',
                    'tele_cabinet',
                    'score',
                    'nombre_reservations',
                    'created_at',
                )
                ->paginate(5),

        ],200);
    }
    public function searchByvilleAndSpeciality($var, $var2, $status) {

        return Response::json([
            'status' => true,
            'data_search' =>User::whereRoleIs('doctor')->where('ville',$var)->where('specialite',$var2)
                ->where('status',$status)
                ->orderBy('score','desc')
                ->orderBy('nombre_reservations','desc')
                ->orderBy('created_at','asc')
                ->select
                (
                    'id',
                    'nom',
                    'prenom',
                    'photo',
                    'email',
                    'specialite',
                    'ville',
                    'cabinet_adresse',
                    'registercomerce',
                    'tele',
                    'tele_cabinet',
                    'score',
                    'nombre_reservations',
                    'created_at',
                )
                ->paginate(5),

        ],200);

    }

    public function cheackNombre($user){
        if($user->doctors()->where('reservations.status', false)->count() >= 3){
            throw new InvalidRendezVousException( 'Nombre maximale des rendez-vous est 3');
        }
    }

    /**
     * @throws InvalidRendezVousException
     */
    public function cheackDate($doctor, $start, $except){
        //verifier que le temps est disponible
        //verifier la date entrer obligatoire apres 2 heures minimun
        $dt = DateTime::createFromFormat("Y-m-d H:i:s", $start);
        $timework = $doctor->timework()->get();
        //apres 2 heures
        if(date("Y-m-d H:i:s", strtotime($start)) <= date("Y-m-d H:i:s", strtotime(now()  . "+120 minutes"))){
            throw new InvalidRendezVousException( 'Essayer apres : '.date("H:i:s", strtotime(now()  . "+120 minutes")));
        }
        //verfier le jour et le temps
        if($timework){
            if(!in_array(date('w', strtotime($start)), $timework[0]->jours)){
                throw new InvalidRendezVousException( 'invalid date');
            }
            $times = array() ;

            $item = $timework[0]->debut;
            while ($timework[0]->fin > $item){
                $times[] = $item;
                $item = date("H:i:s", strtotime($item  . "+".$timework[0]->dure." minutes"));
            }

            if (in_array( $dt->format('H:i:s'), $times) ){
                $res = $doctor->RendezVous()->where('start_date', '=',$start)->where('reservations.status', 0)->first();
                if($except){
                    if($res){
                        throw new InvalidRendezVousException('Temps indisponible ');
                    }
                }else{
                    if($res){
                        if($res->id != request()->user()->id ){
                            throw new InvalidRendezVousException('Temps indisponible ');
                        }

                    }
                }



            }else{
                throw new InvalidRendezVousException( 'heure de début n\' est pas valide ');
            }
        }
    }
    public function ExistWithDoc($user,$doctor){

        if($doctor){
            $doctor_rendez_vous = $doctor->RendezVous()->where('date','>=',Carbon::now()->format('Y-m-d'))->where('reservations.status',0)->get();
            if($doctor_rendez_vous) {

                foreach ($doctor_rendez_vous as $check) {
                    if ($check->id == $user->id) {
                        throw new InvalidRendezVousException( 'vous avez deja un rendez-vous avec Dr ' . $doctor->nom);
                    }
                }
            }
        }
    }

    public function ExistInSameDay($user,$start){
        $user_rendez_vous = $user->Doctors()->where('date','=',$start)->where('reservations.status',0)->get();
        if($user_rendez_vous) {
            foreach ($user_rendez_vous as $check) {
                if ($check->pivot->patient_id == $user->id) {
                    throw new InvalidRendezVousException( 'vous avez deja un rendez-vous dans ce jour');
                }
            }
        }
    }

    public function ExistInSameDayExceptCurrentDay($user,$currentDate)
    {
        $user_rendez_vous = $user->Doctors()->where('date','=',Carbon::now()->format('Y-m-d'))->where('reservations.status',0)->get();
        if($user_rendez_vous) {
            foreach ($user_rendez_vous as $check) {
                if ($check->pivot->patient_id == $user->id && $check->pivot->date != $currentDate) {
                    throw new InvalidRendezVousException( 'vous avez deja un rendez-vous dans ce jour');
                }
            }
        }
    }

}
