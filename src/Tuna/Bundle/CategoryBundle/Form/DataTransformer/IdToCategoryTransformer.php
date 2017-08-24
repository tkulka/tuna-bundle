<?php

namespace TunaCMS\Bundle\CategoryBundle\Form\DataTransformer;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;
use TunaCMS\Bundle\CategoryBundle\Form\Type\AddableCategoryType;

class IdToCategoryTransformer implements DataTransformerInterface
{
    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * IdToCategoryTransformer constructor.
     *
     * @param EntityRepository $entityRepository
     */
    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($data)
    {
        $result = [
            AddableCategoryType::CHOICE_FIELD => null,
            AddableCategoryType::NEW_VALUE_FIELD => null
        ];

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

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data)
    {
        if ($data[AddableCategoryType::CHOICE_FIELD] == AddableCategoryType::NEW_VALUE_OPTION) {
            return $data[AddableCategoryType::NEW_VALUE_FIELD];
        } else {
            if (!$data[AddableCategoryType::CHOICE_FIELD]) {
                return null;
            }
            return $this->entityRepository->find($data[AddableCategoryType::CHOICE_FIELD]);
        }
    }
}
