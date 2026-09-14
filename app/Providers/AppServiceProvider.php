<?php

namespace App\Providers;

use App\Services\SpellHistory\SpellHistoryRepository;
use App\Services\PatchArchive;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SpellHistoryRepository::class, function (): SpellHistoryRepository {
            return new SpellHistoryRepository(
                (string) config('everquest.spell_history.artifact_path'),
            );
        });
        if (config('everquest.patch_history.enable', true)) {
            $this->app->singleton(PatchArchive::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('layouts.partials.pagination');
    }
}
