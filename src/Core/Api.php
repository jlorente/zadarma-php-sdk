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

namespace Jlorente\Zadarma\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Jlorente\Zadarma\ConfigInterface;
use Jlorente\Zadarma\Exception\Handler;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Api implements ApiInterface
{

    /**
     * The Config repository instance.
     *
     * @var \Jlorente\Zadarma\ConfigInterface
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param \Jlorente\Zadarma\ConfigInterface $config
     * @return void
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function baseUrl()
    {
        return 'https://api.zadarma.com';
    }

    /**
     * {@inheritdoc}
     */
    public function _get($url = null, $parameters = [])
    {
        return $this->execute('get', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _head($url = null, array $parameters = [])
    {
        return $this->execute('head', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _delete($url = null, array $parameters = [])
    {
        return $this->execute('delete', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _put($url = null, array $parameters = [])
    {
        return $this->execute('put', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _patch($url = null, array $parameters = [])
    {
        return $this->execute('patch', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _post($url = null, array $parameters = [])
    {
        return $this->execute('post', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function _options($url = null, array $parameters = [])
    {
        return $this->execute('options', $url, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($httpMethod, $url, array $parameters = [])
    {
        try {
            $endpoint = $this->prefixString($url, '/');
            $response = $this->getClient()->{$httpMethod}($endpoint, $this->prepareParameters($httpMethod, $endpoint, $parameters));

            return json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            new Handler($e);
        }
    }

    /**
     * Prepares the parameters to form the request.
     * 
     * @param string $httpMethod
     * @param string $url
     * @param array $parameters
     * @return array
     */
    protected function prepareParameters($httpMethod, $endpoint, array $parameters = [])
    {
        $prepared = [
            'headers' => array_merge($parameters['headers'] ?? [], [
                'Authorization' => $this->getAuthHeader($endpoint, $parameters)
            ])
        ];

        if (strtoupper($httpMethod) === 'GET') {
            $prepared['query'] = $parameters;
        } else {
            $prepared['form_params'] = $parameters;
        }

        return $prepared;
    }

    /**
     * Returns an Http client instance.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getClient()
    {
        return new Client([
            'base_uri' => $this->baseUrl(), 'handler' => $this->createHandler()
        ]);
    }

    /**
     * Create the client handler.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    protected function createHandler()
    {
        $stack = HandlerStack::create();

        $stack->push(Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null, TransferException $exception = null) {
                    return $retries < $this->config->getRequestRetries() && ($exception instanceof ConnectException || ($response && $response->getStatusCode() >= 500));
                }, function ($retries) {
                    return (int) pow(2, $retries) * 1000;
                }));

        return $stack;
    }

    /**
     * Encodes the signature.
     * 
     * @param $signatureString
     * @return string
     */
    public function encodeSignature($signatureString)
    {
        return base64_encode(hash_hmac('sha1', $signatureString, $this->config->getApiSecret()));
    }

    /**
     * Gets the authorization header for the request.
     * 
     * @param $method
     * @param $params
     * @return array
     */
    protected function getAuthHeader($endpoint, $params)
    {
        $params = array_filter($params, function($a) {
            return !is_object($a);
        });
        ksort($params);
        $paramsString = $this->httpBuildQuery($params);
        $signature = $this->encodeSignature($endpoint . $paramsString . md5($paramsString));
        return $this->config->getApiKey() . ':' . $signature;
    }

    /**
     * Prefixes a string with the given value.
     * 
     * @param string $value
     * @param string $prefix
     * @return string
     */
    protected function prefixString($value, $prefix)
    {
        return $prefix . preg_replace('/^(?:' . preg_quote($prefix, '/') . ')+/u', '', $value);
    }

    /**
     * Builds an HTTP query.
     *
     * @param array $params
     * @return string
     */
    private function httpBuildQuery($params = array())
    {
        return http_build_query($params, null, '&', PHP_QUERY_RFC1738);
    }

}
