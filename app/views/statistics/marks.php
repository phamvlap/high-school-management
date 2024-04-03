<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="d-flex flex-column justify-content-center align-items-center">
    <div class="row">
        <div class="col-12">
            <div class="card py-3 px-1" style="font-size: 1.3em;">
                <div class="row mt-0">
                    <div class="col-12">
                        <form action="/statistics/marks" method="GET">
                            <h3 class="text-center text-primary">Thống kê điểm</h3>
                            <div class="row align-items-end">
                                <div class="form-group col-3 offset-2">
                                    <label for="class_id">Mã lớp</label>
                                    <input type="text" class="form-control" id="class_id" name="class_id" placeholder="Nhập mã lớp" value="<?= $_GET['class_id'] ?? '' ?>">
                                </div>
                                <div class="form-group col-3">
                                    <label for="semester">Học kì</label>
                                    <?php $semester = $_GET['semester'] ?? '1' ?>
                                    <select class="form-control" id="semester" name="semester">
                                        <option value="1" <?= $semester == '1' ? 'selected' : '' ?>>1</option>
                                        <option value="2" <?= $semester == '2' ? 'selected' : '' ?>>2</option>
                                    </select>
                                </div>
                                <div class="col-2 offset-1">
                                    <button type="submit" class="btn btn-outline-primary">Thống kê</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <h4 class="text-center text-primary">Kết quả</h4>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-hover mt-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">STT</th>
                                            <th scope="col">Họ tên</th>
                                            <th scope="col">Mã số</th>
                                            <?php foreach ($subjects as $subject) : ?>
                                                <?php $subjectName = Helper::htmlEscape($subject['subject_name']) == 'Giáo dục công dân' ? 'GDCD' : Helper::htmlEscape($subject['subject_name']) ?>
                                                <th scope="col"><?= $subjectName ?></th>
                                            <?php endforeach; ?>
                                            <th scope="col">TBHK</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        ?>
                                        <?php foreach ($marks as $mark) : ?>
                                            <tr class="mark">
                                                <td scope="row"><?= $i++ ?></td>
                                                <td><?= Helper::htmlEscape($mark['full_name']) ?></td>
                                                <td><?= Helper::htmlEscape($mark['student_id']) ?></td>
                                                <?php foreach ($subjects as $subject) : ?>
                                                    <td><?= Helper::htmlEscape($mark[$subject['subject_id']]['average_score']) ?></td>
                                                <?php endforeach; ?>
                                                <td><?= Helper::htmlEscape($mark['average_score']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex mt-5">
                                <a href="/excel" class="ms-auto px-3 btn btn-sm btn-outline-success">
                                    Xuất ra file excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

require_once __DIR__ . '/../partials/footer.php';
