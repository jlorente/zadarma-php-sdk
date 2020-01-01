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

interface ConfigInterface
{

    /**
     * Returns the current package version.
     *
     * @return string
     */
    public function getVersion();

    /**
     * Sets the current package version.
     *
     * @param  string  $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * Returns the Zadarma API key.
     *
     * @return string
     */
    public function getApiKey();

    /**
     * Sets the Zadarma API key.
     *
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey);

    /**
     * Returns the Zadarma API secret.
     *
     * @return string
     */
    public function getApiSecret();

    /**
     * Sets the Zadarma API secret.
     *
     * @param string $apiSecret
     * @return $this
     */
    public function setApiSecret($apiSecret);

    /**
     * Returns the Zadarma request retries value.
     *
     * @return string
     */
    public function getRequestRetries();

    /**
     * Sets the Zadarma API key.
     *
     * @param string $retries
     * @return $this
     */
    public function setRequestRetries($retries);
}
