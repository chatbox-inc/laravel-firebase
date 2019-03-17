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
use Kreait\Firebase\Auth\UserRecord;

class FirebaseAuth
{
    public $fb;

    public $users = [];

    /**
     * FirebaseAuth constructor.
     * @param $fb
     */
    public function __construct(Firebase $fb)
    {
        $this->fb = $fb;
    }

    public function setUser($token,UserRecord $user){
        return Arr::set($this->users, $token,$user);
    }

    public function getUser($token):?UserRecord{
        return Arr::get($this->users, $token);
    }

    public function auth(Request $request):?UserRecord{
        $firebase = $this->fb;
        $token = $request->bearerToken();
        if (!$token) {
            throw new AuthenticationException();
        }

        if($user = $this->getUser($token)){
            return $user;
        }
        try {
            $verifiedIdToken = $firebase->getAuth()->verifyIdToken($token);
            $uid = $verifiedIdToken->getClaim('sub');
            return $firebase->getAuth()->getUser($uid);
        } catch (\Firebase\Auth\Token\Exception\InvalidToken $e) {
            throw new AuthenticationException();
        }
    }

}
