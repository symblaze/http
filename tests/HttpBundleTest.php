<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests;

use Symblaze\Bundle\Http\DependencyInjection\HttpBundleExtension;
use Symblaze\Bundle\Http\HttpBundle;

final class HttpBundleTest extends TestCase
{
    /** @test */
    public function it_loads_http_bundle_extension(): void
    {
        $sut = new HttpBundle();

        $actual = $sut->getContainerExtension();

        $this->assertInstanceOf(HttpBundleExtension::class, $actual);
    }
}
