<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="d-flex flex-column justify-content-center align-items-center">
    <div class="row">
        <div class="col-12">
            <div class="card px-5 py-5" style="font-size: 1.3em;">
                <div class="row mt-0">
                    <div class="col-12">
                        <form action="/statistics/students" method="GET">
                            <h3 class="text-center text-primary">Thống kê học sinh</h3>
                            <div class="row align-items-end">
                                <div class="form-group col-3">
                                    <label for="academic_year">Năm học</label>
                                    <input type="text" class="form-control" id="academic_year" name="academic_year" placeholder="Nhập năm học" value="<?= $_GET['academic_year'] ?? '' ?>">
                                </div>
                                <div class="form-group col-3">
                                    <label for="grade">Khối</label>
                                    <select class="form-control" id="grade" name="grade">
                                        <?php $grade = $_GET['grade'] ?? ''; ?>
                                        <option value="" <?= $grade === '' ? 'selected' : '' ?>>Tất cả</option>
                                        <option value="10" <?= $grade === '10' ? 'selected' : '' ?>>10</option>
                                        <option value="11" <?= $grade === '11' ? 'selected' : '' ?>>11</option>
                                        <option value="12" <?= $grade === '12' ? 'selected' : '' ?>>12</option>
                                    </select>
                                </div>
                                <div class="form-group col-3">
                                    <label for="class_id">Mã lớp</label>
                                    <input type="text" class="form-control" id="class_id" name="class_id" placeholder="Nhập mã lớp" value="<?= $_GET['class_id'] ?? '' ?>">
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
                            <div class="col-9">Tổng số học sinh: </div>
                            <div class="col-3"><?= $totalStudents ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

require_once __DIR__ . '/../partials/footer.php';
