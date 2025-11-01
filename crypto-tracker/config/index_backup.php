<?php
//load config.php
include("config/config.php"); 

//url api 
$endpoint = "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=10&page=1&sparkline=false";

//menyimpan hasil dalam variabel
$data = http_request_get($endpoint);

//konversi data json ke array
$cryptoNews = json_decode($data, true);

if (!$endpoint || json_last_error() !== JSON_ERROR_NONE) {
    $error = "Gagal memuat data crypto news!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Crypto Tracker</title>
    <style>
        .price-up { color: #28a745 !important; }
        .price-down { color: #dc3545 !important; }
        .crypto-card { transition: transform 0.2s; }
        .crypto-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <nav class="navbar bg-success navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fab fa-bitcoin"></i> Crypto Live Tracker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav">
                    <a class="nav-link active" href="#"><i class="fas fa-home"></i> Home</a>
                    <a class="nav-link" href="#"><i class="fas fa-coins"></i> Crypto</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php else: ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h2><i class="fas fa-chart-line"></i> Top 10 Crypto</h2>
                    <p class="text-muted">Real-time dari CoinGecko API</p>
                </div>
            </div>

            <div class="row">
                <?php foreach ($cryptoNews as $coin): 
                    $priceChange = $coin['price_change_percentage_24h'];
                    $changeClass = $priceChange >= 0 ? 'price-up' : 'price-down';
                    $icon = $priceChange >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card crypto-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?= $coin['image'] ?>" alt="<?= $coin['name'] ?>" 
                                     class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h5 class="card-title mb-0"><?= $coin['name'] ?></h5>
                                    <small class="text-muted"><?= strtoupper($coin['symbol']) ?></small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="fw-bold">$<?= number_format($coin['current_price'], 2) ?></div>
                                    <small class="text-muted">Price</small>
                                </div>
                                <div class="col-6">
                                    <div class="<?= $changeClass ?> fw-bold">
                                        <i class="fas <?= $icon ?>"></i> <?= number_format($priceChange, 2) ?>%
                                    </div>
                                    <small class="text-muted">24h</small>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="text-primary">$<?= number_format($coin['market_cap']/1e9, 1) ?>B</div>
                                    <small class="text-muted">Market Cap</small>
                                </div>
                                <div class="col-6">
                                    <div class="text-success"><?= number_format($coin['total_volume']/1e6, 0) ?>M</div>
                                    <small class="text-muted">Volume</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>