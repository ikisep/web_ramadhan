<?php
// Get prayer times for Bandung, Indonesia
// Coordinates: Bandung -6.9175, 107.6191

$latitude = -6.9175;
$longitude = 107.6191;
$date = date('Y-m-d');
$month = date('n');
$year = date('Y');

// Using Aladhan API (free Islamic prayer times API)
// Method 11 = Kementerian Agama Republik Indonesia (Kemenag)
// School 1 = Hanafi (for Asr calculation)
$api_url = "https://api.aladhan.com/v1/calendar/$year/$month?latitude=$latitude&longitude=$longitude&method=11&school=0";

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
                
                // Extract prayer times (remove timezone info and convert to 24h format)
                function extractTime($timeString) {
                    // Format: "HH:MM (GMT+07:00)" or "HH:MM"
                    $time = trim(explode('(', $timeString)[0]);
                    return substr($time, 0, 5);
                }
                
                $prayer_times = [
                    'subuh' => extractTime($timings['Fajr']),
                    'dzuhur' => extractTime($timings['Dhuhr']),
                    'ashar' => extractTime($timings['Asr']),
                    'maghrib' => extractTime($timings['Maghrib']),
                    'isya' => extractTime($timings['Isha'])
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

// Fallback: Return default times if API fails (approximate times for Bandung)
// Waktu Ashar biasanya sekitar jam 3:15-3:30 PM di Bandung (bukan jam 5 PM)
$default_times = [
    'subuh' => '04:30',
    'dzuhur' => '12:00',
    'ashar' => '15:20',  // Fixed: Ashar sekitar jam 3:20 PM untuk Bandung
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

