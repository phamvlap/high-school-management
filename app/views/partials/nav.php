<?php

use App\utils\Helper;

$prefixUrl = '/' . Helper::getPrefixUrl();

$links = [
    [
        'icon' => 'fa fa-home',
        'title' => 'Trang chủ',
        'url' => '/',
    ],
    [
        'icon' => 'fa-solid fa-school',
        'title' => 'Quản lý lớp học',
        'url' => '/classes',
    ],
    [
        'icon' => 'fa-solid fa-person-shelter',
        'title' => 'Quản lý phòng học',
        'url' => '/rooms',
    ],
    [
        'icon' => 'fa fa-tachometer-alt',
        'title' => 'Quản lý giáo viên',
        'url' => '/teachers',
    ],
    [
        'icon' => 'fa fa-users',
        'title' => 'Quản lý sinh viên',
        'url' => '/students'
    ],
    [
        'icon' => 'fa fa-book',
        'title' => 'Quản lý môn học',
        'url' => '/subjects'
    ],
    [
        'icon' => 'fa fa-pen',
        'title' => 'Quản lý điểm',
        'url' => '/marks'
    ],
    // công tác giảng dạy
    // [
    //     'icon' => 'fa fa-chalkboard',
    //     'title' => 'Công tác giảng dạy',
    //     'url' => '/teaching'
    // ],
];


?>

<div id="nav">
    <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
        <ul class="nav nav-pills flex-column mb-auto">
            <?php foreach ($links as $link) : ?>
                <li>
                    <a href="<?= $link['url'] ?>" class="nav-link link-body-emphasis nav-link--hover <?= $link['url'] === $prefixUrl ? 'custom-color-info' : '' ?>">
                        <i class="<?= $link['icon'] ?>"></i> &nbsp;
                        <?= $link['title'] ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <hr>
            <form action="/logout" method="POST" class="d-block w-100">
                <li>
                    <button type="submit" class="nav-link link-body-emphasis text-danger nav-logout--hover w-100 text-start">
                        <i class="fa fa-sign-out-alt"></i> &nbsp;
                        Đăng xuất
                    </button>
                </li>
            </form>
        </ul>
    </div>
</div>