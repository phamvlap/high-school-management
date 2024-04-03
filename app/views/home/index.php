<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

$listItems = [
    [
        'icon' => '/assets/img/classes.png',
        'title' => 'Quản lý lớp học',
        'link' => '/classes'
    ],
    [
        'icon' => '/assets/img/rooms.png',
        'title' => 'Quản lý phòng học',
        'link' => '/rooms'
    ],
    [
        'icon' => '/assets/img/roomclass.png',
        'title' => 'Quản lý phòng - lớp',
        'link' => '/roomclass'
    ],
    [
        'icon' => '/assets/img/teachers.png',
        'title' => 'Quản lý giáo viên',
        'link' => '/teachers'
    ],
    [
        'icon' => '/assets/img/students.png',
        'title' => 'Quản lý học sinh',
        'link' => '/students'
    ],
    // Quản lý môn học
    [
        'icon' => '/assets/img/subjects.png',
        'title' => 'Quản lý môn học',
        'link' => '/subjects'
    ],
    [
        'icon' => '/assets/img/marks.png',
        'title' => 'Quản lý điểm',
        'link' => '/marks'
    ],
    [
        'icon' => '/assets/img/statistics.png',
        'title' => 'Thống kê',
        'link' => '/statistics'
    ],
    [
        'icon' => '/assets/img/users.png',
        'title' => 'Quản lý người dùng',
        'link' => '/users'
    ]
];

?>

<div id="main" class="d-flex flex-column justify-content-center align-items-center">
    <div class="row mt-0">
        <div class="col">
            <h1 class="text-center text-primary">Chức năng</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <?php foreach ($listItems as $key => $item) : ?>
                <?= $key % 6 === 0 ? '<div class="row mt-3 ?>">' : '' ?>
                <div class="col-2 ">
                    <form action="<?= $item['link'] ?>" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="<?= $item['icon'] ?>" alt="Chức năng">
                                <h6 class="card-title"><?= $item['title'] ?></h6>
                            </div>
                        </button>
                    </form>
                </div>
                <?= $key % 6 === 5 ? '</div>' : '' ?>
            <?php endforeach; ?>
            <?= count($listItems) % 6 !== 0 ? '</div>' : '' ?>
        </div>
    </div>
</div>

<?php

require_once __DIR__ . '/../partials/footer.php';
