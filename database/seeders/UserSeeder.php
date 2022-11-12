<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $i = 0;
        \App\Models\User::factory()->count(1)
        ->create([
            'nom' => fake()->name(),
            'prenom' => fake()->name(),
            'email' => 'hmamouchi.abdellah1@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])
        ->each(
            function ($user){
                $user->attachRole('owner');
            }
        );
        $arr = ['tanger','rabat','casablanca','knietra','agadir','oujda','marakech','elhossima','sale','tetouan'];
        $jours = [[1,2,3,4,5],[0,1,3,4],[1,2,3,4,5],[0,1,2,3,4,5,6],[1,2,3]];
        $start = ['07:00:00','08:00:00','09:00:00','10:00:00','07:25:00'];
        $end = ['16:00:00','20:00:00','18:00:00','22:00:00','23:25:00'];
        $time = [15,20,10,30,25,17,21,13,45,24];
        for($i = 2; $i <= 200 ;$i++){
            $key = array_rand($arr);

            if($i<20){
                $user = User::create([
                    'nom' => Str::random(10),
                    'prenom' => Str::random(10),
                    'email' => 'patient'.$i.Str::random(10).'@gmail.com',
                    'ville' => $arr[$key],
                    'email_verified_at' => now(),
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'remember_token' => Str::random(10),
                ]);
                $user->attachRole('patient');
            }else {
                $key1 = array_rand($jours);
                $key2 = array_rand($start);
                $key3 = array_rand($end);
                $key4 = array_rand($time);
                if($i<30){
                    $user = User::create([
                        'nom' => Str::random(10),
                        'prenom' => Str::random(10),
                        'email' => 'doctor'.$i.Str::random(10).'@gmail.com',
                        'email_verified_at' => now(),
                        'ville' => $arr[$key],
                        'specialite' => 'Addictologie',
                        'prix' => 500,
                        'registercomerce' => 30000,
                        'cabinet_adresse' => Str::random(17).'rue '.$i,
                        'status' => 1,
                        'description' =>Str::random(100),
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ]);
                    $user->attachRole('doctor');
                }else if($i<40){
                    $user = User::create([
                        'nom' => Str::random(10),
                        'prenom' => Str::random(10),
                        'email' => 'doctor'.$i.Str::random(10).'@gmail.com',
                        'email_verified_at' => now(),
                        'ville' => $arr[$key],
                        'specialite' => 'Allergologie',
                        'prix' => 400,
                        'registercomerce' => 30000,
                        'cabinet_adresse' => Str::random(17).'rue '.$i,
                        'status' => 0,
                        'description' =>Str::random(100),
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ]);
                    $user->attachRole('doctor');
                }else if($i<60){
                    $user = User::create([
                        'nom' => Str::random(10),
                        'prenom' => Str::random(10),
                        'email' => 'doctor'.$i.Str::random(10).'@gmail.com',
                        'email_verified_at' => now(),
                        'ville' => $arr[$key],
                        'specialite' => 'Anatomie et cytopathologie',
                        'cabinet_adresse' => Str::random(17).'rue '.$i,
                        'prix' => 600,
                        'registercomerce' => 30000,
                        'status' => 1,
                        'description' =>Str::random(100),
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ]);
                    $user->attachRole('doctor');
                }else if($i<80){
                    $user = User::create([
                        'nom' => Str::random(10),
                        'prenom' => Str::random(10),
                        'email' => 'doctor'.$i.Str::random(10).'@gmail.com',
                        'cabinet_adresse' => Str::random(17).'rue '.$i,
                        'email_verified_at' => now(),
                        'specialite' => 'Anesthésie-réanimation',
                        'prix' => 400,
                        'ville' => $arr[$key],
                        'registercomerce' => 30000,
                        'status' => 1,
                        'description' =>Str::random(100),
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ]);
                    $user->attachRole('doctor');
                }else if($i<100){
                    $user = User::create([
                        'nom' => Str::random(10),
                        'prenom' => Str::random(10),
                        'email' => 'doctor'.$i.Str::random(10).'@gmail.com',
                        'email_verified_at' => now(),
                        'cabinet_adresse' => Str::random(17).'rue '.$i,
                        'ville' => $arr[$key],
                        'specialite' => 'Biologie médicale',
                        'prix' => 700,
                        'registercomerce' => 30000,
                        'status' => 1,
                        'description' =>Str::random(100),
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ]);
                    $user->attachRole('doctor');
                }else if($i<150){
                    $user = User::create([
                        'nom' => Str::random(10),
                        'prenom' => Str::random(10),
                        'email' => 'doctor'.$i.Str::random(10).'@gmail.com',
                        'cabinet_adresse' => Str::random(17).'rue '.$i,
                        'email_verified_at' => now(),
                        'specialite' => 'Médecine dentaire',
                        'ville' => $arr[$key],
                        'prix' => 400,
                        'registercomerce' => 30000,
                        'status' => 1,
                        'description' =>Str::random(100),
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ]);
                    $user->attachRole('doctor');
                }else if($i<=200){
                    $user = User::create([
                        'nom' => Str::random(10),
                        'prenom' => Str::random(10),
                        'cabinet_adresse' => Str::random(17).'rue '.$i,
                        'email' => 'doctor'.$i.Str::random(10).'@gmail.com',
                        'email_verified_at' => now(),
                        'specialite' => 'Médecine générale',
                        'ville' => $arr[$key],
                        'prix' => 400,
                        'registercomerce' => 30000,
                        'status' => 1,
                        'description' =>Str::random(100),
                        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                        'remember_token' => Str::random(10),
                    ]);
                    $user->attachRole('doctor');
                }
                $temps = new WorkTime();
                $temps->jours = $jours[$key1];
                $temps->debut = $start[$key2];
                $temps->fin = $end[$key3];
                $temps->dure = $time[$key4];
                $user->timework()->save($temps);
            }

        }












//        \App\Models\User::factory()->count(50)
//            ->create([
//                'nom' => fake()->name(),
//                'prenom' => fake()->name(),
//                'email' => 'patient'.$i.'@gmail.com',
//                'email_verified_at' => now(),
//                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//                'remember_token' => Str::random(10),
//            ])
//            ->each(
//                function ($user){
//                    $user->attachRole('patient');
//                }
//            );

    }
}
