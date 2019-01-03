<?php

namespace App\Repository\Upstream;

class WeatherRepository implements DataRepositoryInterface
{
    private $temperatureRepository;
    private $windSpeedRepository;

    public function __construct($temperatureServiceUri, $windSpeedServiceUri)
    {
        $this->temperatureRepository = new TemperatureRepository($temperatureServiceUri);
        $this->windSpeedRepository = new WindSpeedRepository($windSpeedServiceUri);
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