<?php

namespace Marca\FileBundle\Namer;

use Marca\FileBundle\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;


/**
 * OrignameNamer
 *
 * @author Ivan Borzenkov <ivan.borzenkov@gmail.com>
 */
class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * {@inheritDoc}
     */
    public function directoryName($object, PropertyMapping $mapping)
    {
        $courseid = $object->getCourse()->getId();
        $new_path = '/'.$courseid;
        return $new_path;
    }
}

