<?php

namespace App\Http\Middleware;

use App\Models\BitacoraMovimiento;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogUserMovement
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! Auth::check()) {
            return $response;
        }

        if ($request->is('livewire/*') || $request->is('up')) {
            return $response;
        }

        $user = Auth::user();
        if (! $user) {
            return $response;
        }
        $route = $request->route();

        $routeName = $route?->getName();
        $path = $request->path();
        $method = strtoupper($request->method());

        $modulo = null;
        if ($routeName) {
            $modulo = explode('.', $routeName)[0] ?? null;
        }

        if (! $modulo) {
            $segments = explode('/', trim($path, '/'));
            $modulo = $segments[0] ?? 'sistema';
        }

        $routeParams = $route?->parameters() ?? [];
        $entidad = null;
        $entidadId = null;

        foreach ($routeParams as $paramName => $paramValue) {
            $entidad = $paramName;
            if (is_object($paramValue) && method_exists($paramValue, 'getKey')) {
                $entidadId = $paramValue->getKey();
            } else {
                $entidadId = is_scalar($paramValue) ? (int) $paramValue : null;
            }
            break;
        }

        BitacoraMovimiento::create([
            'user_id' => $user->id,
            'nombre' => $user->nombre ?? $user->name,
            'email' => $user->email,
            'modulo' => $modulo,
            'accion' => $method,
            'entidad' => $entidad,
            'entidad_id' => $entidadId,
            'descripcion' => "{$method} {$path}",
            'metadata' => [
                'route_name' => $routeName,
                'path' => $path,
                'status' => $response->getStatusCode(),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'ocurrio_en' => now(),
        ]);

        return $response;
    }
}
