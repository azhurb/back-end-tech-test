<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;


/**
 * Few edge cases to test with real upstream servers.
 *
 * Class SensorApiControllerTest
 * @package App\Tests\Controller
 */
class SensorApiControllerTest extends WebTestCase
{
    /**
     * @var Client;
     */
    private $client;

    public function setUp()
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    /**
     * Status and json format test.
     */
    public function testStatus200()
    {

        foreach (array('temperatures', 'speeds', 'weather') as $resource) {

            $this->client->request('GET', '/' . $resource . '?start=2019-01-01T12:00:00Z&end=2019-01-01T12:00:00Z');

            $response = $this->client->getResponse();

            $this->assertEquals(200, $response->getStatusCode());

            $this->assertJson($response->getContent());
        }
    }

    /**
     * Date format test.
     */
    public function testStatus400()
    {

        foreach (array('temperatures', 'speeds', 'weather') as $resource) {

            $this->client->request('GET', '/' . $resource . '?start=2019-13-01T12:00:00Z&end=2019-01-02T12:00:00Z');

            $response = $this->client->getResponse();

            $this->assertEquals(400, $response->getStatusCode());

            $this->assertJson($response->getContent());

            $this->assertContains('is not a valid RFC3339 DateTime', $response->getContent());
        }
    }

    /**
     * Date range test.
     */
    public function testMissingRange()
    {

        foreach (array('temperatures', 'speeds', 'weather') as $resource) {

            $this->client->request('GET', '/' . $resource . '');

            $response = $this->client->getResponse();

            $this->assertEquals(400, $response->getStatusCode());

            $this->assertJson($response->getContent());

            $this->assertContains('Date range not specified', $response->getContent());
        }
    }

    /**
     * Date range test.
     */
    public function testWrongRange()
    {

        foreach (array('temperatures', 'speeds', 'weather') as $resource) {

            $this->client->request('GET', '/' . $resource . '');

            $response = $this->client->getResponse();

            $this->assertEquals(400, $response->getStatusCode());

            $this->assertJson($response->getContent());

            $this->assertContains('Date range not specified', $response->getContent());
        }
    }

    /**
     * Check fields.
     */
    public function testWeatherFields(){


        $this->client->request('GET', '/weather?start=2019-01-01T12:00:00Z&end=2019-01-02T12:00:00Z');

        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $data = \json_decode($response->getContent(), true);

        $this->assertEquals(2, count($data));

        $this->assertArrayHasKey('temp', $data[0]);
        $this->assertArrayHasKey('date', $data[0]);
        $this->assertArrayHasKey('north', $data[0]);
        $this->assertArrayHasKey('west', $data[0]);
    }

    /**
     * Check 404.
     */
    public function testNotFound404(){


        $this->client->request('GET', '/dummy');

        $response = $this->client->getResponse();

        $this->assertEquals(404, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $this->assertContains('Not found', $response->getContent());
    }
}