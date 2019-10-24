<?php
namespace Chatbox\Larabase;

use Illuminate\Support\ServiceProvider;

use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

/**
 * Created by PhpStorm.
 * User: mkkn
 * Date: 2018/12/19
 * Time: 0:09
 */

class FirebaseServiceProvider extends ServiceProvider
{
    public function boot(){
        config()->set("auth.guards.firebase_idtoken",[
            'driver' => 'firebase_idtoken',
        ]);
    }

    public function register()
    {
        app()->singleton(FirebaseAuth::class);

        app()->singleton(Factory::class,function(){
            $serviceAccount = ServiceAccount::fromArray(config("services.firebase",[]));
            $firebase = (new Factory())
                ->withServiceAccount($serviceAccount);
            return $firebase;
        });

        app()->alias(Factory::class,"firebase");

        app('auth')->viaRequest('firebase_idtoken', function ($request) {
            /** @var FirebaseAuth $auth */
            $auth = app(FirebaseAuth::class);
            return $auth->auth($request);
        });
    }
}
