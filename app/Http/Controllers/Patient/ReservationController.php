<?php

namespace App\Http\Controllers\Patient;

use App\Exceptions\Patient\InvalidRendezVousException;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Traits\GeneralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReservationController extends Controller
{
    use GeneralCode;

    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index()
    {
        //retourner tous les rendez-vous.

        return Response::json([
            'data' => request()->user()->Doctors()->orderBy('start_date','desc')->select('users.id','nom','prenom','photo','prix','ville','cabinet_adresse')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws InvalidRendezVousException
     */
    public function store(Request $request)
    {
        //validation

        $request->validate([
            'title' => 'required|string',
            'doctor_id' => 'required',
            'start_date' => 'required|date',
        ]);
        $user = request()->user();
        $doctor = User::whereRoleIs('doctor')->where('id',$request->input('doctor_id'))->first();
        if($doctor){

            //nombre maximale des reservations 3
            $this->cheackNombre($user);
            //verifier que la date entrer est valide
            $this->cheackDate($doctor,$request->input('start_date'),true);
            // verifier que le patient n'a pas d rendez-vous qui n est pas passe avec doctor
            //verifier que le temps est disponible
            $this->ExistWithDoc($user,$doctor);
            //verifier que le patient n' a pas aucune rendez-vous dans la meme jour
            $this->ExistInSameDay($user,date("Y-m-d", strtotime($request->input('start_date'))));
            //Prendre le rendez-vous
            try{
                $user->Doctors()->attach($request->doctor_id,[
                    'title' => $request->input('title'),
                    'date' => date("Y-m-d", strtotime($request->input('start_date'))),
                    'start_date' => $request->input('start_date'),
                    'end_date' => date("Y-m-d H:i:s", strtotime( $request->input('start_date')  . "+30 minutes")),
                    'status' =>false,
                    'review' => null,
                    'comment' => null
                ]);
                return Response::json([
                    'status' => true,
                    'message' => 'Vous avez ajouter un rendez-vous avec '.$doctor->nom.' dans '.$request->input('start_date'),
                ],200);
            }catch (\Exception $e){
                return Response::json([
                    'status' => false,
                    'message' => 'Ressayer !',
                ],500);
            }


        }else{
            return Response::json([
                'status' => false,
                'message' => 'doctor not found'
            ],222);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $data = request()
            ->user()
            ->Doctors()
            ->where('reservations.id',$id)->select('users.id','nom','prenom','email','tele','photo','prix','ville','cabinet_adresse','tele_cabinet','description')
            ->get();
        $comments = User::find($data[0]->id)
            ->RendezVous()
            ->where('reservations.status',true)
            ->whereNotNull('reservations.comment')
            ->select('nom','prenom','ville')
            ->get();
        return Response::json([
            'data' => $data,
            'comments' => $comments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws InvalidRendezVousException
     */
    public function update(Request $request, $id)
    {
        //
        $user = request()->user();
        $reservation = request()->user()->Doctors()->where('reservations.id',$id)->get();
        $doctor = User::whereRoleIs('doctor')->where('id',$reservation[0]->pivot->doctor_id)->first();

        if($reservation){
            if($reservation[0]->pivot->status == 0 && $reservation[0]->pivot->start_date > date("Y-m-d H:i:s", strtotime(now()  . "+120 minutes"))) {
                $request->validate([
                    'title' => 'required|string',
                    'start_date' => 'required|date',
                ]);

                $this->ExistInSameDayExceptCurrentDay($user,$reservation[0]->pivot->date);
                //verifier que le patient n' a pas aucune rendez-vous dans le meme jour
                $this->cheackDate($doctor,$request->input('start_date'),false);
                //verifier que le temp est valide
                //verifier que le temps est disponible

                //update le temps
                try {
                    Reservation::find($id)->update([
                        'date' => date("Y-m-d", strtotime($request->input('start_date'))),
                        'start_date' => $request->input('start_date'),
                        'end_date' => date("Y-m-d H:i:s", strtotime($request->input('start_date') . "+30 minutes")),
                    ]);

                    return Response::json([
                        'status' => true,
                        'message' => 'votre rendez-vous a ete mise a jour vers le ' . $request->input('start_date'),
                    ]);
                } catch (\Exception $e) {
                    return Response::json([
                        'status' => false,
                        'message' => 'ressayer !!'
                    ], 222);
                }
            }else if($reservation[0]->pivot->status == 1 && $reservation[0]->pivot->start_date < now()){
                //type comment and review code
                $request->validate([
                    'comment' => 'required|string',
                    'review' => 'required|numeric',
                ]);
                try {
                    if($reservation[0]->pivot->review == null) {
                        $calcul = (($doctor->score * $doctor->nombre_reservations) + $request->input('review')) / ($doctor->nombre_reservations + 1);

                        $doctor->update([
                            'score' => $calcul,
                            'nombre_reservations' => $doctor->nombre_reservations + 1
                        ]);
                    }else{
                        $calcul = (($doctor->score * $doctor->nombre_reservations)-$reservation[0]->pivot->review + $request->input('review')) / ($doctor->nombre_reservations);
                        $doctor->update([
                            'score' => $calcul,
                            'nombre_reservations' => $doctor->nombre_reservations
                        ]);
                    }

                    Reservation::find($id)->update([
                        'comment' => $request->input('comment'),
                        'review' => $request->input('review'),
                    ]);


                    return Response::json([
                        'status' => true,
                        'message' => 'commentaire ajouter en success',
                    ]);
                } catch (\Exception $e) {
                    return Response::json([
                        'status' => false,
                        'message' => 'ressayer !!'
                    ], 222);
                }
            }else{
                return Response::json([
                    'status' => false,
                    'message' => 'vous n\'avez pas le droit de modifier la date de ce rendez-vous'
                ]);
            }
        }else{
            return Response::json([
                'status' => false,
                'message' => 'reservations n existe pas !'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        $user = request()->user();
        $reservation = request()->user()->Doctors()->where('reservations.id',$id)->get();
        if($reservation[0]->pivot->start_date >  date("Y-m-d H:i:s", strtotime(now()  . "+120 minutes")) && $reservation[0]->pivot->status == 0){
            try{
                Reservation::find($reservation[0]->pivot->id)->delete();
                return Response::json([
                    'status' => true,
                    'message' => 'Rendez-vous annulé '
                ]);
            }catch(\Exception $e){
                return Response::json([
                    'status' => false,
                    'message' => 'Ressayez !!'
                ]);
            }

        }else{
            return Response::json([
                'status' => true,
                'message' => 'Impossible de supprimer ce rendez-vous '
            ]);
        }
    }
}
