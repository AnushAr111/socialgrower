<?php
/**
 * @var \App\Core\View $view
 * @var string $content
 */

use App\Core\App;
use App\Core\Url;

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $view->vars['title'] ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>

<body>
<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Book Recommendations</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <?php if (App::$session->has('access_token')) :?>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?= Url::to('/recommendations') ?>">Recommendations <span class="sr-only">(current)</span></a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="<?= Url::to('/logout') ?>">Logout</a></li>
                    </ul>

                <?php else: ?>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?= Url::to('/login') ?>">Login <span class="sr-only">(current)</span></a></li>
                    </ul>
                <?php endif; ?>
            </div>

        </div>
    </nav>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= $view->vars['title'] ?></h3>
        </div>
        <div class="panel-body">
            <?= $content ?>
        </div>
    </div>
</div>
</body>
</html>
