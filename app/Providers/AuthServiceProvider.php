<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Define Gates
        Gate::define('barn_owner', function (User $user) {
            return $user->user_type === 'barn_owner';
        });

        Gate::define('barn_staff', function (User $user) {
            if ($user->user_type !== 'barn_staff') {
                return false;
            }

            $barnStaff = $user->barnStaff()->first();
            return $barnStaff && $barnStaff->staff_status === 'active';
        });

        View::composer('*', function ($view) {
            if (!Auth::check()) {
                $view->with('currentBarn', null);
                $view->with('currentUser', null);
                return;
            }

            $user = Auth::user();
            $currentBarn = null;

            if ($user->user_type === 'barn_owner') {
                $currentBarn = $user->barnsAsOwner()->first();
            } elseif ($user->user_type === 'barn_staff') {
                $barnStaff = $user->barnStaff()->first();
                // Only load barn if staff is active
                if ($barnStaff && $barnStaff->staff_status === 'active') {
                    $currentBarn = $barnStaff->barn;
                }
            }

            $view->with('currentBarn', $currentBarn);
            $view->with('currentUser', $user);
        });
    }
}