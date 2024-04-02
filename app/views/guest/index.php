<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';

?>

<div id="main" class="main--guest">
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="card guest-form">
            <div class="card-header">
                <h4 class="text-center text-primary">TRA CỨU ĐIỂM, KẾT QUẢ HỌC TẬP</h4>
            </div>
            <div class="card-body">
                <form action="/guest/submit" method="POST">
                    <div class="mb-3">
                        <label for="parent_phone_number" class="form-label fw-bold">Số điện thoại của phụ huynh</label>
                        <input type="username" class="form-control" id="parent_phone_number" name="parent_phone_number" required>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-info">Tra cứu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column justify-content-center align-items-center mt-4">
        <?php if(isset($_SESSION['markTable'])): ?>
        <div class="mark-table p-2">
            <div class="row p-2">
                <h4 class="text-center">Bảng điểm học sinh</h4>
                <div class="col-md-4">
                    <div class="info-section p-2 h-100 rounded-2">
                        <h5 class="text-center">Thông tin học sinh</h5>
                        <div>
                            <div>
                                <span>Mã học sinh:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['student']['id'] ?? '') ?></span>
                            </div>
                            <div>
                                <span>Họ tên:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['student']['full_name'] ?? '') ?></span>
                            </div>
                            <div>
                                <span>Ngày sinh:</span>
                                <span><?= Helper::htmlEscape(Helper::formatDate($_SESSION['markTable']['student']['date_of_birth'] ?? '') ?? '') ?></span>
                            </div>
                            <div>
                                <span>Địa chỉ:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['student']['address'] ?? '') ?></span>
                            </div>
                            <div>
                                <span>Số điện thoại phụ huynh:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['student']['parent_phone_number'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-section p-2 h-100 rounded-2">
                        <h5 class="text-center">Thông tin lớp học</h5>
                        <div>
                            <div>
                                <span>Lớp:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['class']['name'] ?? '') ?></span>
                            </div>
                            <div>
                                <span>Niên khóa:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['class']['academic_year'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-section p-2 h-100 rounded-2">
                        <h5 class="text-center">Giáo viên chủ nhiệm</h5>
                        <div>
                            <div>
                                <span>Mã giáo viên:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['teacher']['id'] ?? '') ?></span>
                            </div>
                            <div>
                                <span>Họ tên:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['teacher']['full_name'] ?? '') ?></span>
                            </div>
                            <div>
                                <span>Số điện thoại:</span>
                                <span><?= Helper::htmlEscape($_SESSION['markTable']['teacher']['phone_number'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">Học kì</th>
                        <th scope="col">Mã môn</th>
                        <th scope="col">Tên môn</th>
                        <th scope="col">Điểm miệng</th>
                        <th scope="col">Điểm 15 phút</th>
                        <th scope="col">Điểm1 tiết</th>
                        <th scope="col">Điểm cuối kì</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach($_SESSION['markTable']['marks'] as $mark): ?>
                        <tr class="room">
                            <td scope="row" class="room_id">
                                <?= Helper::htmlEscape($mark['semester'] ?? '') ?>
                            </td>
                            <td scope="row" class="room_id">
                                <?= Helper::htmlEscape($mark['subject_id'] ?? '') ?>
                            </td>
                            <td scope="row" class="room_id">
                                <?= Helper::htmlEscape($mark['subject_name'] ?? '') ?>
                            </td>
                            <td class="room_number">
                                <?= Helper::htmlEscape($mark['mark_oral_score'] ?? '') ?>
                            </td>
                            <td class="room_number">
                                <?= Helper::htmlEscape($mark['mark__15_minutes_score'] ?? '') ?>
                            </td>
                            <td class="room_number">
                                <?= Helper::htmlEscape($mark['mark__1_period_score'] ?? '') ?>
                            </td>
                            <td class="room_number">
                                <?= Helper::htmlEscape($mark['mark_semester_score'] ?? '') ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    
                </tbody>
            </table>
        </div>
        <?php endif ?>
    </div>
</div>
<script>
    const formId = 'guest-form';
    const trClass = 'none';
</script>

<?php
Helper::removeFromSession('markTable');
require_once __DIR__ . '/../partials/footer.php';
