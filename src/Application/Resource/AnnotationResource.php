<?php

namespace Application\Resource;

trait AnnotationResource
{
    /**
     * @return \Doctrine\Common\Annotations\AnnotationReader
     */
    public function getAnnotationReader()
    {
        if (!ResourceMemory::hasKey('annotation')) {
            $annotationReader = new \Doctrine\Common\Annotations\AnnotationReader();
            ResourceMemory::set('annotation', $annotationReader);
        }

        return ResourceMemory::get('annotation');
    }
}
