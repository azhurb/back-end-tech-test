<?php

namespace App\Controller;

use App\Repository\Upstream\TemperatureRepository;
use App\Repository\Upstream\WeatherRepository;
use App\Repository\Upstream\WindSpeedRepository;
use App\Repository\Upstream\UpstreamException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\Upstream\DataRepositoryInterface;
use App\Util\DateRange;

class SensorsApiController
{
    /**
     * @Route("/temperatures")
     * @param $temperatureRepository TemperatureRepository
     * @param $request Request
     * @return JsonResponse
     */
    public function getTemperature(TemperatureRepository $temperatureRepository, Request $request)
    {
        return $this->getFromRepository($temperatureRepository, $request);
    }

    /**
     * @Route("/speeds")
     * @param $windSpeedRepository WindSpeedRepository
     * @param $request Request
     * @return JsonResponse
     */
    public function getWindSpeed(WindSpeedRepository $windSpeedRepository, Request $request)
    {
        return $this->getFromRepository($windSpeedRepository, $request);
    }

    /**
     * @Route("/weather")
     * @param $weatherRepository WeatherRepository
     * @param $request Request
     * @return JsonResponse
     */
    public function getWeather(WeatherRepository $weatherRepository, Request $request)
    {
        return $this->getFromRepository($weatherRepository, $request);
    }

    private function getFromRepository(DataRepositoryInterface $repository, Request $request)
    {
        $dateRange = new DateRange($request->query->get('start'), $request->query->get('end'));

        $results = [];

        foreach ($dateRange->step() as $currentDate) {
            try {
                $results[] = $repository->findByDate($currentDate);
            } catch (UpstreamException $e) {
                return new JsonResponse(['message' => $e->getMessage()], 500);
            }
        }

        $response = new JsonResponse($results);

        return $response;
    }
}