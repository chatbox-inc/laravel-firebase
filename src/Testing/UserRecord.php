<?php
namespace Chatbox\Larabase\Testing;
use Illuminate\Foundation\Testing\WithFaker;
use Kreait\Firebase\Auth\UserRecord as BaseUserRecord;
use Chatbox\Larabase\FirebaseAuth;

/**
 * Created by PhpStorm.
 * User: mkkn
 * Date: 2019/03/17
 * Time: 20:17
 */

class UserRecord extends BaseUserRecord
{
    use WithFaker;

    static public function fake():UserRecord{
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
        $this->uid = 1234556;
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
