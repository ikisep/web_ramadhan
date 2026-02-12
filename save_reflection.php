<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $content = $_POST['content'] ?? '';
        $date = $_POST['date'] ?? date('Y-m-d');
        
        // Check if reflection exists for this date
        $stmt = $pdo->prepare("SELECT id FROM reflections WHERE reflection_date = ?");
        $stmt->execute([$date]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            // Update existing reflection
            $stmt = $pdo->prepare("UPDATE reflections SET content = ? WHERE reflection_date = ?");
            $stmt->execute([$content, $date]);
        } else {
            // Insert new reflection
            $stmt = $pdo->prepare("INSERT INTO reflections (reflection_date, content) VALUES (?, ?)");
            $stmt->execute([$date, $content]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Reflection saved successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>

