<?php
declare(strict_types=1);

use php_part\exceptions\RepositoryException;
use php_part\repositories\ArticleDbRepository;

require __DIR__ . '/../bootstrap.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Invalid request';
    exit;
}

try {
    $article = (new ArticleDbRepository())->findById((int) $_GET['id']);
} catch (RepositoryException $e) {
    http_response_code(400);
    echo 'Article not found';
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($article->title); ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .article img {
            max-width: 100%;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="offset-md-2 col-md-8 article">
        <h1><?php echo htmlspecialchars($article->title); ?></h1>
        <i class="article-published-date"><?php echo $article->published_at; ?></i>
        <div class="article-content">
            <?php echo $article->content; ?>
        </div>
    </div>
</div>
</body>
</html>

