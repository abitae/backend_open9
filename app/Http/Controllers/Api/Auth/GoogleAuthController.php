<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\RecordStatus;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\SocialLoginSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class GoogleAuthController extends Controller
{
    /**
     * Redirige a Google si el módulo está habilitado y configurado.
     */
    public function redirect(): SymfonyRedirectResponse|RedirectResponse
    {
        $settings = SocialLoginSetting::current();

        if (! $settings->googleEnabled()) {
            return redirect($this->frontendUrl('/ingresar?error=google_disabled'));
        }

        $this->configureDriver($settings);

        return $this->googleDriver()->redirect();
    }

    /**
     * Recibe el callback de Google, crea/busca el Client y emite un token.
     */
    public function callback(): RedirectResponse
    {
        $settings = SocialLoginSetting::current();

        if (! $settings->googleEnabled()) {
            return redirect($this->frontendUrl('/ingresar?error=google_disabled'));
        }

        $this->configureDriver($settings);

        try {
            $googleUser = $this->googleDriver()->user();
        } catch (\Throwable $exception) {
            Log::warning('Fallo en el callback de Google.', ['error' => $exception->getMessage()]);

            return redirect($this->frontendUrl('/ingresar?error=google_failed'));
        }

        $email = $googleUser->getEmail();

        if ($email === null || $email === '') {
            return redirect($this->frontendUrl('/ingresar?error=google_no_email'));
        }

        $client = Client::query()->where('google_id', $googleUser->getId())->first()
            ?? Client::query()->where('email', $email)->first();

        if ($client === null) {
            $client = new Client;
            $client->email = $email;
            $client->password = null;
            $client->status = RecordStatus::Active;
        }

        $client->name = $client->name ?: ($googleUser->getName() ?: Str::before($email, '@'));
        $client->google_id = $googleUser->getId();
        $client->avatar = $client->avatar ?: $googleUser->getAvatar();

        if ($client->email_verified_at === null) {
            $client->email_verified_at = now();
        }

        $client->last_login_at = now();
        $client->save();

        $token = $client->createToken('google')->plainTextToken;

        return redirect($this->frontendUrl('/auth/callback?token='.urlencode($token)));
    }

    /**
     * Devuelve el driver de Google como proveedor OAuth2 en modo stateless
     * (la API no usa sesión, así que evitamos la verificación de estado).
     */
    private function googleDriver(): AbstractProvider
    {
        $driver = Socialite::driver('google');

        abort_unless($driver instanceof AbstractProvider, 500, 'El proveedor de Google no está disponible.');

        return $driver->stateless();
    }

    private function configureDriver(SocialLoginSetting $settings): void
    {
        config([
            'services.google.client_id' => $settings->google_client_id,
            'services.google.client_secret' => $settings->resolvedGoogleClientSecret(),
            'services.google.redirect' => $settings->google_redirect_url ?: url('/api/auth/google/callback'),
        ]);
    }

    private function frontendUrl(string $path): string
    {
        $base = rtrim((string) (config('app.frontend_url') ?: config('app.url')), '/');

        return $base.'/'.ltrim($path, '/');
    }
}
