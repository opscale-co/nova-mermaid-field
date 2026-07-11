<?php

declare(strict_types=1);

namespace Opscale\Fields\Tests\Browser;

use Laravel\Dusk\Browser;
use Opscale\Fields\Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

final class MermaidCreateTest extends DuskTestCase
{
    #[Test]
    public function create_form_renders_a_file_upload_input_for_the_mermaid_field(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginToNova($browser)
                ->visit('/nova/resources/diagrams/new')
                ->waitForText('Create Diagram', 15)
                ->waitFor('[data-testid="mermaid-form-field"]', 15)
                ->waitFor('[data-testid="mermaid-form-field"] input[type="file"]', 15)
                ->assertAttribute(
                    '[data-testid="mermaid-form-field"] input[type="file"]',
                    'accept',
                    '.mmd,.mermaid'
                );
        });
    }

    #[Test]
    public function update_form_also_renders_the_file_upload_input(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginToNova($browser)
                ->visit('/nova/resources/diagrams/1/edit')
                ->waitForText('Update Diagram', 15)
                ->waitFor('[data-testid="mermaid-form-field"]', 15)
                ->waitFor('[data-testid="mermaid-form-field"] input[type="file"]', 15)
                ->assertPresent('[data-testid="mermaid-form-field"] input[type="file"]');
        });
    }

    #[Test]
    public function submitting_the_form_uploads_the_file_and_renders_it_on_detail(): void
    {
        $fixture = (string) realpath(__DIR__.'/../fixtures/sample.mmd');

        $this->browse(function (Browser $browser) use ($fixture): void {
            $this->loginToNova($browser)
                ->visit('/nova/resources/diagrams/new')
                ->waitForText('Create Diagram', 15)
                ->waitFor('@name', 15)
                ->type('@name', 'Uploaded via Dusk')
                ->waitFor('[data-testid="mermaid-form-field"] input[type="file"]', 15)
                ->attach('[data-testid="mermaid-form-field"] input[type="file"]', $fixture)
                ->pause(300)
                ->press('Create Diagram')
                // Nova redirects to /nova/resources/diagrams/{id} on successful save.
                ->waitForText('Diagram Details: Uploaded via Dusk', 15)
                // If mermaid_path was persisted AND the file was uploaded, the detail
                // page fetches it and the viewer renders — end-to-end proof.
                ->waitFor('[data-testid="mermaid-canvas"] svg', 15)
                ->assertSeeIn('[data-testid="mermaid-canvas"]', 'Review request');
        });
    }
}
