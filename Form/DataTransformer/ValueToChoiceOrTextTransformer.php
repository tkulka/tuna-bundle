<?php

namespace TheCodeine\AdminBundle\Form\DataTransformer;

use TheCodeine\AdminBundle\Form\Type\ExtendableSelectType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ValueToChoiceOrTextTransformer implements DataTransformerInterface
{
    /**
     * @var array
     */
    private $choices;

    /**
     * ValueToChoiceOrTextTransformer constructor.
     */
    public function __construct(array $choices)
    {
        $this->choices = $choices;
    }

    public function transform($data)
    {
        if (in_array($data, $this->choices, true)) {
            return array('choice' => $data, 'new_value' => null);
        }

        return array('choice' => ExtendableSelectType::NEW_VALUE, 'new_value' => $data);
    }

    public function reverseTransform($data)
    {
        if (ExtendableSelectType::NEW_VALUE === $data['choice']) {
            return $data['new_value'];
        }

        return $data['choice'];
    }
}
