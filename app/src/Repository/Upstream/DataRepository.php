<?php

namespace App\Repository\Upstream;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * Class DataRepository
 * @package App\Repository\Upstream
 */
abstract class DataRepository implements DataRepositoryInterface
{

    private $client;
    private $useCache;

    /**
     * DataRepository constructor.
     * @param $url string
     * @param $useCache boolean
     */
    public function __construct($url, $useCache)
    {
        $this->client = new Client(['base_uri' => $url]);
        $this->useCache = $useCache;
    }

    /**
     * @param $date string ISO8601 DateTime
     * @throws UpstreamException
     * @return array
     */
    public function findByDate($date)
    {
        $key = (new \ReflectionClass($this))->getShortName() . '' . md5($date);
        $cache = new FilesystemCache(); // todo: switch to memcached in production

        try {

            if ($this->useCache && $cache->has($key)) {
                $data = $cache->get($key);
            } else {
                $response = $this->client->request('GET', '?at=' . $date, [
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]);

                $data = \json_decode($response->getBody(), true);

                if ($this->useCache && $response->getStatusCode() === 200 && $data !== false) {
                    $cache->set($key, $data);
                }
            }
        } catch (TransferException $e) {
            throw new UpstreamException($e->getMessage());
        }

        return $data;
    }
}