<?php

use Wijourdil\Statically\CdnHelper;

function cdn(string $asset): string
{
    return (new CdnHelper())->generate($asset);
}
