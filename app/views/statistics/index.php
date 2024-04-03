<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

$listItems = [
    [
        'icon' => 'assets/img/teachers.png',
        'title' => 'Thống kê giáo viên',
        'link' => '/statistics/teachers'
    ],
    [
        'icon' => 'assets/img/students.png',
        'title' => 'Thông kê học sinh',
        'link' => '/statistics/students'
    ],
    [
        'icon' => 'assets/img/marks.png',
        'title' => 'Thống kê điểm',
        'link' => '/statistics/marks'
    ],
];

?>

<div id="main" class="d-flex flex-column justify-content-center align-items-center">
    <div class="row mt-0">
        <div class="col">
            <h3 class="text-center text-primary">Các chức năng thống kê</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php foreach ($listItems as $key => $item) : ?>
                <?= $key % 3 === 0 ? '<div class="row mt-3 ?>">' : '' ?>
                <div class="col-2 <?php if ($key % 3 == 0) echo 'offset-3' ?>">
                    <form action="<?= $item['link'] ?>" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="<?= $item['icon'] ?>" alt="Chức năng">
                                <h6 class="card-title"><?= $item['title'] ?></h6>
                            </div>
                        </button>
                    </form>
                </div>
                <?= $key % 3 === 2 ? '</div>' : '' ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php

require_once __DIR__ . '/../partials/footer.php';
