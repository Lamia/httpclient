<?php
declare(strict_types=1);

namespace Lamia\HttpClient;

use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{
    const TEST_URL = 'http://jsonplaceholder.typicode.com/';

    public function testCreateHttpClientInstance()
    {
        $newInstance = new HttpClient(self::TEST_URL);
        $this->assertInstanceOf(HttpClient::class, $newInstance);
    }

    public function testDummyGetRequest()
    {
        $newInstance = new HttpClient(self::TEST_URL);
        $this->assertNotEmpty($newInstance->get('posts/1')->getBody()->getContents());
    }

    public function testDummyGetRequestWithParams()
    {
        $newInstance = new HttpClient(self::TEST_URL);
        $result = json_decode($newInstance->get('posts', ['userId' => 2])->getBody()->getContents());
        $this->assertEquals(2, $result[1]->userId);
    }

    public function testDummyPostRequest()
    {
        $newInstance = new HttpClient(self::TEST_URL);
        $result = json_decode($newInstance->post('posts', json_encode(['title' => 'test', 'body' => 'blabla', 'userId' => 1]))->getBody()->getContents());

        $this->assertNotEmpty($result->id);
    }

    public function testDummyPutRequest()
    {
        $newInstance = new HttpClient(self::TEST_URL);
        $result = json_decode($newInstance->put('posts/1', json_encode(['id' => 1, 'title' => 'test', 'body' => 'blabla', 'userId' => 1]))->getBody()->getContents());

        $this->assertEquals(1, $result->id);
    }

    public function testDummyDeleteRequest()
    {
        $newInstance = new HttpClient(self::TEST_URL);
        $this->assertEquals(200, $newInstance->delete('posts/1')->getStatusCode());

    }
}
