<?php

declare(strict_types=1);

use Laravel\Nova\Fields\File;
use Opscale\Fields\Mermaid;

it('uses the nova-mermaid-field Vue component', function (): void {
    $field = Mermaid::make('Diagram', 'mermaid_path');

    expect($field->component)->toBe('nova-mermaid-field');
});

it('is hidden from the index view', function (): void {
    $field = Mermaid::make('Diagram', 'mermaid_path');

    expect($field->showOnIndex)->toBeFalse();
});

it('inherits Laravel Nova File behavior', function (): void {
    $field = Mermaid::make('Diagram', 'mermaid_path');

    expect($field)->toBeInstanceOf(File::class);
});

it('advertises .mmd and .mermaid as accepted upload types', function (): void {
    $field = Mermaid::make('Diagram', 'mermaid_path');

    expect($field->acceptedTypes)->toBe('.mmd,.mermaid');
});

it('exposes viewer defaults via meta()', function (): void {
    $meta = Mermaid::make('Diagram', 'mermaid_path')->meta();

    expect($meta)
        ->toHaveKey('height', 600)
        ->toHaveKey('theme', 'default')
        ->toHaveKey('zoomControls', true);
});

it('overrides viewer defaults through chainable modifiers', function (): void {
    $meta = Mermaid::make('Diagram', 'mermaid_path')
        ->height(900)
        ->theme('dark')
        ->zoomControls(false)
        ->meta();

    expect($meta)
        ->toHaveKey('height', 900)
        ->toHaveKey('theme', 'dark')
        ->toHaveKey('zoomControls', false);
});

it('serializes the File-level meta keys expected by the form-file-field component', function (): void {
    $field = Mermaid::make('Diagram', 'mermaid_path');

    $json = $field->jsonSerialize();

    expect($json)
        ->toHaveKey('component', 'nova-mermaid-field')
        ->toHaveKey('attribute', 'mermaid_path')
        ->toHaveKey('acceptedTypes', '.mmd,.mermaid')
        ->toHaveKeys(['previewUrl', 'thumbnailUrl', 'downloadable', 'deletable']);
});
