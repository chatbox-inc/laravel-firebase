<?php
namespace Chatbox\Larabase\Testing;
use Chatbox\Larabase\FirebaseUser;
use Illuminate\Foundation\Testing\WithFaker;
use Chatbox\Larabase\FirebaseAuth;
use Kreait\Firebase\Auth\UserRecord;

/**
 * Created by PhpStorm.
 * User: mkkn
 * Date: 2019/03/17
 * Time: 20:17
 */

class TestFirebaseUser extends FirebaseUser
{
    use WithFaker;

    static public function fake():TestFirebaseUser{
        return (new static())->makeFackObject();
    }

    /**
     * UserRecord constructor.
     */
    public function __construct()
    {
        $this->setUpFaker();
        parent::__construct();
    }

    public function makeFackObject(){
        $this->uid = mt_rand(100000,999999);
        $this->email = $this->faker->email;
        $this->displayName = $this->faker->name;
        $this->photoUrl = "https://placehold.it/800x800";
        return $this;
    }

    public function recordAs($token){
        app()->extend(FirebaseAuth::class,function(FirebaseAuth $auth)use($token){
            $auth->setUser($token,$this);
            return $auth;
        });
        return $this;
    }
}
