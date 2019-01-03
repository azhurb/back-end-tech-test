<?php

namespace App\Repository\Upstream;

/**
 * Class TemperatureRepository
 * @package App\Repository\Upstream
 */
class TemperatureRepository extends DataRepository
{
    public function __construct($temperatureServiceUri, $useCache)
    {
        parent::__construct($temperatureServiceUri, $useCache);
    }
}