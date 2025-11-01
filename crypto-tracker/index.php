<?php
// === include config ===
include("config/config.php");

// === AMBIL DATA MARKET (Top 10) ===
$endpoint_market = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=10&page=1&sparkline=false";
$data_market = http_request_get($endpoint_market);
$cryptoNews = $data_market ? json_decode($data_market, true) : null;

// === AMBIL DATA TRENDING (Berita-like) ===
$endpoint_trending = "https://api.coingecko.com/api/v3/search/trending";
$data_trending = http_request_get($endpoint_trending);
$trendingNews = $data_trending ? json_decode($data_trending, true) : null;

// Cek error
$error = null;
if (!$data_market) {
    $error = "Gagal terhubung ke CoinGecko (Market).";
} elseif (!$cryptoNews || json_last_error() !== JSON_ERROR_NONE || !is_array($cryptoNews) || empty($cryptoNews)) {
    $error = "Data market tidak valid.";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .price-up { color: #28a745 !important; }
        .price-down { color: #dc3545 !important; }
        .crypto-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        .crypto-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15) !important;
        }
        .card-body { background: white; }
        .navbar-brand i { color: #ffc107; }
        .trending-card { height: 120px; border-radius: 8px; }
        .carousel-control-prev, .carousel-control-next { width: 5%; }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar bg-success navbar-expand-lg shadow-sm" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fab fa-bitcoin"></i> Crypto Tracker
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- ERROR MESSAGE -->
        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                <hr>
                <small class="text-muted">Coba refresh halaman atau periksa koneksi internet.</small>
            </div>
        <?php else: ?>
            <!-- HEADER + SEARCH -->
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-1"><i class="fas fa-chart-line"></i> Top 10 Crypto</h2>
                    <p class="text-muted mb-3">Live dari CoinGecko • Update otomatis</p>
                    <input type="text" id="search" class="form-control form-control-lg" placeholder="Cari crypto... (contoh: bitcoin, eth)">
                </div>
            </div>

            <!-- FITUR BARU: TRENDING CRYPTO (Seperti Berita) -->
            <?php if ($trendingNews && isset($trendingNews['coins']) && !empty($trendingNews['coins'])): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fas fa-fire"></i> Trending Crypto (Hot Now)</h5>
                        <p class="text-muted small mb-2">Crypto yang lagi populer & dibicarakan banyak orang</p>
                    </div>
                    <div id="trending-carousel" class="col-12">
                        <div id="trendingCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php 
                                $trendingCoins = array_slice($trendingNews['coins'], 0, 15); // Ambil 15 untuk 3 slide (5 per slide)
                                for ($i = 0; $i < count($trendingCoins); $i += 5): 
                                    $slideCoins = array_slice($trendingCoins, $i, 5);
                                    $active = ($i === 0) ? 'active' : '';
                                ?>
                                <div class="carousel-item <?= $active ?>">
                                    <div class="row">
                                        <?php foreach ($slideCoins as $coin): 
                                            $item = $coin['item'];
                                            $score = $coin['score'] ?? 0;
                                        ?>
                                        <div class="col-12 col-md-6 col-lg-4 col-xl-2 mb-2">
                                            <div class="card trending-card bg-warning bg-opacity-10 border-warning h-100">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= $item['large'] ?? 'https://via.placeholder.com/30' ?>" 
                                                             alt="<?= $item['name'] ?>" class="rounded me-2" width="30" height="30">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0 fw-bold"><?= $item['name'] ?></h6>
                                                            <small class="text-muted"><?= strtoupper($item['symbol']) ?></small>
                                                        </div>
                                                        <div class="text-end">
                                                            <small class="badge bg-warning text-dark">Score: <?= round($score) ?></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endfor; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#trendingCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#trendingCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-4">Trending data tidak tersedia saat ini.</div>
            <?php endif; ?>

            <!-- LOADING STATE -->
            <div id="loading" class="text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat data crypto...</p>
            </div>

            <!-- CONTAINER UNTUK TOP 10 CARD -->
            <div class="row" id="crypto-container" style="display: none;">
                <!-- JS akan isi di sini -->
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap + JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data dari PHP (pastikan aman)
        const cryptoData = <?= json_encode($cryptoNews ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        // DOM
        const container = document.getElementById('crypto-container');
        const loading = document.getElementById('loading');
        const searchInput = document.getElementById('search');

        // Render card (Top 10)
        function render(data) {
            if (!container) return;

            container.innerHTML = '';

            if (!data || data.length === 0) {
                container.innerHTML = `<div class="col-12 text-center py-5 text-muted">Tidak ada data.</div>`;
                return;
            }

            data.forEach(coin => {
                const price = coin.current_price ?? 0;
                const change = coin.price_change_percentage_24h ?? 0;
                const marketCap = coin.market_cap ?? 0;
                const volume = coin.total_volume ?? 0;

                const isUp = change >= 0;
                const icon = isUp ? 'fa-arrow-up' : 'fa-arrow-down';
                const color = isUp ? 'price-up' : 'price-down';

                const card = `
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card crypto-card h-100 shadow">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="${coin.image || 'https://via.placeholder.com/50'}" 
                                     alt="${coin.name}" class="rounded-circle me-3" width="50" height="50"
                                     onerror="this.src='https://via.placeholder.com/50/6c757d/fff?text=?'">
                                <div>
                                    <h5 class="mb-0 fw-bold">${coin.name}</h5>
                                    <small class="text-muted text-uppercase">${coin.symbol}</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center mb-2">
                                <div class="col-6">
                                    <div class="fw-bold fs-5">$${price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
                                    <small class="text-muted">Harga</small>
                                </div>
                                <div class="col-6">
                                    <div class="${color} fw-bold">
                                        <i class="fas ${icon}"></i> ${change.toFixed(2)}%
                                    </div>
                                    <small class="text-muted">24 jam</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="text-primary fw-bold">$${(marketCap / 1e9).toFixed(1)}B</div>
                                    <small class="text-muted">Market Cap</small>
                                </div>
                                <div class="col-6">
                                    <div class="text-success fw-bold">${Math.round(volume / 1e6)}M</div>
                                    <small class="text-muted">Volume</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                container.innerHTML += card;
            });

            // Sembunyikan loading, tampilkan card
            loading.style.display = 'none';
            container.style.display = 'flex';
            container.style.flexWrap = 'wrap';
        }

        // Search real-time (Top 10)
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                const q = searchInput.value.toLowerCase().trim();
                const filtered = cryptoData.filter(coin =>
                    coin.name.toLowerCase().includes(q) ||
                    coin.symbol.toLowerCase().includes(q)
                );
                render(filtered);
            });
        }

        // Render pertama kali
        if (cryptoData && cryptoData.length > 0) {
            setTimeout(() => render(cryptoData), 500); // Simulasi loading
        } else {
            loading.innerHTML = `<p class="text-danger">Data gagal dimuat.</p>`;
        }
    </script>
</body>
</html>