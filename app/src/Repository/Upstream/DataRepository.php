<?php

namespace App\Repository\Upstream;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Symfony\Component\Cache\Simple\FilesystemCache;

abstract class DataRepository implements DataRepositoryInterface
{

    private $client;
    const USE_CACHE = true;

    /**
     * DataRepository constructor.
     * @param $url string
     */
    public function __construct($url)
    {
        $this->client = new Client(['base_uri' => $url, ]);
    }

    /**
     * @param $date string ISO8601 DateTime
     * @throws UpstreamException
     * @return array
     */
    public function findByDate($date)
    {
        $key = (new \ReflectionClass($this))->getShortName() . '' . md5($date);
        $cache = new FilesystemCache(); // todo: change to memcached

        try {

            if (self::USE_CACHE && $cache->has($key)) {
                $data = $cache->get($key);
            }else{
                $response = $this->client->request('GET', '?at=' . $date, [
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);

                $data = \json_decode($response->getBody(), true);

                if (self::USE_CACHE && $response->getStatusCode() === 200 && $data !== false){
                    $cache->set($key, $data);
                }
            }
        } catch (TransferException $e) {
            throw new UpstreamException($e->getMessage());
        }

        return $data;
    }
}