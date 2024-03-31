<?php

use App\utils\Helper;

$queryArray = [];
foreach ($filter as $key => $value) {
    if ($value) {
        $queryArray[$key] = $value;
    }
}
$queryString = implode('&', array_map(fn ($key, $value) => "$key=$value", array_keys($queryArray), $queryArray));
$url = "/" . Helper::getPrefixUrl() . "?";
if (strlen($queryString) > 0) {
    $url = "/" . Helper::getPrefixUrl() . "?$queryString&";
}
?>
<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item <?php echo $pagination['prevPage'] ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $url ?>page=<?= $pagination['prevPage'] ?>&limit=<?= $_GET['limit'] ?? MAX_RECORDS_PER_PAGE ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php foreach ($pagination['pages'] as $page) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $url ?>page=<?= $page ?>&limit=<?= $_GET['limit'] ?? MAX_RECORDS_PER_PAGE ?>" <?php
                                                                                                                                    if ($page == $pagination['currPage']) {
                                                                                                                                        echo 'style="background-color: #007bff; color: white;"';
                                                                                                                                    }
                                                                                                                                    ?>>
                        <?= $page ?>
                    </a>
                </li>
            <?php endforeach ?>

            <li class="page-item <?php echo $pagination['nextPage'] ? '' : 'disabled' ?>">
                <a class="page-link" href="<?= $url ?>page=<?= $pagination['nextPage'] ?>&limit=<?= $_GET['limit'] ?? MAX_RECORDS_PER_PAGE ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>