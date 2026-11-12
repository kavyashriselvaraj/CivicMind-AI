<?php

namespace CivicMind\Controllers;

use CivicMind\Core\View;
use CivicMind\Core\Response;
use CivicMind\Core\Session;
use CivicMind\Core\Database;
use PDO;

class ProviderController {
    public function index(): void {
        $category = $_GET['category'] ?? '';
        $db = Database::getInstance();

        if ($category) {
            $stmt = $db->prepare("SELECT sp.*, u.phone, u.avatar FROM service_providers sp JOIN users u ON sp.user_id = u.id WHERE sp.service_category LIKE :cat ORDER BY sp.rating DESC");
            $stmt->execute(['cat' => "%{$category}%"]);
        } else {
            $stmt = $db->query("SELECT sp.*, u.phone, u.avatar FROM service_providers sp JOIN users u ON sp.user_id = u.id ORDER BY sp.rating DESC");
        }

        $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('providers/index', [
            'title' => 'Trusted Local Service Provider Network - CivicMind X',
            'providers' => $providers,
            'selectedCategory' => $category,
        ]);
    }

    public function book(): void {
        $user = Session::get('user');
        if (!$user) {
            Response::error('Please login to book a service provider.', 401);
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $providerId = (int)($input['provider_id'] ?? 0);
        $description = trim($input['service_description'] ?? '');
        $date = $input['scheduled_date'] ?? date('Y-m-d H:i:s', strtotime('+1 day'));
        $price = (float)($input['total_price'] ?? 450.00);

        if (!$providerId || !$description) {
            Response::error('Please select a provider and detail your service request.', 400);
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO bookings (user_id, provider_id, service_description, scheduled_date, status, total_price) VALUES (:uid, :pid, :desc, :sdate, 'Pending', :price)");
        $stmt->execute([
            'uid' => $user['id'],
            'pid' => $providerId,
            'desc' => $description,
            'sdate' => $date,
            'price' => $price,
        ]);

        Response::success(['booking_id' => $db->lastInsertId()], 'Booking request submitted successfully! The service provider will contact you shortly.');
    }
}
