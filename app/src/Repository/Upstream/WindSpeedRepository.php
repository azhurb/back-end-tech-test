<?php

namespace App\Repository\Upstream;

class WindSpeedRepository extends DataRepository
{
    public function __construct($windSpeedServiceUri)
    {
        parent::__construct($windSpeedServiceUri);
    }
}