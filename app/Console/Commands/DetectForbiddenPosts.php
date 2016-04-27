<?php

namespace App\Console\Commands;

use App\Models\BannedString;
use App\Models\User;
use App\Services\Facebook\FacebookManager;
use Illuminate\Console\Command;

class DetectForbiddenPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'detect:forbiddenPosts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detection of forbidden facebook posts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $facebookManager = new FacebookManager();
        $bannedStrings = BannedString::all();

        $user = User::where('name', 'Miroslav Andrýsek')->first();

        if (!is_null($user))
        {
            $facebookManager->detectForbiddenPosts($bannedStrings, $user->fb_token);
        }


        $this->info('Detection completed.');
    }
}
