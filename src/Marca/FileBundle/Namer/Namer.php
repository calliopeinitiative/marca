<?php

namespace Marca\FileBundle\Namer;

use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * Namer class.
 */
class Namer implements NamerInterface
{
    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $obj The object the upload is attached to.
     * @param string $field The name of the uploadable field to generate a name for.
     * @return string The file name.
     */

    public function name($obj, $field)
    {
        $refObj = new \ReflectionObject($obj);

        $refProp = $refObj->getProperty($field);
        $refProp->setAccessible(true);

        $file = $refProp->getValue($obj);

        $original_name = $file->getClientOriginalName();

        $name = uniqid();

        $extension = pathinfo($original_name, PATHINFO_EXTENSION);

        $name = sprintf('%s.%s', $name, $extension);

        return $name;
    }
}
