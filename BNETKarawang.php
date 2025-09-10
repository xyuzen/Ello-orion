<?php
header('Content-Type: application/json; charset=utf-8');

// Lokasi file CSV
$csvFile = __DIR__ . '/Biznet_Karawang.csv';

// Ambil raw input body
$input = file_get_contents("php://input");
$params = json_decode($input, true);

// Kalau ada "limit" di body
$limit = isset($params['limit']) ? intval($params['limit']) : 0;

if (!file_exists($csvFile)) {
    echo json_encode(["error" => "File CSV tidak ditemukan"]);
    exit;
}

if (($handle = fopen($csvFile, "r")) !== false) {
    // Sertakan semua parameter fgetcsv agar tidak muncul warning PHP 8.1+
    $headers = fgetcsv($handle, 0, ",", '"', "\\");

    $rows = [];
    $count = 0;

    while (($row = fgetcsv($handle, 0, ",", '"', "\\")) !== false) {
        // Pastikan jumlah kolom sama dengan header
        if (count($row) === count($headers)) {
            $rows[] = array_combine($headers, $row);
        }

        if ($limit > 0 && ++$count >= $limit) break;
    }
    fclose($handle);

    echo json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Gagal membuka file CSV"]);
}
