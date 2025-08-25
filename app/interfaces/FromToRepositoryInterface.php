<?php

interface IRouteRepository {
    public function getDistinctFromCities(): array;
    public function getDistinctToCities(): array;
}