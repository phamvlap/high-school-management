<?php

use App\utils\Helper;
?>
<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item <?php echo $pagination['prevPage'] ? '' : 'disabled' ?>">
                <a class="page-link" href="/<?= Helper::getPrefixUrl() ?>?page=<?= $pagination['prevPage'] ?>&limit=10" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php foreach ($pagination['pages'] as $page) : ?>
                <li class="page-item">
                    <a class="page-link" href="/<?= Helper::getPrefixUrl() ?>?page=<?= $page ?>&limit=10" <?php
                                                                                                            if ($page == $pagination['currPage']) {
                                                                                                                echo 'style="background-color: #007bff; color: white;"';
                                                                                                            }
                                                                                                            ?>>
                        <?= $page ?>
                    </a>
                </li>
            <?php endforeach ?>

            <li class="page-item <?php echo $pagination['nextPage'] ? '' : 'disabled' ?>">
                <a class="page-link" href="/<?= Helper::getPrefixUrl() ?>?page=<?= $pagination['nextPage'] ?>&limit=10" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>