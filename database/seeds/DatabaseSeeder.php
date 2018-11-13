<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //$this->call(TagSeeder::class);
        //factory(\App\Question::class, 100)->create();
//        for($i=0; $i<200; $i++){
//            DB::table('question_tag')->insert([
//                'question_id' => random_int(1,100),
//                'tag_id'=>random_int(1,22),
//            ]);
//        }
       //factory(\App\Reply::class, 300)->create();
//        foreach(\App\User::all() as $user){
//            $likedQuestionsNumber=random_int(10,20);
//
//            for($i=0; $i<$likedQuestionsNumber; $i++){
//
//                DB::table('likes')->insert([
//                    'question_id' =>random_int(1,100),
//                    'user_id'=>$user->id,
//                    'created_at'=>Carbon::now(),
//                    'updated_at'=>Carbon::now()
//                ]);
//            }
//
//
//        }
//        foreach(\App\User::all() as $user){
//            $likedQuestionsNumber=random_int(10,20);
//
//            for($i=0; $i<$likedQuestionsNumber; $i++){
//
//                DB::table('likereplies')->insert([
//                    'reply_id' =>random_int(16,315),
//                    'user_id'=>$user->id,
//
//                ]);
//            }
//
//
//        }

    }
}
