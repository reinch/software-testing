<?php
// system-test.php

function test($description, $condition) {
    echo $description . ': ' . ($condition ? "✅ PASSED" : "❌ FAILED") . "\n";
}

// Simulasi data dari API (bisa diganti dengan hasil http_request_get jika ingin live test)
$mockData = json_encode(array_map(function($i) {
    return [
        'name' => "Crypto $i",
        'symbol' => "C$i",
        'image' => "https://example.com/image$i.png",
        'current_price' => 1000 + $i,
        'price_change_percentage_24h' => $i % 2 === 0 ? 5.5 : -3.2,
        'market_cap' => 1e9 + $i * 1e8,
        'total_volume' => 5e6 + $i * 1e6
    ];
}, range(1, 10)));

$cryptoNews = json_decode($mockData, true);

// --------------------
// BLACK BOX TEST CASES
// --------------------

// Test 1: Jumlah data sesuai permintaan (10 koin)
test("BlackBox: Jumlah data harus 10", count($cryptoNews) === 10);

// Test 2: Respons kosong
$emptyData = json_decode("[]", true);
test("BlackBox: Respons kosong harus menghasilkan array kosong", is_array($emptyData) && count($emptyData) === 0);

// Test 3: Respons gagal (bukan JSON)
$invalidJson = json_decode("INVALID_JSON", true);
test("BlackBox: Respons gagal harus menghasilkan null", $invalidJson === null);

// --------------------
// WHITE BOX TEST CASES
// --------------------

// Test 4: Loop hanya menampilkan 5 koin
$limited = array_slice($cryptoNews, 0, 5);
test("WhiteBox: array_slice harus menghasilkan 5 elemen", count($limited) === 5);

// Test 5: Harga naik → class price-up
$firstUp = $cryptoNews[0];
$changeClass = $firstUp['price_change_percentage_24h'] >= 0 ? 'price-up' : 'price-down';
test("WhiteBox: Harga naik harus menghasilkan class 'price-up'", $changeClass === 'price-up');

// Test 6: Harga turun → class price-down
$firstDown = $cryptoNews[1];
$changeClass = $firstDown['price_change_percentage_24h'] >= 0 ? 'price-up' : 'price-down';
test("WhiteBox: Harga turun harus menghasilkan class 'price-down'", $changeClass === 'price-down');