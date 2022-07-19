<?php


namespace App\Services;


use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function createUser(array $validated): string
    {
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $payload = [
            'Sub' => $user->id,
            'Exp' => now()->addHour()->format('U')
        ];

        return JWT::encode($payload, config('jwt.key'), 'HS256');
    }


    public function loginUser(array $validated): string|null
    {
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $user = User::where('email', $validated['email'])->first();

            $payload = [
                'Sub' => $user->id,
                'Exp' => now()->addHour()->format('U')
            ];

            return JWT::encode($payload, config('jwt.key'), 'HS256');
        }
        else
            return null;
    }
}
