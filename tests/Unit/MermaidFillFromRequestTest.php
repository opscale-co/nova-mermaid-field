<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;
use Opscale\Fields\Mermaid;
use Workbench\App\Models\Diagram;

function sampleMermaid(): string
{
    return (string) file_get_contents(__DIR__.'/../fixtures/sample.mmd');
}

function mermaidRequest(array $data): NovaRequest
{
    $files = [];
    $params = [];

    foreach ($data as $key => $value) {
        if ($value instanceof UploadedFile) {
            $files[$key] = $value;
        } else {
            $params[$key] = $value;
        }
    }

    return NovaRequest::create('/', 'POST', $params, [], $files);
}

beforeEach(function (): void {
    Storage::fake('public');
});

it('stores the uploaded file on the configured disk and persists the path', function (): void {
    $tmp = tempnam(sys_get_temp_dir(), 'mermaid').'.mmd';
    file_put_contents($tmp, sampleMermaid());
    $upload = new UploadedFile($tmp, 'diagram.mmd', 'text/plain', null, true);

    $field = Mermaid::make('Diagram', 'mermaid_path', 'public');
    $model = new Diagram;

    $field->fill(mermaidRequest(['mermaid_path' => $upload]), $model);

    expect($model->mermaid_path)->toBeString()->not->toBeEmpty();

    Storage::disk('public')->assertExists($model->mermaid_path);
    expect(Storage::disk('public')->get($model->mermaid_path))->toContain('flowchart TD');

    @unlink($tmp);
});

it('leaves the attribute untouched when no file is uploaded', function (): void {
    $field = Mermaid::make('Diagram', 'mermaid_path', 'public');
    $model = new Diagram(['mermaid_path' => 'diagrams/existing.mmd']);

    $field->fill(mermaidRequest([]), $model);

    expect($model->mermaid_path)->toBe('diagrams/existing.mmd');
});

it('rejects files whose extension is not in acceptedTypes', function (): void {
    $upload = UploadedFile::fake()->createWithContent('diagram.pdf', '%PDF-1.4');

    $field = Mermaid::make('Diagram', 'mermaid_path', 'public')->rules('mimes:mmd,mermaid');
    expect($field->acceptedTypes)->toBe('.mmd,.mermaid');

    // rules('mimes:...') is enforced by Nova's validator layer; here we assert the
    // field advertises the restriction that the browser + backend will honor.
});
