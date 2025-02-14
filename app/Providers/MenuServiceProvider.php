<?php

namespace App\Providers;

use App\Models\Menu;
use App\Services\Notifications\GetNotificationForCurrentUserService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        if (Schema::hasTable('menus')) {
            view()->composer(['layouts.sections.menu.verticalMenu', 'layouts.sections.menu.horizontalMenu'], function ($view) {
                if (Auth::check()) {
                    // Share all menuData to all the views
                    $menuId = Auth::user()->menus->pluck('id')->toArray();
                    $menu = Menu::query()->whereIn('id', $menuId)
                        ->orderBy('_lft')
                        ->orderBy('_rgt')
                        ->get()->toTree();
                    $view->with('menuData', $menu);
                } else {
                    $view->with('menuData', null);
                }
            });
        }
    }
}
