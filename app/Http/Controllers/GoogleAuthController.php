<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use PhpParser\Node\Stmt\TryCatch;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(){
        try{

            $find_user= Socialite::driver('google')->user();

            // dd($find_user);

            $user = User::where('google_id', $find_user->getId())->first();

            if (!$user) {

                $new_user = User::create([
                    'name'=> $find_user->getName(),
                    'email'=> $find_user->getEmail(),
                    'google_id'=> $find_user->getId()
                ]);

                Auth::login();

                return redirect()->intended('home');

            }else{
                Auth::login($user);

                return redirect()->intended('home');
            }


        } catch (\Throwable $th) {
            dd('Something went wrong! '. $th->getMessage());
        }
    }
}
