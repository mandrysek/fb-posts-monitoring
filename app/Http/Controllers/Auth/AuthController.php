<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Facebook\FacebookManager;
use App\Services\Instagram\InstagramManager;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct()
    {
    }

    public function index()
    {
        $fbManager = new FacebookManager();
        $loginUrl = $fbManager->getLoginUrl(route('auth.facebook.callback'));

        return view('auth.index', compact('loginUrl'));
    }

    public function facebookCallback(Request $request)
    {
        if (!empty($request->get('error')))
        {
            switch ($request->get('error'))
            {
                case 'access_denied':
                    $errorMessage = 'Facebook Login is required.';
                    break;
                default:
                    $errorMessage = 'Unknown error occurred. Please try again later.';
            }

            return redirect()->route('auth.facebook.login')->withErrors([
                'login' => $errorMessage,
            ]);
        }


        $fbManager = new FacebookManager();
        $user = $fbManager->handleCallback();

        if ($user instanceof User)
        {
            \Auth::login($user, true);
            return redirect()->route('home');
        }


        return redirect()->route('auth.facebook.login')->withErrors([
            'login' => 'Access token authorization error.',
        ]);
    }

    public function unauthorized()
    {
        return view('auth.unauthorized');
    }


    public function instagram() {

        if (\Auth::user()->in_token !== null) {
            return redirect()->route('home');
        }

        $inManager = new InstagramManager();
        $loginUrl = $inManager->getLoginUrl(route('auth.instagram.callback'));

        return view('auth.instagram', compact('loginUrl'));
    }

    public function instagramCallback(Request $request) {

        if (!empty($request->get('error')))
        {
            switch ($request->get('error'))
            {
                case 'access_denied':
                    $errorMessage = 'Instagram Login is required.';
                    break;
                default:
                    $errorMessage = 'Unknown error occurred. Please try again later.';
            }

            return redirect()->route('auth.instagram.login')->withErrors([
                'login' => $errorMessage,
            ]);
        }

        $inManager = new InstagramManager();
        $callback = $inManager->handleCallback(route('auth.instagram.callback'));
        
        if ($callback === false) {
            return redirect()->route('auth.instagram.login')->withErrors([
                'callback' => 'Could not get Access Token',
            ]);
        }

        $user = \Auth::user();

        $user->in_id = $callback['user'];
        $user->in_token = $callback['access_token'];

        $user->save();

        return redirect()->route('home');
    }

}
