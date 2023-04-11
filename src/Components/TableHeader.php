<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PostMount;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent('table_header')]
class TableHeader
{

    public bool $visible;
    public string $direction;
    public string $name;
    public string $label;
    public string $field;


    public function __construct()
    {
    }
}
