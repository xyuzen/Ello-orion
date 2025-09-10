<?php
// Output selalu JSON
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, User-Agent");
header("X-Content-Type-Options: nosniff");

// Nama file CSV
$filename = 'Biznet_Karawang.csv';

// Cek file ada atau tidak
if (!file_exists($filename)) {
    echo json_encode(["error" => "File tidak ditemukan"]);
    exit;
}

// Buka file CSV
$handle = fopen($filename, "r");
if ($handle === false) {
    echo json_encode(["error" => "Gagal membuka file"]);
    exit;
}

// Ambil header (nama kolom)
$header = fgetcsv($handle);
if ($header === false) {
    echo json_encode(["error" => "Header CSV tidak terbaca"]);
    fclose($handle);
    exit;
}

$data = [];

// Loop baris CSV
while (($row = fgetcsv($handle)) !== false) {
    // Pastikan jumlah kolom sama
    if (count($row) === count($header)) {
        $data[] = array_combine($header, $row);
    }
}

fclose($handle);

// Output langsung array JSON (bukan dibungkus)
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
