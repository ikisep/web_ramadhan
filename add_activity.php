<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = $_POST['title'] ?? '';
        $category = $_POST['category'] ?? '';
        $activity_date = $_POST['activity_date'] ?? '';
        $activity_time = $_POST['activity_time'] ?? '';
        
        if (empty($title) || empty($category) || empty($activity_date) || empty($activity_time)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required']);
            exit;
        }
        
        $stmt = $pdo->prepare("INSERT INTO activities (title, category, activity_date, activity_time) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $category, $activity_date, $activity_time]);
        
        echo json_encode(['success' => true, 'message' => 'Activity added successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

