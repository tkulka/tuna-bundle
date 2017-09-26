<?php

namespace TunaCMS\Component\Common\Model;

interface LocaleInterface
{
    /**
     * @param string $locale
     *
     * @return self
     */
    public function setLocale($locale);

    /**
     * @return string
     */
    public function getLocale();
}
