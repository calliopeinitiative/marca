<?php

namespace Marca\FileBundle\Namer;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

/**
 * Namer class.
 */
class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $obj The object the upload is attached to.
     * @param string $field The name of the uploadable field to generate a name for.
     * @return string The file name.
     */
    function directoryName($object, PropertyMapping $mapping)
    {
        $course = $object->getCourse();
        $courseid = $course->getID();
        return $courseid;
    }
}

