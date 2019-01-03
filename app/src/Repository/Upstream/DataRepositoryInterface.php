<?php

namespace App\Repository\Upstream;

interface DataRepositoryInterface
{

    /**
     * @param $date string ISO8601 DateTime
     * @return array
     */
    public function findByDate($date);
}