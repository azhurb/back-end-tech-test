<?php

namespace App\Repository\Upstream;

/**
 * Class WindSpeedRepository
 * @package App\Repository\Upstream
 */
class WindSpeedRepository extends DataRepository
{
    /**
     * WindSpeedRepository constructor.
     * @param string $windSpeedServiceUri
     * @param bool $useCache
     */
    public function __construct($windSpeedServiceUri, $useCache)
    {
        parent::__construct($windSpeedServiceUri, $useCache);
    }
}