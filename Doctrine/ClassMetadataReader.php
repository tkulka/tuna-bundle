<?php

namespace TunaCMS\AdminBundle\Doctrine;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Column;

class ClassMetadataReader
{
    protected $annotationReader;

    protected $classTypesMap = [];

    public function __construct()
    {
        $this->annotationReader = new AnnotationReader();
    }

    public function getPropertyType($entity, $property)
    {
        if (!$entity) {
            return null;
        }

        $key = get_class($entity).'//'.$property;

        if (!array_key_exists($key, $this->classTypesMap)) {
            $reflection = new \ReflectionClass($entity);
            $annotations = $this->annotationReader->getPropertyAnnotations($reflection->getProperty($property));
            $this->classTypesMap[$key] = null;

            foreach ($annotations as $annotation) {
                if ($annotation instanceof Column) {
                    $this->classTypesMap[$key] = $annotation->type;
                    break;
                }
            }
        }

        return $this->classTypesMap[$key];
    }
}
