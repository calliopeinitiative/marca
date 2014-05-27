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
        $upload_dir = $mapping->getUploadDestination();
        $courseid = $object->getCourse()->getId();
        $new_path = $upload_dir.'/'.$courseid;
        return $new_path;
    }
}

