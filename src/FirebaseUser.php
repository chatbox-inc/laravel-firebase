<?php
/**
 * Created by PhpStorm.
 * User: mkkn
 * Date: 2019/03/24
 * Time: 12:12
 */

namespace Chatbox\Larabase;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Kreait\Firebase\Auth\UserRecord;

class FirebaseUser extends UserRecord implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;


    /**
     * FirebaseUser constructor.
     */
    public function __construct(UserRecord $userRecord=null)
    {
        if($userRecord){
            $this->uid = $userRecord->uid;
            $this->email = $userRecord->email;
            $this->emailVerified = $userRecord->emailVerified;
            $this->displayName = $userRecord->displayName;
            $this->photoUrl = $userRecord->photoUrl;
            $this->phoneNumber = $userRecord->phoneNumber;
            $this->disabled = $userRecord->disabled;
            $this->metadata = $userRecord->metadata;
            $this->providerData = $userRecord->providerData;
            $this->passwordHash = $userRecord->passwordHash;
            $this->customAttributes = $userRecord->customAttributes;
            $this->tokensValidAfterTime = $userRecord->tokensValidAfterTime;
        }
    }

    public function getKeyName(){
        return "uid";
    }
}
