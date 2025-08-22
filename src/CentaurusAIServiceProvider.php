<?php

namespace Mecomedia\CentaurusAI;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class CentaurusAIServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Config zusammenführen
        $this->mergeConfigFrom(__DIR__ . '/../config/centaurus-ai.php', 'centaurus-ai');

        // Hauptklasse als Singleton im Container
        $this->app->singleton(CentaurusAI::class, function ($app) {
            return new CentaurusAI(config('centaurus-ai'));
        });

        // Optional: Schlüssel "centaurus-ai" für Resolve
        $this->app->alias(CentaurusAI::class, 'centaurus-ai');

        $this->mergeConfigFrom(__DIR__.'/../config/anthropic.php', 'anthropic');
        $this->mergeConfigFrom(__DIR__.'/../config/gemini.php', 'gemini');
        $this->mergeConfigFrom(__DIR__.'/../config/ionos.php', 'ionos');
        $this->mergeConfigFrom(__DIR__.'/../config/mistral.php', 'mistral');
        $this->mergeConfigFrom(__DIR__.'/../config/google-api.php', 'google-api');
        $this->mergeConfigFrom(__DIR__.'/../config/openai.php', 'openai');
    }

    public function boot(): void
    {
        // Config publishen
        $this->publishes([
            __DIR__ . '/../config/centaurus-ai.php' => config_path('centaurus-ai.php'),
        ], 'centaurus-ai-config');
    }
}