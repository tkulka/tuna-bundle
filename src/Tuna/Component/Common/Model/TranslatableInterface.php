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
     * @param mixed $locale the current locale
     */
    public function setCurrentLocale($locale);

    /**
     * Returns translation for specific locale (creates new one if doesn't exists).
     * If requested translation doesn't exist, it will first try to fallback default locale
     * If any translation doesn't exist, it will be added to newTranslations collection.
     * In order to persist new translations, call mergeNewTranslations method, before flush
     *
     * @param string $locale The locale (en, ru, fr) | null If null, will try with current locale
     * @param bool $fallbackToDefault Whether fallback to default locale
     *
     * @return TranslatableInterface
     */
    public function translate($locale = null, $fallbackToDefault = true);
}
