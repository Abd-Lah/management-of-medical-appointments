<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReservationController extends Controller
{
    //
    public function index(){

        $rendez_vous = request()->user()->RendezVous()->where('reservations.status',0)->where('reservations.date',Carbon::now()->format('Y-m-d'))->orderBy('reservations.start_date','asc')->paginate(8);
        return $rendez_vous;

    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);
        $rendez_vous = request()->user()->RendezVous()->where('reservations.id',$id)->update([
            'reservations.status' => $request->input('status')
        ]);


        return Response::json([
            'message' => 'Mise a jour en succes',
        ]);

    }
    public function search(Request $request){
        switch (count(array_filter($request->toArray()))){
            case 0 :
                return Response::json([
                            'message' => 'selectioner un option !'
                        ]);
            case 1 :
                if($request->input('status') != null){
                     return request()->user()
                    ->RendezVous()
                    ->where('reservations.status',$request->input('status'))
                    ->orderBy('reservations.updated_at','asc')
                    ->paginate(8);
                } else if ($request->input('date') != null){
                    return request()->user()
                        ->RendezVous()
                        ->where('reservations.date',$request->input('date'))
                        ->orderBy('reservations.updated_at','asc')
                        ->paginate(8);
                } else if ($request->input('ville') != null){
                    return request()->user()
                        ->RendezVous()
                        ->where('users.ville',$request->input('ville'))
                        ->orderBy('reservations.updated_at','asc')
                        ->paginate(8);
                }
                break;
            case 2:
                if($request->input('status') != null && $request->input('date') != null){
                    return request()->user()
                        ->RendezVous()
                        ->where('reservations.status',$request->input('status'))
                        ->where('reservations.date',$request->input('date'))
                        ->orderBy('reservations.updated_at','asc')
                        ->paginate(8);
                } else if ($request->input('status') != null && $request->input('ville') != null){
                    return request()->user()
                        ->RendezVous()
                        ->where('reservations.status',$request->input('status'))
                        ->where('users.ville',$request->input('ville'))
                        ->orderBy('reservations.updated_at','asc')
                        ->paginate(8);
                } else if ($request->input('date') != null && $request->input('ville') != null){
                    return request()->user()
                        ->RendezVous()
                        ->where('reservations.date',$request->input('date'))
                        ->where('users.ville',$request->input('ville'))
                        ->orderBy('reservations.updated_at','asc')
                        ->paginate(8);
                }
                break;
            case 3:
                return request()->user()
                ->RendezVous()
                ->where('users.ville',$request->input('ville'))
                ->where('reservations.date',$request->input('date'))
                ->where('reservations.status',$request->input('status'))
                ->orderBy('reservations.updated_at','asc')
                ->paginate(8);
        }
    }
    public function eventOnCalendar(){
        $doctor = request()->user();
        return Response::json([
            'events' => $doctor->RendezVous()->where('date','>=',Carbon::now()->format('Y-m-d'))->orderBy('reservations.updated_at','asc')->get(),
        ]);
    }
}
