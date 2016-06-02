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
        $sports = \App\Models\BannedString::where('id', '>=', 764)->where('id', '<=', 845)->get();
        $lastNames = \App\Models\BannedString::where('id', '>=', 854)->where('id', '<=', 929)->get();

        $olympijsk = \App\Models\BannedString::find(731);
        $fan1 = \App\Models\BannedString::find(849);
        $fan2 = \App\Models\BannedString::find(850);
        $fan3 = \App\Models\BannedString::find(851);

//        foreach ($sports as $sport) {
//            $olympijsk->children()->save($sport);
//            $rio->children()->save($sport);
//        }

        $olympijsk->children()->save($fan1);
        $olympijsk->children()->save($fan2);
        $olympijsk->children()->save($fan3);

        foreach ($lastNames as $lastName) {
            $lastName->children()->save($fan1);
            $lastName->children()->save($fan2);
            $lastName->children()->save($fan3);
        }
    }
}
