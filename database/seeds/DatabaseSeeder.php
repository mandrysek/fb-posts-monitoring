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
        $rio = \App\Models\BannedString::find(732);

        foreach ($sports as $sport) {
            $olympijsk->children()->save($sport);
            $rio->children()->save($sport);
        }

        foreach ($lastNames as $lastName) {
            $olympijsk->children()->save($lastName);
            $rio->children()->save($lastName);
        }
    }
}
