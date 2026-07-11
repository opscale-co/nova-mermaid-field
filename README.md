## Support us

At Opscale, we’re passionate about contributing to the open-source community by providing solutions that help businesses scale efficiently. If you’ve found our tools helpful, here are a few ways you can show your support:

⭐ **Star this repository** to help others discover our work and be part of our growing community. Every star makes a difference!

💬 **Share your experience** by leaving a review on [Trustpilot](https://www.trustpilot.com/review/opscale.co) or sharing your thoughts on social media. Your feedback helps us improve and grow!

📧 **Send us feedback** on what we can improve at [feedback@opscale.co](mailto:feedback@opscale.co). We value your input to make our tools even better for everyone.

🙏 **Get involved** by actively contributing to our open-source repositories. Your participation benefits the entire community and helps push the boundaries of what’s possible.

💼 **Hire us** if you need custom dashboards, admin panels, internal tools or MVPs tailored to your business. With our expertise, we can help you systematize operations or enhance your existing product. Contact us at hire@opscale.co to discuss your project needs.

Thanks for helping Opscale continue to scale! 🚀



## Description

Mermaid diagrams usually live in docs, wikis, and README files — rarely inside the apps they describe. This package closes that gap: upload a `.mmd` file from any Nova resource and the rendered diagram shows up right on the record, read-only, with zoom controls — powered by [`mermaid`](https://mermaid.js.org). Compatible with Nova 5.

## Installation

[![Latest Version on Packagist](https://img.shields.io/packagist/v/opscale-co/nova-mermaid-field.svg?style=flat-square)](https://packagist.org/packages/opscale-co/nova-mermaid-field)

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require opscale-co/nova-mermaid-field
```

The package will auto-register its service provider.

Back your field with a `string` column on the owning model — it stores the disk path of the uploaded `.mmd`. The file itself lives on the Laravel disk of your choice (typically `public`).

```php
Schema::create('diagrams', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('mermaid_path')->nullable();
    $table->timestamps();
});
```

Make sure the disk is publicly readable (for the default `public` disk: `php artisan storage:link`).

## Usage

Add the `Mermaid` field to any Nova Resource that owns a Mermaid attribute:

```php
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Opscale\Fields\Mermaid;

class Diagram extends Resource
{
    public static $model = \App\Models\Diagram::class;

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->rules('required', 'max:255'),

            Mermaid::make('Diagram', 'mermaid_path', 'public')
                ->prunable()
                ->deletable()
                ->height(600)
                ->theme('default')
                ->zoomControls(),
        ];
    }
}
```

On **Create / Update** the field renders Nova’s native File dropzone — pick a `.mmd` or `.mermaid` file, it is uploaded to the configured disk and the path is persisted. On **Detail / Lens** the field fetches the file from the disk and renders the diagram. The diagram is **not editable** — this is a renderer, not an editor. It is intentionally **hidden from Index**.

### Field behavior

| View | Behavior |
|---|---|
| Create / Update | Nova’s native File field dropzone (`.mmd`, `.mermaid`). Inherited storage, deletion, and `->prunable()` semantics. |
| Detail / Lens | Read-only `mermaid` renderer with zoom in / out / reset controls. |
| Index | Hidden — a full diagram inside a table cell adds no decision-making value. |

### Modifiers

| Method | Purpose |
|---|---|
| `->height(int $pixels)` | Canvas height in pixels (default `600`). |
| `->theme(string $theme)` | Mermaid theme: `default`, `dark`, `forest`, `neutral`, `base` (default `default`). |
| `->zoomControls(bool $enabled = true)` | Show the in-canvas zoom in / out / reset buttons. |
| `->prunable()` / `->deletable()` | Inherited from `Laravel\Nova\Fields\File` — remove the stored file when the record is deleted or the field is cleared. |

### Under the hood

| Layer | Dependency |
|---|---|
| Upload / storage | `Laravel\Nova\Fields\File` — disk-backed upload, deletion, prunable. |
| Renderer | [`mermaid`](https://mermaid.js.org) — `mermaid.render()` with `securityLevel: 'strict'`. |

The PHP field class is a thin subclass of Nova’s `File`; the Vue `form` and `index` slots alias `form-file-field` and `index-file-field` verbatim, so there is no custom upload UI to maintain. Only the `detail` slot is custom — it reads `field.previewUrl`, fetches the diagram source, and renders it to SVG.

## Testing

```bash

npm run test

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/opscale-co/.github/blob/main/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email development@opscale.co instead of using the issue tracker.

## Credits

- [Opscale](https://github.com/opscale-co)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
