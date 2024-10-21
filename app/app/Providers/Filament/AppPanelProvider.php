<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Pages\Registration;
use App\Filament\Resources\AdmissionSettingResource;
use App\Filament\Resources\ProgramResource;
use App\Filament\Resources\StudentResource;
use App\Filament\Resources\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use SolutionForest\FilamentTranslateField\FilamentTranslateFieldPlugin;
use Filament\Navigation\MenuItem;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('member')
            ->path('member')
            ->spa()
            ->login()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Slate,
                'info' => Color::Cyan,
                'primary' => Color::Teal,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->favicon(asset('images/logo_erudify.ico'))
            ->brandLogo(fn () => view('vendor.filament.components.logo'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Registration::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make(), FilamentTranslateFieldPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $isStudent = auth()->user()->hasRole('student');
                if(!$isStudent) {
                  return $builder->groups([
                      NavigationGroup::make('Admin')
                        ->items([
                          ...(AdmissionSettingResource::shouldRegisterNavigation() ? AdmissionSettingResource::getNavigationItems() : []),
                          ...(StudentResource::shouldRegisterNavigation() ? StudentResource::getNavigationItems() : []),
                          NavigationItem::make('Beranda')
                            ->icon('heroicon-o-home')
                            ->url('/'),
                        ]),
                    ])
                    ->groups([
                      NavigationGroup::make('User Permission')
                        ->items([
                          ...(UserResource::shouldRegisterNavigation() ? UserResource::getNavigationItems() : []),
                          ...(auth()->user()->can('view-any Role') ? [
                            NavigationItem::make('Roles')
                              ->icon('heroicon-o-wrench-screwdriver')
                              ->isActiveWhen(fn(): bool => request()->routeIs([
                                'filament.app.resources.roles.index',
                                'filament.app.resources.roles.create',
                                'filament.app.resources.roles.view',
                                'filament.app.resources.roles.edit',
                              ]))
                              ->url(fn(): string => '/member/roles'),
                          ] : []),
                          ...(auth()->user()->can('view-any Permission') ? [
                            NavigationItem::make('Permission')
                              ->icon('heroicon-o-lock-closed')
                              ->isActiveWhen(fn(): bool => request()->routeIs([
                                'filament.app.resources.permissions.index',
                                'filament.app.resources.permissions.create',
                                'filament.app.resources.permissions.view',
                                'filament.app.resources.permissions.edit',
                              ]))
                              ->url(fn(): string => '/member/permissions'),
                          ] : []),
                        ]),
                    ]);
                } else {
                  return $builder->items([
                    NavigationItem::make('Pendaftaran')
                      ->icon('heroicon-o-home')
                      ->isActiveWhen(fn(): bool => request()->routeIs('filament.app.pages.registration'))
                      ->url(fn(): string => Registration::getUrl())
                      ->visible($isStudent),
                    NavigationItem::make('Beranda')
                      ->icon('heroicon-o-home')
                      ->url('/'),
                  ]);
                }

              })
            ->userMenuItems([
                MenuItem::make()
                    ->label('Beranda')
                    ->url('/')
                    ->icon('heroicon-o-home'),
                // ...
            ]);;
    }
}