<?php

namespace Marca\CasBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarcaCasBundle extends Bundle
{
    public function getParent()
    {
        return 'BeSimpleSsoAuthBundle';
    }
}
