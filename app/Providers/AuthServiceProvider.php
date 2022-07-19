<?php

namespace App\Providers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('jwt', function (Request $request) {
            try{
                $tokenPayload = JWT::decode($request->bearerToken(), new Key(config('jwt.key'), 'HS256'));
                return User::find($tokenPayload->Sub)->first();
            } catch(\Exception $th){
                Log::error($th);
                return null;
            }
        });
    }
}
