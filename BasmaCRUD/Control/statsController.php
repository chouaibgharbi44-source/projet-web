<?php
require_once __DIR__ . '/../Model/Stats.php';

class StatsController
{
    private $model;

    public function __construct()
    {
        $this->model = new Stats();
    }

    public function handleRequest()
    {
        $totalEvents = $this->model->getTotalEvents();
        $totalReservations = $this->model->getTotalReservations();
        $eventsByCategory = $this->model->getEventsByCategory();
        $reservationsByEvent = $this->model->getReservationsByEvent();
        $eventStatusDistribution = $this->model->getEventStatusDistribution();

        // New Stats
        $reservationStatusDistribution = $this->model->getReservationStatusDistribution();
        $capacityStats = $this->model->getEventCapacityStats();
        $eventsByMonth = $this->model->getEventsByMonth();

        // Advanced Stats (Step 3)
        $occupancyStats = $this->model->getGlobalOccupancy();
        $topUsers = $this->model->getTopActiveUsers();
        $reservationsByDay = $this->model->getReservationsByDayOfWeek();

        require_once __DIR__ . '/../View/backoffice/stats.php';
    }
}
