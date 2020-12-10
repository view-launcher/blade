<?php

namespace Tests\Unit;

use Tests\TestCase;
use ViewLauncher\Blade\SourceMapper;

class SourceMapperTest extends TestCase
{
    /**
     * @test
     */
    public function it_ignore_slot_tag()
    {
        $content = <<<BLADE
<x-section>
    <x-slot name='sidebar'>
        foobar
    </x-slot>
</x-section>
BLADE;

        $sourceMapper = $this->makeSourceMapper($content);
        $compiled = $sourceMapper->compile();

        $this->assertStringContainsString('<x-slot name="sidebar">', $compiled);
    }

    /**
     * @test
     */
    public function it_ignore_end_tag()
    {
        $content = <<<BLADE
<section>
foobar
</section>
BLADE;

        $sourceMapper = $this->makeSourceMapper($content);
        $compiled = $sourceMapper->compile();

        $this->assertStringContainsString('</section>', $compiled);
    }

    protected function makeSourceMapper(string $content): SourceMapper
    {
        return new SourceMapper($content, __DIR__ . '/content.blade.php');
    }
}
