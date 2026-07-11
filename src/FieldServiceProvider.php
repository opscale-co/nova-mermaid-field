<?php

declare(strict_types=1);

namespace Opscale\Fields;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

final class FieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Nova::serving(function (ServingNova $event): void {
            Nova::script('nova-mermaid-field', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-mermaid-field', __DIR__.'/../dist/css/field.css');
        });
    }

    public function register(): void
    {
        //
    }
}
