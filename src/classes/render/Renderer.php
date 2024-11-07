<?php
declare(strict_types=1);

namespace iutnc\deefy\render;
interface Renderer{
    const COMPACT = 1;
    const LONG = 2;

    public function render(int $type): string;
}

