<?php

declare(strict_types=1);

namespace Opscale\Fields\Tests\Browser;

use Laravel\Dusk\Browser;
use Opscale\Fields\Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

final class MermaidDetailTest extends DuskTestCase
{
    #[Test]
    public function detail_view_renders_the_mermaid_viewer(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginToNova($browser)
                ->visit('/nova/resources/diagrams/1')
                ->waitFor('[data-testid="mermaid-detail"]', 15)
                ->waitFor('[data-testid="mermaid-viewer"]', 15)
                ->waitFor('[data-testid="mermaid-canvas"] svg', 15)
                ->assertPresent('[data-testid="mermaid-controls"]')
                ->assertPresent('[data-testid="mermaid-zoom-in"]')
                ->assertPresent('[data-testid="mermaid-zoom-out"]')
                ->assertPresent('[data-testid="mermaid-zoom-reset"]');
        });
    }

    #[Test]
    public function the_canvas_displays_nodes_from_the_mermaid_file(): void
    {
        $this->browse(function (Browser $browser): void {
            $this->loginToNova($browser)
                ->visit('/nova/resources/diagrams/1')
                ->waitFor('[data-testid="mermaid-canvas"] svg', 15)
                ->waitFor('[data-testid="mermaid-canvas"] svg .node', 15)
                ->assertSeeIn('[data-testid="mermaid-canvas"]', 'Review request')
                ->assertSeeIn('[data-testid="mermaid-canvas"]', 'Notify requester');
        });
    }
}
