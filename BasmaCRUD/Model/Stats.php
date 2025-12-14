<?php
require_once __DIR__ . '/db.php';

class Stats
{
    private $db;

    public function __construct()
    {
        $this->db = DB::getConnection();
    }

    public function getTotalEvents()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM evenements");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getTotalReservations()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as count FROM reservations");
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getEventsByCategory()
    {
        $stmt = $this->db->query("SELECT category, COUNT(*) as count FROM evenements GROUP BY category");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationsByEvent()
    {
        $stmt = $this->db->query("SELECT e.title, COUNT(r.id) as count FROM evenements e LEFT JOIN reservations r ON e.id = r.event_id GROUP BY e.id ORDER BY count DESC LIMIT 5");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventStatusDistribution()
    {
        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM evenements GROUP BY status");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationStatusDistribution()
    {
        $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM reservations GROUP BY status");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventCapacityStats()
    {
        $stmt = $this->db->query("SELECT SUM(capacity) as total_capacity, AVG(capacity) as avg_capacity FROM evenements");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEventsByMonth()
    {
        $stmt = $this->db->query("SELECT DATE_FORMAT(date, '%M %Y') as month, COUNT(*) as count FROM evenements GROUP BY DATE_FORMAT(date, '%Y-%m'), DATE_FORMAT(date, '%M %Y') ORDER BY DATE_FORMAT(date, '%Y-%m') ASC LIMIT 12");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- New Methods ---

    public function getGlobalOccupancy()
    {
        // Calculate total seats reserved vs total capacity across all events
        // Note: This assumes reservations table has a 'seats' column and evenements has 'capacity'

        $totalSeatsQuery = $this->db->query("SELECT SUM(seats) as total_reserved FROM reservations WHERE status != 'cancelled' AND status != 'rejected'");
        $totalCapacityQuery = $this->db->query("SELECT SUM(capacity) as total_capacity FROM evenements");

        $reserved = $totalSeatsQuery->fetch(PDO::FETCH_ASSOC)['total_reserved'] ?? 0;
        $capacity = $totalCapacityQuery->fetch(PDO::FETCH_ASSOC)['total_capacity'] ?? 0;

        return [
            'reserved' => $reserved,
            'capacity' => $capacity,
            'rate' => ($capacity > 0) ? round(($reserved / $capacity) * 100, 1) : 0
        ];
    }

    public function getTopActiveUsers()
    {
        // Top 5 users by number of reservations
        $stmt = $this->db->query("SELECT name, email, COUNT(*) as count FROM reservations GROUP BY email ORDER BY count DESC LIMIT 5");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationsByDayOfWeek()
    {
        // Day name (Monday, Tuesday...)
        // Note: DAYNAME returns English names by default in standard MySQL setup
        $stmt = $this->db->query("SELECT DAYNAME(created_at) as day, COUNT(*) as count FROM reservations GROUP BY DAYOFWEEK(created_at), DAYNAME(created_at) ORDER BY DAYOFWEEK(created_at)");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
