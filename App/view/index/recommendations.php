<?php
/**
 * @var \Google_Service_Books_VolumeVolumeInfo[] $books
 */
?>

<?php foreach ($books as $book):
    if (!$book->getImageLinks()) {
        continue;
    }

    ?>
    <div class="panel panel-primary">
        <div class="panel-heading"><?= $book->getTitle() ?></div>
        <div class="panel-body">
            <div class="media">
                <div class="media-left">
                    <a href="<?= $book->getPreviewLink() ?>" target="_blank">
                        <img class="media-object" src="<?= $book->getImageLinks()->getThumbnail() ?>" alt="<?= $book->getTitle() ?>">
                    </a>
                </div>
                <div class="media-body">
                    <?= $book->getDescription() ?>
                    <hr/>
                    <?php if ($book->getAuthors()) :?>
                    <div>
                        Authors: <strong><?= implode(', ', $book->getAuthors()) ?></strong>
                    </div>
                    <?php endif;?>
                    <?php if ($book->getCategories()) :?>
                    <div>
                        Categories: <strong><?= implode(', ', $book->getCategories()) ?></strong>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>


<?php endforeach; ?>
