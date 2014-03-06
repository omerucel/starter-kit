<?php

namespace MiniFrame\Extra\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use MiniFrame\BaseService;

class AnnotationReaderService extends BaseService
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * @return AnnotationReader
     */
    public function getAnnotationReader()
    {
        if ($this->annotationReader == null) {
            $this->annotationReader = new AnnotationReader();
        }

        return $this->annotationReader;
    }
}
