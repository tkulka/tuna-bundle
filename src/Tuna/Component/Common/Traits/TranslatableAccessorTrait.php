<?php

namespace TunaCMS\CommonComponent\Traits;

use Symfony\Component\PropertyAccess\PropertyAccess;

trait TranslatableAccessorTrait
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    static protected $propertyAccessor;

    public function __call($name, array $arguments = [])
    {
        if (self::$propertyAccessor == null) {
            self::$propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        $count = count($arguments);
        $translate = $this->translate();
        if ($count > 0 || 'set' == substr($name, 0, 3)) {
            if ($count !== 1) {
                throw new \RuntimeException(sprintf('The "%s" method requires exactly one argument.', $name));
            }

            self::$propertyAccessor->setValue($translate, $name, $arguments[0]);

            return $this;
        }

        return self::$propertyAccessor->getValue($translate, $name);
    }
}