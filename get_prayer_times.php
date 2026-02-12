<?php
// Get prayer times for Bandung, Indonesia
// Coordinates: Bandung -6.9175, 107.6191

$latitude = -6.9175;
$longitude = 107.6191;
$date = date('Y-m-d');
$month = date('n');
$year = date('Y');

// Using Aladhan API (free Islamic prayer times API)
$api_url = "http://api.aladhan.com/v1/calendar/$year/$month?latitude=$latitude&longitude=$longitude&method=11&school=1";

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

header('Content-Type: application/json');

if ($http_code === 200 && $response) {
    $data = json_decode($response, true);
    
    if (isset($data['data']) && is_array($data['data'])) {
        // Find today's prayer times
        $today = date('d');
        foreach ($data['data'] as $day) {
            if (isset($day['date']['gregorian']['day']) && $day['date']['gregorian']['day'] == $today) {
                $timings = $day['timings'];
                
                // Extract prayer times (remove timezone info)
                $prayer_times = [
                    'subuh' => substr($timings['Fajr'], 0, 5),
                    'dzuhur' => substr($timings['Dhuhr'], 0, 5),
                    'ashar' => substr($timings['Asr'], 0, 5),
                    'maghrib' => substr($timings['Maghrib'], 0, 5),
                    'isya' => substr($timings['Isha'], 0, 5)
                ];
                
                echo json_encode([
                    'success' => true,
                    'date' => $date,
                    'city' => 'Bandung',
                    'prayers' => $prayer_times
                ]);
                exit;
            }
        }
    }
}

// Fallback: Return default times if API fails
$default_times = [
    'subuh' => '04:30',
    'dzuhur' => '12:00',
    'ashar' => '15:15',
    'maghrib' => '18:00',
    'isya' => '19:15'
];

echo json_encode([
    'success' => true,
    'date' => $date,
    'city' => 'Bandung',
    'prayers' => $default_times,
    'note' => 'Using default times'
]);
?>

