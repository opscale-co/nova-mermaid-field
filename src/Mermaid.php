<?php

declare(strict_types=1);

namespace Opscale\Fields;

use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\File;
use Override;

class Mermaid extends File
{
    public $component = 'nova-mermaid-field';

    public $showOnIndex = false;

    public $acceptedTypes = '.mmd,.mermaid';

    /**
     * @param  \Stringable|string  $name
     */
    final public function __construct($name, mixed $attribute = null, ?string $disk = null, ?callable $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->preview(fn (?string $value, ?string $diskName): ?string => $value !== null && $value !== ''
            ? Storage::disk($diskName ?? $this->getStorageDisk())->url($value)
            : null,
        );
    }

    final public function height(int $pixels): self
    {
        return $this->withMeta(['height' => $pixels]);
    }

    final public function theme(string $theme): self
    {
        return $this->withMeta(['theme' => $theme]);
    }

    final public function zoomControls(bool $enabled = true): self
    {
        return $this->withMeta(['zoomControls' => $enabled]);
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function meta()
    {
        return array_merge(parent::meta(), [
            'height' => 600,
            'theme' => 'default',
            'zoomControls' => true,
        ], $this->meta);
    }
}
