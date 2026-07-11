<?php

declare(strict_types=1);

namespace Workbench\App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Opscale\Fields\Mermaid;

/**
 * @extends resource<\Workbench\App\Models\Diagram>
 */
final class Diagram extends Resource
{
    public static string $model = \Workbench\App\Models\Diagram::class;

    public static $title = 'name';

    public static $search = ['id', 'name'];

    public static function authorizedToCreate(Request $request): bool
    {
        return true;
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Text::make('Name')->sortable()->rules('required', 'max:255'),

            Mermaid::make('Diagram', 'mermaid_path', 'public')
                ->prunable()
                ->deletable()
                ->height(500)
                ->zoomControls(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function filters(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }

    public function authorizedToView(Request $request): bool
    {
        return true;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return true;
    }

    public function authorizedToDelete(Request $request): bool
    {
        return true;
    }
}
