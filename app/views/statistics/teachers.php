<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="d-flex flex-column justify-content-center align-items-center">
    <div class="row">
        <div class="col-6 offset-3">
            <div class="card px-5 py-5" style="font-size: 1.3em;">
                <div class="row mt-0">
                    <div class="col-12">
                        <form action="/statistics/teachers" method="GET">
                            <h3 class="text-center text-primary">Thống kê giáo viên</h3>
                            <div class="row align-items-end">
                                <div class="form-group col-7">
                                    <label for="academic_year">Năm học</label>
                                    <input type="text" class="form-control" id="academic_year" name="academic_year" placeholder="Nhập năm học">
                                </div>
                                <div class="col-4 offset-1">
                                    <button type="submit" class="btn btn-outline-primary">Thống kê</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-12 mt-3">
                        <h4 class="text-center text-primary">Kết quả</h4>
                        <div class="row">
                            <div class="col-9">Tổng số giáo viên: </div>
                            <div class="col-3"><?= $totalTeachers ?></div>
                            <div class="col-9">Tổng số giáo viên chủ nhiệm: </div>
                            <div class="col-3"><?= $totalHomeroomTeachers ?></div>
                            <div class="col-9">Tổng số giáo viên không chủ nhiệm: </div>
                            <div class="col-3"><?= $totalTeachers - $totalHomeroomTeachers ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php

require_once __DIR__ . '/../partials/footer.php';
