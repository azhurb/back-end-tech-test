<?php

namespace App\Repository\Upstream;

/**
 * Class WeatherRepository
 * @package App\Repository\Upstream
 */
class WeatherRepository implements DataRepositoryInterface
{
    private $temperatureRepository;
    private $windSpeedRepository;

    /**
     * WeatherRepository constructor.
     * @param $temperatureServiceUri
     * @param $windSpeedServiceUri
     * @param $useCache
     */
    public function __construct($temperatureServiceUri, $windSpeedServiceUri, $useCache)
    {
        $this->temperatureRepository = new TemperatureRepository($temperatureServiceUri, $useCache);
        $this->windSpeedRepository = new WindSpeedRepository($windSpeedServiceUri, $useCache);
    }

    /**
     * @param $date string ISO8601 DateTime
     * @throws UpstreamException
     * @return array
     */
    public function findByDate($date)
    {
        return array_merge($this->temperatureRepository->findByDate($date), $this->windSpeedRepository->findByDate($date));
    }
}