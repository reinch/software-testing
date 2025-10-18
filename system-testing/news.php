<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Berita - Berita Luar Negeri</title>
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
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2);
    }
    .card-body { color: #333; }
    .btn-readmore { color: #C62828; text-decoration: none; font-weight: 600; }
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
        <li class="nav-item active"><a class="nav-link" href="news.php">News</a></li>
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4">
    <h3 class="mb-4 text-center text-danger">Semua Berita</h3>
    <?php
      $url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=f4bbd4b3e4ee49eea1ffe00502212047";
      $response = @file_get_contents($url);
      $data = json_decode($response, true);

      if(isset($data['articles'])){
        foreach($data['articles'] as $article){
          echo '
          <div class="card">
            <div class="row no-gutters">
              <div class="col-md-4">
                <img src="'.$article['urlToImage'].'" class="card-img" alt="...">
              </div>
              <div class="col-md-8">
                <div class="card-body">
                  <h5>'.$article['title'].'</h5>
                  <p><em>Oleh '.$article['author'].' - '.$article['source']['name'].'</em></p>
                  <p>'.substr($article['description'], 0, 120).'...</p>
                  <a href="'.$article['url'].'" target="_blank" class="btn-readmore">Baca Selengkapnya..</a>
                </div>
              </div>
            </div>
          </div>';
        }
      } else {
        echo "<p>Tidak ada berita tersedia.</p>";
      }
    ?>
  </div>

  <footer>
    <p>&copy; <?= date('Y'); ?> Berita Luar Negeri</p>
  </footer>
</body>
</html>
