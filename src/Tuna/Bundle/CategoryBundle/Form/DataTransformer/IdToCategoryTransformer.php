<?php

namespace TheCodeine\CategoryBundle\Form\DataTransformer;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use TheCodeine\CategoryBundle\Form\Type\AddableCategoryType;

class IdToCategoryTransformer implements DataTransformerInterface
{
    /**
     * @var EntityRepository
     */
    private $repo;

    /**
     * IdToCategoryTransformer constructor.
     */
    public function __construct(EntityRepository $repo)
    {
        $this->repo = $repo;
    }

    public function transform($data)
    {
        $result = array(
            AddableCategoryType::CHOICE_FIELD => null,
            AddableCategoryType::NEW_VALUE_FIELD => null
        );

        if ($data === null || $data === '') {
            return $result;
        }

        if ($data->getId()) {
            $result[AddableCategoryType::CHOICE_FIELD] = $data->getId();
        } else {
            $result[AddableCategoryType::NEW_VALUE_FIELD] = $data;
        }

        return $result;
    }

    public function reverseTransform($data)
    {
        if ($data[AddableCategoryType::CHOICE_FIELD] == AddableCategoryType::NEW_VALUE_OPTION) {
            return $data[AddableCategoryType::NEW_VALUE_FIELD];
        } else {
            return $this->repo->find($data[AddableCategoryType::CHOICE_FIELD]);
        }
    }
}
