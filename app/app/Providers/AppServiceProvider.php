<?php

namespace App\Providers;

use App\Filament\Pages\Registration;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        Model::unguard();
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);

        FilamentColor::register([
            'danger' => Color::Rose,
            'gray' => Color::Slate,
            'info' => Color::Cyan,
            'primary' => Color::Blue,
            'success' => Color::Emerald,
            'warning' => Color::Orange,
          ]);

          Filament::registerPages([
            Registration::class
          ]);
    }
}
