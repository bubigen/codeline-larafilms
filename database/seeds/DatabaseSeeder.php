<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(App\User::class, 3)->create();
        factory(App\Models\Film::class, 3)->create()->each(function($film) {
        	$film->comments()->save(factory(App\Models\Comment::class)->make());
        });		
    }
}
