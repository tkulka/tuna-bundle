<?php

namespace TheCodeine\CategoryBundle\Form\DataTransformer;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;

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
        dump('transform');
        dump($data);
        if ($data === null || $data === '') {
            return '';
        }

        return array('choice' => $data, 'new_value' => 'dupa');
    }

    public function reverseTransform($data)
    {
        dump('reverse');
        dump($data);

        return $data['choice'];
    }
}
