<?php

namespace Lamia\HttpClient;

use GuzzleHttp\Exception\GuzzleException;
use Lamia\HttpClient\Exception\ErrorException;
use Lamia\HttpClient\Exception\InvalidArgumentException;
use Lamia\HttpClient\Exception\RuntimeException;
use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * Send a GET request
     *
     * @param string $path       Request path
     * @param array  $parameters GET Parameters
     * @param array  $headers    Reconfigure the request headers for this call only
     *
     * @return ResponseInterface
     */
    public function get($path, array $parameters = [], array $headers = []);
    /**
     * Send a POST request
     *
     * @param string $path    Request path
     * @param mixed  $body    Request body
     * @param array  $headers Reconfigure the request headers for this call only
     *
     * @return ResponseInterface
     */
    public function post($path, $body = null, array $headers = []);
    /**
     * Send a PATCH request
     *
     * @param string $path    Request path
     * @param mixed  $body    Request body
     * @param array  $headers Reconfigure the request headers for this call only
     *
     * @internal param array $parameters Request body
     * @return ResponseInterface
     */
    public function patch($path, $body = null, array $headers = []);
    /**
     * Send a PUT request
     *
     * @param string $path    Request path
     * @param mixed  $body    Request body
     * @param array  $headers Reconfigure the request headers for this call only
     *
     * @return ResponseInterface
     */
    public function put($path, $body, array $headers = []);
    /**
     * Send a DELETE request
     *
     * @param string $path    Request path
     * @param mixed  $body    Request body
     * @param array  $headers Reconfigure the request headers for this call only
     *
     * @return ResponseInterface
     */
    public function delete($path, $body = null, array $headers = []);
    /**
     * Send a request to the server, receive a response,
     * decode the response and returns an associative array
     *
     * @param string $path       Request path
     * @param mixed  $body       Request body
     * @param string $httpMethod HTTP method to use
     * @param array  $headers    Request headers
     *
     * @return ResponseInterface
     *
     * @throws ErrorException
     * @throws RuntimeException
     * @throws GuzzleException
     */
    public function request($path, $body, $httpMethod = 'GET', array $headers = []);
    /**
     * Change an option value.
     *
     * @param string $name The option name
     * @param mixed $value The value
     *
     * @throws InvalidArgumentException
     * 
     * @return void
     */
    public function setOption($name, $value);
    /**
     * Set HTTP headers
     *
     * @param array<string, string|int|float> $headers
     * @return void
     */
    public function setHeaders(array $headers);
}
