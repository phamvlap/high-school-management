<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';

?>

<div id="main" class="main--guest">
    <div class="d-flex flex-column justify-content-center align-items-center">
        <div class="card guest-form">
            <div class="card-header">
                <h4 class="text-center text-primary">TRA CỨU KẾT QUẢ HỌC TẬP</h4>
            </div>
            <div class="card-body">
                <form action="/guest/submit" method="POST">
                    <div class="mb-3">
                        <label for="student_id" class="form-label fw-bold">Mã học sinh</label>
                        <input type="username" class="form-control" id="student_id" name="student_id" required>
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
            <?php foreach($_SESSION['markTable'] as $studentInfo): ?>
                <div class="mark-table p-2 mb-2">
                    <div class="row p-2">
                        <h4 class="text-center">Bảng điểm học sinh</h4>
                        <div class="col-md-7">
                            <div class="info-section p-2 h-100 rounded-2">
                                <h5 class="text-center">Thông tin học sinh</h5>
                                <div class="d-inline">
                                    <div>
                                        <span class="fw-bold">Mã học sinh:</span>
                                        <span><?= Helper::htmlEscape($studentInfo['info']['id'] ?? '') ?></span>
                                    </div>
                                    <div>
                                        <span class="row">
                                            <span class="col-7">
                                                <span class="fw-bold">Họ tên:</span>
                                                <span><?= Helper::htmlEscape($studentInfo['info']['full_name'] ?? '') ?></span>
                                            </span>
                                            <span class="col-5">
                                                <span class="fw-bold">Lớp:</span>
                                                <span><?= Helper::htmlEscape($studentInfo['class']['name'] ?? '') ?></span>
                                            </span>
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <span class="row">
                                            <span class="col-7">
                                                <span class="fw-bold">Địa chỉ:</span>
                                                <span><?= Helper::htmlEscape($studentInfo['info']['address'] ?? '') ?></span>
                                            </span>
                                            <span class="col-5">
                                            <span class="fw-bold">Niên khóa:</span>
                                                <span><?= Helper::htmlEscape($studentInfo['class']['academic_year'] ?? '') ?></span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="info-section p-2 h-100 rounded-2">
                                <h5 class="text-center">Giáo viên chủ nhiệm</h5>
                                <div>
                                    <!-- <div>
                                        <span>Mã giáo viên:</span>
                                        <span><?= Helper::htmlEscape($studentInfo['teacher']['id'] ?? '') ?></span>
                                    </div> -->
                                    <div>
                                        <span class="fw-bold">Họ tên:</span>
                                        <span><?= Helper::htmlEscape($studentInfo['teacher']['full_name'] ?? '') ?></span>
                                    </div>
                                    <div>
                                        <span class="fw-bold">Số điện thoại:</span>
                                        <span><?= Helper::htmlEscape($studentInfo['teacher']['phone_number'] ?? '') ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <?php foreach($studentInfo['marks'] as $semester => $marks): ?>
                            <h5 class="text-start p-2 border-bottom">ĐIỂM HỌC KỲ <?= Helper::htmlEscape($semester) ?></h5>
                            <table class="table table-striped table-hover mt-2">
                                <thead>
                                    <tr>
                                        <th scope="col">Mã môn</th>
                                        <th scope="col">Tên môn</th>
                                        <th scope="col">Điểm miệng</th>
                                        <th scope="col">Điểm 15 phút</th>
                                        <th scope="col">Điểm 1 tiết</th>
                                        <th scope="col">Điểm cuối kì</th>
                                        <th scope="col">Điểm trung bình</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($marks as $mark): ?>
                                        <tr class="room">
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
                                            <td>
                                                <?php 
                                                 // Tính điểm trung bình của từng môn, kết quả làm tròn 2 chữ số thập phân và lưu kết quả vừa tính vào biến mảng $mark['average_score']
                                                $avegare_score = $mark['mark_oral_score'] + $mark['mark__15_minutes_score'] + $mark['mark__1_period_score']*2 + $mark['mark_semester_score']*3;
                                                echo round($avegare_score/7,2);
                                                $average_scores[] = round($avegare_score/7,2);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                    <tr class="p-30">
                                        <td colspan="1"></td>
                                        <td colspan="5" class="text-left ml-3 col-offset-3"><b>Điểm trung bình học kỳ:</b></td>
                                        <td class="fw-bold">
                                            <?php
                                                $total_average = array_sum($average_scores) / count($average_scores);
                                                echo round($total_average, 2);
                                                $total_averages[] = $total_average;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="p-30">
                                        <td colspan="1"></td>
                                        <td colspan="5" class="text-left ml-3 col-offset-3"><b>Điểm trung bình cả năm:</b></td>
                                        <td class="fw-bold">
                                            <?php
                                                $year_average = array_sum($total_averages) / count($total_averages);
                                                echo round($year_average, 2);
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php endforeach ?>

                        
                    </div>

                    
                </div>
            <?php endforeach ?>
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
