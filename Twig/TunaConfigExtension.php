<?php

namespace TunaCMS\AdminBundle\Twig;

class TunaConfigExtension extends \Twig_Extension
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tuna_get_config', [$this, 'getConfig']),
        ];
    }

    /**
     * @param string $key Compatible with PropertyAccessor format (ie. `[components][menu][default_template]`)
     *
     * @return mixed
     */
    public function getConfig($key)
    {
        $keyParts = explode('.', $key);
        $config = $this->config;

        foreach ($keyParts as $keyPart) {
            if (!array_key_exists($keyPart, $config)) {
                throw new \InvalidArgumentException(sprintf('Invalid config key (%s). Available keys: %s',
                    $key,
                    implode(', ', $this->getAvailableKeys())
                ));
            }

            $config = $config[$keyPart];
        }

        return $config;
    }

    protected function getAvailableKeys()
    {
        $keys = [];
        $extractKeys = function ($config, $prefix = '') use (&$keys, &$extractKeys) {
            if ($prefix != '') {
                $prefix = "$prefix.";
            }

            if (is_array($config)) {
                foreach ($config as $key => $value) {
                    $newKey = $prefix.$key;
                    $keys[] = $newKey;
                    $extractKeys($value, $newKey);
                }
            }
        };

        $extractKeys($this->config);

        return $keys;
    }
}
