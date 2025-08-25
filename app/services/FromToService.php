<?php

require_once APPROOT . '/repositories/FromToRepository.php';

class FromToService {
    private $routeRepository;

    public function __construct(FromToRepository $routeRepository) {
        $this->routeRepository = $routeRepository;
    }

    public function getUniqueFromCities(): array {
        return $this->routeRepository->getDistinctFromCities();
    }

    public function getUniqueToCities(): array {
        return $this->routeRepository->getDistinctToCities();
    }
}
