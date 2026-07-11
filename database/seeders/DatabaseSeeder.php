<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Workbench\App\Models\Diagram;
use Workbench\App\Models\User;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@laravel.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $disk = Storage::disk('public');
        $path = 'diagrams/sample.mmd';

        if (! $disk->exists($path)) {
            $disk->put($path, (string) file_get_contents(__DIR__.'/../../tests/fixtures/sample.mmd'));
        }

        Diagram::query()->firstOrCreate(
            ['name' => 'Sample process'],
            ['mermaid_path' => $path],
        );
    }
}
