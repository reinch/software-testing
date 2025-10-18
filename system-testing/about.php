<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang - Berita Luar Negeri</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">

<style>
    body {
      background-color: #F4F6F7;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar {
      background-color: #B71C1C  !important;
    }
    .navbar-brand {
      color: #ffffff !important;
      font-weight: bold;
      font-size: 22px;
    }
    .nav-link{
      color: #FFFFFF !important;
      font-weight: 500;
      margin-left: 15px;   
    }
    .nav-link:hover { color: #FFD54F !important; }
    .card {
      border: 1px solid #E0E0E0;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .card-body { color: #333; }
    .btn-readmore { color: #C62828; text-decoration: none; }
    .btn-readmore:hover { color: #8E0000; text-decoration: underline; }
    footer {
      background-color: #424242;
      color: #EEEEEE;
      text-align: center;
      padding: 12px;
      margin-top: 40px;
    }
</style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="index.php">Berita Luar Negeri</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="news.php">News</a></li>
        <li class="nav-item active"><a class="nav-link" href="about.php">About</a></li>
      </ul>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="card shadow-sm p-4">
      <h3 class="text-danger">Tentang Berita Luar Negeri</h3>
      <p>Website ini dibuat untuk menampilkan berita terkini dari berbagai sumber menggunakan REST API.</p>
      <p>Tujuannya untuk melatih integrasi data eksternal menggunakan PHP dan Bootstrap agar user dapat melihat berita secara cepat dan dinamis.</p>
      <ul>
        <li><b>Teknologi:</b> PHP, JSON, REST API, Bootstrap</li>
        <li><b>Dibuat oleh:</b> Muhammad Ramadhan Putra Bintang</li>
        <li><b>Program Studi:</b> Sistem Informasi</li>
      </ul>
    </div>
  </div>

  <footer>
    <p>&copy; <?= date('Y'); ?> Berita Luar Negeri</p>
  </footer>
</body>
</html>
