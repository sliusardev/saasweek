<?php

namespace App\Http\Controllers\Socialite;

use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Models\AuthProviders;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class ProviderCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $provider)
    {
        if (!in_array($provider, ['google', 'github'])) {
            return redirect(route('login'))->withErrors(['provider' => 'Invalid provider']);
        }

        Log::error($provider. ' callback');

        $socialUser = Socialite::driver($provider)->stateless()->user();
        $user = User::query()->where('email', $socialUser->email)->first();

        $providerUser = AuthProviders::query()->updateOrCreate([
            'provider_id' => $socialUser->id,
            'provider_name' => $provider,
        ], [
            'user_id' => $user->id ?? null,
            'name' => $socialUser->name,
            'email' => $socialUser->email,
            'avatar' => $socialUser->avatar,
            'nickname' => $socialUser->nickname,
            'token' => $socialUser->token,
            'refresh_token' => $socialUser->refreshToken,
        ]);

        if (!$user) {
            $role = Role::query()->where('name', RoleEnum::CLIENT->value)->first();

            $user = User::query()->create([
                'name' => $socialUser->name,
                'email' => $socialUser->email,
            ]);

            $providerUser->update(['user_id' => $user->id]);

            $user->assignRole($role);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
