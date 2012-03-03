<?php

namespace Marca\AuthenticateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MarcaAuthenticateBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
