<?php

namespace AshAllenDesign\ShortURL\Providers;

use Illuminate\Database\Eloquent\Model;
use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Models\ShortURL;
use AshAllenDesign\ShortURL\Classes\Validation;
use AshAllenDesign\ShortURL\Models\ShortURLVisit;
use AshAllenDesign\ShortURL\Exceptions\ShortURLException;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use Illuminate\Support\ServiceProvider;

class ShortURLProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/short-url.php', 'short-url');

        $this->app->bind('short-url.builder', function () {
            return new Builder();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function boot(): void
    {
        // Config
        $this->publishes([
            __DIR__.'/../../config/short-url.php' => config_path('short-url.php'),
        ], 'short-url-config');

        // Migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'short-url-migrations');

        // Routes
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');

        if (config('short-url') && config('short-url.validate_config')) {
            (new Validation())->validateConfig();
        }
    }

    public static function determineShortURLModel(): string
    {
        $shortURLModel = config('short-url.short_url_model') ?? ShortURL::class;

        if (!is_a($shortURLModel, ShortURL::class, true)
            || !is_a($shortURLModel, Model::class, true)) {
            throw new ShortURLException($shortURLModel);
        }

        return $shortURLModel;
    }

    public static function getShortURLModelInstance(): ShortURL
    {
        $shortURLModelClassName = self::determineShortURLModel();

        return new $shortURLModelClassName();
    }

    public static function determineShortURLVisitModel(): string
    {
        $shortURLVisitModel = config('short-url.short_url_visit_model') ?? ShortURLVisit::class;

        if (!is_a($shortURLVisitModel, ShortURLVisit::class, true)
            || !is_a($shortURLVisitModel, Model::class, true)) {
            throw new ShortURLException($shortURLVisitModel);
        }

        return $shortURLVisitModel;
    }

    public static function getShortURLVisitModelInstance(): ShortURLVisit
    {
        $shortURLVisitModelClassName = self::determineShortURLVisitModel();

        return new $shortURLVisitModelClassName();
    }
}
