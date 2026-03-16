<?php

namespace App\Providers;

use App\Models\BitacoraIngreso;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerBitacoraIngresosListeners();
        $this->registerMailLoggerListeners();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    protected function registerBitacoraIngresosListeners(): void
    {
        Event::listen(Login::class, function (Login $event): void {
            $request = request();
            $user = $event->user;

            if (! $user instanceof User) {
                return;
            }

            BitacoraIngreso::create([
                'user_id' => $user->id,
                'nombre' => $user->nombre ?? $user->name,
                'email' => $user->email,
                'evento' => 'login',
                'session_id' => $request->session()->getId(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'ocurrio_en' => now(),
            ]);
        });

        Event::listen(Logout::class, function (Logout $event): void {
            $request = request();
            $user = $event->user;

            if (! $user instanceof User) {
                return;
            }

            BitacoraIngreso::create([
                'user_id' => $user->id,
                'nombre' => $user->nombre ?? $user->name,
                'email' => $user->email,
                'evento' => 'logout',
                'session_id' => $request->session()->getId(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'ocurrio_en' => now(),
            ]);
        });
    }

    protected function registerMailLoggerListeners(): void
    {
        Event::listen(MessageSending::class, function (MessageSending $event): void {
            Log::channel('mail')->info('Intento de envio de correo.', [
                'subject' => $event->message->getSubject(),
                'to' => $this->extractEmailAddresses($event->message->getTo()),
                'cc' => $this->extractEmailAddresses($event->message->getCc()),
                'bcc' => $this->extractEmailAddresses($event->message->getBcc()),
            ]);
        });

        Event::listen(MessageSent::class, function (MessageSent $event): void {
            Log::channel('mail')->info('Correo enviado correctamente.', [
                'subject' => $event->message->getSubject(),
                'to' => $this->extractEmailAddresses($event->message->getTo()),
                'cc' => $this->extractEmailAddresses($event->message->getCc()),
                'bcc' => $this->extractEmailAddresses($event->message->getBcc()),
            ]);
        });
    }

    /**
     * @param  array<int, \Symfony\Component\Mime\Address>|null  $addresses
     * @return array<int, string>
     */
    protected function extractEmailAddresses(?array $addresses): array
    {
        if (! $addresses) {
            return [];
        }

        return array_values(array_map(
            fn ($address) => $address->getAddress(),
            $addresses,
        ));
    }
}
