<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http;

use Symblaze\Bundle\Http\DependencyInjection\HttpBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class HttpBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new HttpBundleExtension();
    }
}
