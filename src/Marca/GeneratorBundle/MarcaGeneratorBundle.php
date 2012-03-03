<?php

namespace Marca\GeneratorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarcaGeneratorBundle extends Bundle
{
    public function getParent()
    {
        return 'SensioGeneratorBundle';
    }
}
