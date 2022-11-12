<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ShowDoctorController extends Controller
{
    //
    public function show($id){
        $times = array() ;
        $invalid = array() ;
        $comment_review = array() ;
        $user = User::Where('id',$id)->where('status',1)->first();

        if($user && $user->hasRole('doctor')){
            $timework = $user->timework()->get();

            $inavialable_times = $user->RendezVous()->where('start_date', '>',now())->where('reservations.status', 0)->select('reservations.start_date')->get();

            $comment = $user
                ->RendezVous()
                ->where('start_date', '<',now())
                ->where('reservations.status', 1)
                ->whereNotNull('reservations.comment')
                ->whereNotNull('reservations.review')
                ->select('nom','ville','prenom','reservations.review','reservations.comment','reservations.updated_at')
                ->get();


            $item = $timework[0]->debut;
            while ($timework[0]->fin > $item){
                $times[] = $item;
                $item = date("H:i:s", strtotime($item  . "+".$timework[0]->dure." minutes"));
            }

            if($inavialable_times){
                foreach ($inavialable_times as $item){
                    $invalid[] = $item->start_date;
                }
            }
            if($comment){
                foreach ($comment as $item){
                    $comment_review[] = [
                        'nom' => $item->nom,
                        'prenom' =>  $item->prenom,
                        'ville' => $item->ville,
                        'review' => $item->review,
                        'comment' => $item->comment,
                        'time' => $item->updated_at,
                    ];

                }
            }
            return Response::json([
                'data' => $user,
                'possible_times' => $times,
                'work_times' => $timework,
                'inavialable_times' => $invalid,
                'comment_review' => $comment_review
            ],200);
        }

        return Response::json([
            'data' => 'id not found',
        ],401);
    }
}
