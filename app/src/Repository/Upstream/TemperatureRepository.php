<?php

namespace App\Repository\Upstream;

class TemperatureRepository extends DataRepository
{
    public function __construct($temperatureServiceUri)
    {
        parent::__construct($temperatureServiceUri);
    }
}