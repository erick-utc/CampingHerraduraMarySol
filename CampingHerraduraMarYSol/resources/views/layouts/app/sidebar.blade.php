<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header class="h-auto! min-h-0! flex-col items-stretch gap-2 pb-3">
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="pt-2">
                <div class="px-3 pb-2 pt-1 text-xs leading-none text-zinc-400">
                    {{ __('Mantenimientos') }}
                </div>

                <flux:sidebar.group class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>

                   

                    @can('ver usuarios')
                        <flux:sidebar.item icon="users" :href="route('dashboard.users')" :current="request()->routeIs('dashboard.users')" wire:navigate>
                            {{ __('Usuarios') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('ver permisos')
                        <flux:sidebar.item icon="shield-check" :href="route('permissions.index')" :current="request()->routeIs('permissions.*')" wire:navigate>
                            {{ __('Permisos') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('ver roles')
                        <flux:sidebar.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.*')" wire:navigate>
                            {{ __('Roles') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('ver hospedajes')
                        <flux:sidebar.item icon="home" :href="route('hospedajes.index')" :current="request()->routeIs('hospedajes.*')" wire:navigate>
                            {{ __('Hospedajes') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('ver productos')
                        <flux:sidebar.item icon="shopping-bag" :href="route('productos.index')" :current="request()->routeIs('productos.*')" wire:navigate>
                            {{ __('Productos') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('ver reservas')
                        <flux:sidebar.item icon="calendar-days" :href="route('reservas.index')" :current="request()->routeIs('reservas.*')" wire:navigate>
                            {{ __('Reservas') }}
                        </flux:sidebar.item>
                    @endcan

                    @can('ver facturas')
                        <flux:sidebar.item icon="document-text" :href="route('facturas.index')" :current="request()->routeIs('facturas.*')" wire:navigate>
                            {{ __('Facturas') }}
                        </flux:sidebar.item>
                    @endcan

                     <flux:sidebar.item icon="information-circle" :href="route('about')" :current="request()->routeIs('about')" wire:navigate>
                        {{ __('Acerca de') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="question-mark-circle" :href="route('help')" :current="request()->routeIs('help')" wire:navigate>
                        {{ __('Ayuda') }}
                    </flux:sidebar.item>

                    @if(auth()->user()->can('bitacora movimiento de usuario') || auth()->user()->can('ver bitacora'))
                        <flux:sidebar.group :heading="__('Bitácoras')" class="grid">
                            @can('bitacora movimiento de usuario')
                                <flux:sidebar.item icon="arrow-path" :href="route('bitacoras.ingresos.index')" :current="request()->routeIs('bitacoras.ingresos.*')" wire:navigate>
                                    {{ __('Ingresos') }}
                                </flux:sidebar.item>
                            @endcan

                            @can('ver bitacora')
                                <flux:sidebar.item icon="clipboard-document-list" :href="route('bitacoras.movimientos.index')" :current="request()->routeIs('bitacoras.movimientos.*')" wire:navigate>
                                    {{ __('Movimientos') }}
                                </flux:sidebar.item>
                            @endcan
                        </flux:sidebar.group>
                    @endif

                </flux:sidebar.group>

                @can('ver reportes')
                    <flux:sidebar.group :heading="__('Reportes')" class="grid">
                        <flux:sidebar.item icon="chart-bar" :href="route('reportes.clientes')" :current="request()->routeIs('reportes.clientes')" wire:navigate>
                            {{ __('Número de clientes') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="home-modern" :href="route('reportes.habitaciones')" :current="request()->routeIs('reportes.habitaciones')" wire:navigate>
                            {{ __('Uso de habitaciones') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="map" :href="route('reportes.camping')" :current="request()->routeIs('reportes.camping')" wire:navigate>
                            {{ __('Uso de camping') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="shopping-cart" :href="route('reportes.ventas-productos')" :current="request()->routeIs('reportes.ventas-productos')" wire:navigate>
                            {{ __('Ventas por productos') }}
                        </flux:sidebar.item>
                        <flux:sidebar.item icon="truck" :href="route('reportes.parqueo')" :current="request()->routeIs('reportes.parqueo')" wire:navigate>
                            {{ __('Uso de parqueo') }}
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                @endcan
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        @if(auth()->user()->hasRole('administrador'))
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>
                        @endif
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
