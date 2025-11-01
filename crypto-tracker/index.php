<?php
// === FUNGSI cURL ===
include("config/config.php");

// === AMBIL DATA MARKET (Top 10) ===
$endpoint_market = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=10&page=1&sparkline=false";
$data_market = http_request_get($endpoint_market);
$cryptoNews = $data_market ? json_decode($data_market, true) : null;

// === AMBIL DATA TRENDING ===
$endpoint_trending = "https://api.coingecko.com/api/v3/search/trending";
$data_trending = http_request_get($endpoint_trending);
$trendingNews = $data_trending ? json_decode($data_trending, true) : null;

// === CEK ERROR ===
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
        /* === GENERAL STYLE === */
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .price-up { color: #28a745 !important; }
        .price-down { color: #dc3545 !important; }
        .navbar-brand i { color: #ffc107; }

        /* === CRYPTO CARD === */
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

        /* === TRENDING SECTION === */
        .trending-container {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: #fff;
            padding: 8px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .trending-grid {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 8px 0;
            gap: 12px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .trending-grid::-webkit-scrollbar { display: none; }
        .trending-card-item { min-width: 230px; max-width: 230px; }

        .trending-card-modern {
            transition: all 0.3s ease;
            border-radius: 12px !important;
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        }
        .trending-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12) !important;
        }
        .trending-card-modern .card-body { padding: 12px !important; }

        /* === RANK BADGE & LOGO === */
        .rank-badge {
            width: 36px !important;
            height: 36px !important;
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0 !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }
        .coin-img {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #eee;
            flex-shrink: 0 !important;
        }

        /* === BADGE & TEXT === */
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545, #c82333) !important;
            font-size: 0.75rem;
        }
        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* === SCROLL INDICATORS === */
        .scroll-indicator {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            z-index: 10;
            opacity: 0;
            transition: opacity 0.3s ease;
            user-select: none;
        }
        .trending-container:hover .scroll-indicator { opacity: 1; }
        .scroll-indicator.left { left: 10px; }
        .scroll-indicator.right { right: 10px; }
        .scroll-indicator:active {
            background: rgba(0,0,0,0.9);
            transform: translateY(-50%) scale(0.95);
        }

        /* === RESPONSIVE === */
        @media (max-width: 768px) {
            .scroll-indicator { display: none; }
            .trending-card-item { min-width: 180px; max-width: 180px; }
        }
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

    <!-- Main Container -->
    <div class="container mt-4">
        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle"></i> 
                <strong>Error:</strong> <?= htmlspecialchars($error) ?>
                <hr>
                <small class="text-muted">Coba refresh atau cek koneksi internet.</small>
            </div>
        <?php else: ?>
            <!-- Header + Search -->
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="mb-1"><i class="fas fa-chart-line"></i> Top 10 Crypto</h2>
                    <p class="text-muted mb-3">Live dari CoinGecko • Update otomatis</p>
                    <input type="text" id="search" class="form-control form-control-lg" 
                           placeholder="Cari crypto... (contoh: bitcoin, eth)">
                </div>
            </div>

            <!-- Trending Section -->
            <?php if ($trendingNews && isset($trendingNews['coins']) && !empty($trendingNews['coins'])): ?>
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-fire text-danger"></i> Trending Crypto (Hot Now)</h5>
                        <small class="text-muted ms-2">Crypto paling banyak dicari & dibicarakan</small>
                    </div>

                    <div class="trending-container position-relative">
                        <!-- Scroll Left -->
                        <div class="scroll-indicator left">
                            <i class="fas fa-chevron-left"></i>
                        </div>

                        <!-- Scrollable Grid -->
                        <div class="trending-grid">
                            <?php 
                            $trendingCoins = array_slice($trendingNews['coins'], 0, 10);
                            foreach ($trendingCoins as $index => $coin): 
                                $item = $coin['item'];
                                $rank = $index + 1;
                            ?>
                                <div class="trending-card-item">
                                    <div class="card h-100 border-0 shadow-sm trending-card-modern">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <!-- Rank -->
                                                <div class="rank-badge flex-shrink-0 me-3">#<?= $rank ?></div>
                                                
                                                <!-- Logo -->
                                                <img src="<?= $item['large'] ?? 'https://via.placeholder.com/40' ?>" 
                                                     alt="<?= htmlspecialchars($item['name']) ?>" 
                                                     class="coin-img flex-shrink-0 me-3">

                                                <!-- Nama & Symbol -->
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="mb-0 fw-bold text-dark text-truncate" style="max-width: 120px;">
                                                        <?= htmlspecialchars($item['name']) ?>
                                                    </h6>
                                                    <small class="text-uppercase fw-bold text-muted">
                                                        <?= htmlspecialchars($item['symbol']) ?>
                                                    </small>
                                                </div>

                                                <!-- Badge Api -->
                                                <div class="ms-auto">
                                                    <span class="badge bg-gradient-danger text-white px-2 py-1">
                                                        <i class="fas fa-fire"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Scroll Right -->
                        <div class="scroll-indicator right">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-light border mb-4">
                    <i class="fas fa-info-circle"></i> Data trending tidak tersedia.
                </div>
            <?php endif; ?>

            <!-- Loading State -->
            <div id="loading" class="text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Memuat data crypto...</p>
            </div>

            <!-- Top 10 Cards Container -->
            <div class="row" id="crypto-container" style="display: none;"></div>
        <?php endif; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // === DATA DARI PHP ===
        const cryptoData = <?= json_encode($cryptoNews ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        // === DOM ELEMENTS ===
        const container = document.getElementById('crypto-container');
        const loading = document.getElementById('loading');
        const searchInput = document.getElementById('search');

        // === RENDER CARD ===
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

            loading.style.display = 'none';
            container.style.display = 'flex';
            container.style.flexWrap = 'wrap';
        }

        // === SEARCH REAL-TIME ===
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

        // === RENDER PERTAMA KALI ===
        if (cryptoData && cryptoData.length > 0) {
            setTimeout(() => render(cryptoData), 500);
        } else {
            loading.innerHTML = `<p class="text-danger">Data gagal dimuat.</p>`;
        }

        // === SCROLL TRENDING ===
        document.querySelectorAll('.scroll-indicator').forEach(btn => {
            btn.addEventListener('click', () => {
                const grid = document.querySelector('.trending-grid');
                const dir = btn.classList.contains('left') ? -1 : 1;
                grid.scrollBy({ left: dir * 240, behavior: 'smooth' });
            });
        });

        // === AUTO-HIDE INDICATORS ===
        document.querySelector('.trending-grid')?.addEventListener('scroll', function() {
            const scrollLeft = this.scrollLeft;
            const maxScroll = this.scrollWidth - this.clientWidth;
            document.querySelector('.scroll-indicator.left').style.opacity = scrollLeft > 50 ? '1' : '0';
            document.querySelector('.scroll-indicator.right').style.opacity = (maxScroll - scrollLeft) > 50 ? '1' : '0';
        });
    </script>
</body>
</html>