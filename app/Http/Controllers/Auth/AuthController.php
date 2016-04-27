<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Facebook\FacebookManager;
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
            auth()->login($user, true);
        }


        return redirect()->route('auth.facebook.login')->withErrors([
            'login' => 'Access token authorization error.',
        ]);
    }

    public function unauthorized()
    {
        return view('auth.unauthorized');
    }


}
