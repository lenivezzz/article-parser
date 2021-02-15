<?php
declare(strict_types=1);

use php_part\repositories\ArticleDbRepository;

require __DIR__ . '/../bootstrap.php';

$articlesList = (new ArticleDbRepository())->findAll(40);

?>

<!doctype html>
<html lang="en">
<head>
    <title>Список статей</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .card {
            margin-bottom: 2rem;
        }

        .card-body {
            margin-bottom: 2rem;
        }

        .card-body-btn-details {
            position: absolute;
            bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Список статей</h1>
    <?php foreach (array_chunk($articlesList, 3) as $articleChunk) { ?>
        <div class="row">
            <?php foreach ($articleChunk as $article) { ?>
                <div class="col-md-4 d-flex">
                    <div class="card">
                        <?php $imageSrc = $article->image_src ?: 'https://dummyimage.com/1180x730/cfcfcf/808080.png&text=No+image'; ?>
                        <img src="<?php echo $imageSrc; ?>" class="card-img-top" alt="" />
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($article->title); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($article->announce)?></p>
                            <a href="<?php echo getenv('APP_URL') . '/view.php?id=' . $article->id; ?>" class="btn btn-link card-body-btn-details">Подробнее</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
</body>
</html>
