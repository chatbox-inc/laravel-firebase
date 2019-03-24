<?php
/**
 * Created by PhpStorm.
 * User: mkkn
 * Date: 2019/03/17
 * Time: 14:10
 */

namespace Chatbox\Larabase;


use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Kreait\Firebase;

use Illuminate\Auth\AuthenticationException;

class FirebaseAuth
{
    public $users = [];

    public function setUser($token,FirebaseUser $user){
        return Arr::set($this->users, $token,$user);
    }

    public function getUser($token):?FirebaseUser{
        return Arr::get($this->users, $token);
    }

    public function auth(Request $request):?FirebaseUser{
        $token = $request->bearerToken();
        if (!$token) {
            throw new AuthenticationException();
        }

        if($user = $this->getUser($token)){
            return $user;
        }
        try {
            /** @var Firebase $firebase */
            $firebase = app(Firebase::class);
            $verifiedIdToken = $firebase->getAuth()->verifyIdToken($token);
            $uid = $verifiedIdToken->getClaim('sub');
            return new FirebaseUser(
                $firebase->getAuth()->getUser($uid)
            );
        } catch (\Firebase\Auth\Token\Exception\InvalidToken $e) {
            throw new AuthenticationException();
        }
    }

}
