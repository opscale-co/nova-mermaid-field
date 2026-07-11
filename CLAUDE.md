# nova-mermaid-field

Read-only Mermaid diagram renderer field for Laravel Nova 5. The PHP field
(`src/Mermaid.php`) is a thin subclass of `Laravel\Nova\Fields\File`: forms
upload a `.mmd`/`.mermaid` file with Nova's native dropzone, detail views fetch
the stored file and render it with `mermaid` (see
`resources/js/components/MermaidViewer.vue`). The field is hidden on index.

## Commands

- `npm run production` — build `dist/` assets (laravel-mix).
- `composer serve` — build the Testbench workbench and serve Nova at
  http://127.0.0.1:8000/nova (admin@laravel.com / password).
- `composer test` / `npm run test` — Unit + Feature tests (Pest).
- `composer test:web` — Dusk browser tests (builds the Dusk skeleton first).
- `npm run quality` — lint (duster) + phpstan + tests.

## Structure

- `src/` — field class + service provider (`Opscale\Fields`).
- `resources/js/components/` — `FormField` (aliases `form-file-field`),
  `DetailField` (fetches `field.previewUrl`, mounts `MermaidViewer`),
  `IndexField`, `MermaidViewer`.
- `workbench/` — demo app (Diagram model/resource) used by `composer serve`
  and Dusk.
- `tests/fixtures/sample.mmd` — flowchart fixture used by tests and seeder.
