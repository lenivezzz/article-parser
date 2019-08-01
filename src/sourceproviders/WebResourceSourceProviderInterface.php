<?php
declare(strict_types=1);

namespace php_part\sourceproviders;

use php_part\exceptions\WebPageSourceProviderException;

interface WebResourceSourceProviderInterface
{
    /**
     * @param string $url
     * @return string
     * @throws WebPageSourceProviderException
     */
    public function provide(string $url) : string;
}
