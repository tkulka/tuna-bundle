<?php

namespace TunaCMS\CommonComponent\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

interface TranslatableInterface
{
    /**
     * @return ArrayCollection
     */
    public function getTranslations();

    /**
     * @param ArrayCollection $translations
     *
     * @return TranslatableInterface
     */
    public function setTranslations(ArrayCollection $translations);

    /**
     * @param AbstractPersonalTranslation $translation
     *
     * @return TranslatableInterface
     */
    public function addTranslation($translation);

    /**
     * @param AbstractPersonalTranslation $translation
     *
     * @return TranslatableInterface
     */
    public function removeTranslation($translation);

    /**
     * @param $locale
     *
     * @return $this
     */
    public function setTranslatableLocale($locale);
}
