## Larabase - firebase with Laravel


## functions and scopes

構造 firebase-php をベースにLaravel 向けの Wrapper を付与しています。

https://firebase-php.readthedocs.io/en/stable/setup.html

kreait/laravel-firebase が json file ベースの認証をサポートしているのに対し、
こちらのライブラリでは、環境変数値での認証をサポートしています。

- [x] Firebase オブジェクトをコンテナに注入.
- [x] Firebase uid ベースの認証ドライバの実装.
- [x] unit test のための Auth Mock.

## Injected Firebase Object

`laravel-firebase` では、 `kreait/firebase-php` を利用しています。

https://github.com/kreait/firebase-php

You can get `Kreait\Firebase` Object from DI Container.

You will put your firebase config on your `config/services.php`.

```php
<?php
return [
    // ...
    'firebase' => [
        "type"=> "service_account",
        "project_id"=> env("FIREBASE_PROJECTID"),
        "private_key_id"=> env("FIREBASE_PRIVATEID"),
        "private_key"=> str_replace("\\n","\n",env("FIREBASE_PRIVATEKEY")),
        "client_email"=> env("FIREBASE_CLIENTEMAIL"),
        "client_id"=> env("FIREBASE_CLIENTID"),
        "auth_uri"=> "https://accounts.google.com/o/oauth2/auth",
        "token_uri"=> "https://oauth2.googleapis.com/token",
        "auth_provider_x509_cert_url"=> "https://www.googleapis.com/oauth2/v1/certs",
        "client_x509_cert_url"=> env("FIREBASE_X509CERTURL")
    ],
];
```

`.env` は以下のような構成になります。

```
FIREBASE_PROJECTID=
FIREBASE_PRIVATEID=
FIREBASE_PRIVATEKEY=
FIREBASE_CLIENTEMAIL=
FIREBASE_CLIENTID=
FIREBASE_X509CERTURL=
```

containered object also has alias name `firebase`. so you can recieve firebase object also like this.

```php
/** @var \Kreait\Firebase $firebase */
$firebase = app("firebase");
```  

## FirebaseAuth

`laravel-firebase` provide Request Guard that is named `firebase_idtoken`.

With this guard, REST API will be written like below.

```php
Route::group([
    "middleware" => [
        "auth:firebase_idtoken",
    ]
],function(){
    Route::post('/user', function(){
        $user = Auth::guard("firebase_idtoken")->user();
        //...
    });
});
```

In this guard, `Auth::guard("firebase_idtoken")` will return `\Chatbox\Larabase\FirebaseUser` 
that is subclass `\Kreait\Firebase\Auth\UserRecord` and implementation for `Illuminate\Auth\Authenticatable`.

## FirebaseAuth Mock

`TestFirebaseUser` create mock firebase user.

With this, you can test your REST API without firebase setting.

```php
<?php

namespace Tests\Feature;

use Chatbox\Larabase\Testing\TestFirebaseUser;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProjectIdTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        if(!TestFirebaseUser::users()){
            $token = Str::random();
            $user = TestFirebaseUser::fake()->recordAs($token);
            // Store firebase user to database or ...
            $this->withHeader("Authorization", "Bearer $token");
        }else{
            TestFirebaseUser::remenber(function($token, $user){
                $this->withHeader("Authorization", "Bearer $token");
            });
        }
        config()->set("app.url","http://localhost/api/");
    }
}
```

`TestFirebaseUser::fake()` create firebase dummy user.
Method `recordAs` will store the user with uid(you generate it!).

`TestFirebaseUser::users()` will return all stored users,
and `TestFirebaseUser::remenber` will pop out each stored user to passed closure.






