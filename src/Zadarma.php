<?php

/**
 * Part of the Zadarma package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Zadarma
 * @version    1.0.0
 * @author     Jose Lorente
 * @license    BSD License (3-clause)
 * @copyright  (c) 2019, Jose Lorente
 */

namespace Jlorente\Zadarma;

class Zadarma
{

    /**
     * The package version.
     *
     * @var string
     */
    const VERSION = '1.0.0';

    /**
     * The Config repository instance.
     *
     * @var \Jlorente\Zadarma\ConfigInterface
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param int $requestRetries
     * @return void
     */
    public function __construct($apiKey = null, $apiSecret = null, $requestRetries = null)
    {
        $this->config = new Config(self::VERSION, $apiKey, $apiSecret, $requestRetries);
    }

    /**
     * Create a new Zadarma API instance.
     *
     * @param string $apiKey
     * @param string $apiSecret
     * @param int $requestRetries
     * @return \Jlorente\Zadarma\Zadarma
     */
    public static function make($apiKey = null, $apiSecret = null, $requestRetries = null)
    {
        return new static($apiKey, $apiSecret, $requestRetries);
    }

    /**
     * Returns the current package version.
     *
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Returns the Config repository instance.
     *
     * @return \Jlorente\Zadarma\ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Sets the Config repository instance.
     *
     * @param  \Jlorente\Zadarma\ConfigInterface  $config
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Returns the Zadarma API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->config->getApiKey();
    }

    /**
     * Sets the Zadarma API key.
     *
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->config->setApiKey($apiKey);

        return $this;
    }

    /**
     * Returns the Zadarma API secret.
     *
     * @return string
     */
    public function getApiSecret()
    {
        return $this->config->getApiSecret();
    }

    /**
     * Sets the Zadarma API secret.
     *
     * @param string $apiSecret
     * @return $this
     */
    public function setApiSecret($apiSecret)
    {
        $this->config->setApiSecret($apiSecret);

        return $this;
    }

    /**
     * Dynamically handle missing methods.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Jlorente\Zadarma\Core\ApiInterface
     */
    public function api()
    {
        return new Api($this->config);
    }

}
