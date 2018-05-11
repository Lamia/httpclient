<?php
namespace Lamia\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Lamia\HttpClient\Exception\ErrorException;
use Lamia\HttpClient\Exception\RuntimeException;

class HttpClient implements HttpClientInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    protected $headers = array();
    private $lastResponse;
    private $lastRequest;

    protected $options = array(
        'user_agent' => 'lamia-http-request',
        'timeout' => 10
    );

    /**
     * @param string          $baseUrl
     * @param array           $options
     * @param ClientInterface $client
     */
    public function __construct($baseUrl, array $options = array(), ClientInterface $client = null)
    {
        $this->options = array_merge($this->options, $options);
        $this->options['base_uri'] = $baseUrl;
        $client = $client ?: new Client($this->options);
        $this->client = $client;

        $this->clearHeaders();
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clears used headers
     */
    public function clearHeaders()
    {
        $this->headers = array(
            'User-Agent' => sprintf('%s', $this->options['user_agent']),
        );
    }


    /**
     * {@inheritDoc}
     */
    public function get($path, array $parameters = array(), array $headers = array())
    {
        $path .= '?'. http_build_query($parameters);
        return $this->request($path, null, 'GET', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function post($path, $body = null, array $headers = array())
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $this->request($path, $body, 'POST', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function patch($path, $body = null, array $headers = array())
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $this->request($path, $body, 'PATCH', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request($path, $body, 'DELETE', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function put($path, $body, array $headers = array())
    {
        if (!isset($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }
        return $this->request($path, $body, 'PUT', $headers);
    }

    /**
     * {@inheritDoc}
     */
    public function request(
        $path,
        $body = null,
        $httpMethod = 'GET',
        array $headers = array(),
        array $options = array()
    ) {
        $request = $this->createRequest($httpMethod, $path, $body, $headers);
        try {
            $response = $this->client->send($request, $options);
        } catch (\LogicException $e) {
            throw new ErrorException($e->getMessage(), $e->getCode(), $e);
        } catch (\RuntimeException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
        $this->lastRequest = $request;
        $this->lastResponse = $response;
        return $response;
    }

    /**
     * @return Request
     */
    public function getLastRequest()
    {
        return $this->lastRequest;
    }

    /**
     * @return Response
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param string $httpMethod
     * @param string $path
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    protected function createRequest(
        $httpMethod,
        $path,
        $body = null,
        array $headers = array()
    ) {
        return new Request(
            $httpMethod,
            $path,
            array_merge($this->headers, $headers),
            $body
        );
    }
}
