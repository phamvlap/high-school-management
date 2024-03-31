<?php

use App\utils\Helper;


require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';
?>

<div id="main" class="">
    <div class="row">
        <div class="col-3 border-end" id="addClassForm">
            <form action="/classes/store" method="POST">
                <span class="fw-bold" style="font-size: 1rem;">Thông tin lớp học</span>
                <input type="hidden" name="class_id" id="class_id" value="-1">
                <div class="mb-1">
                    <label for="class_name" class="form-label mb-0">Tên lớp<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="class_name" name="class_name" value="<?= Helper::?>">
                </div>
                <div class="mb-1">
                    <label for="room_id" class="form-label mb-0">Mã phòng<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="room_id" name="room_id">
                </div>
                <div class="mb-1">
                    <label for="teacher_id" class="form-label mb-0">Giáo viên chủ nhiệm<span style="color: red;"> *</span></label>
                    <input type="text" class="form-control" id="teacher_id" name="teacher_id">
                </div>
                <div class="mb-1 row">
                    <span class="col">
                        <label for="semester" class="form-label mb-0">Học kỳ<span style="color: red;"> *</span></label>
                        <select class="form-select" id="semester" name="semester">
                            <option value="1" selected>Học kỳ</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                        </span>
                        <span class="col">
                            <label for="academic_year" class="form-label mb-0">Năm học<span style="color: red;"> *</span></label>
                             <input type="text" class="form-control" id="academic_year" name="academic_year">
                        </span>
                </div>
                <div class="d-flex mt-3">
                    <button type="reset" class="ms-auto me-2 px-3 btn btn-sm btn-outline-danger">
                        Hủy
                    </button>
                    <button type="submit" class="px-3 btn btn-sm btn-outline-primary">
                        Lưu
                    </button>
                </div>
            </form>

            <div class="d-flex mt-5">
                <button class="ms-auto px-3 btn btn-sm btn-outline-success">
                    Thêm từ file excel
                </button>
            </div>
        </div>

       
        <div class="col-9">
            <form class="d-flex justify-content-between">
                <div class="d-flex">
                    <div class="mb-1">
                        <label for="teacher_dob_sort" class="form-label mb-0">Lọc theo năm sinh</label>
                        <br>
                        <input type="date" class="form-control" name="teacher_dob_sort" id="teacher_dob_sort">
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="school-year" class="form-label mb-0">Lọc theo năm học</label>
                        <select class="form-select" id="school-year" name="school-year">
                            <option selected>Năm học</option>
                            <option value="2023-2024">2023-2024</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026">2025-2026</option>
                        </select>
                    </div>
                    <div class="ms-1 mb-1">
                        <label for="sort" class="form-label mb-0">Sắp xếp</label>
                        <select class="form-select" id="sort" name="sort">
                            <option selected>Họ tên</option>
                            <option value="name-asc">Tên A-Z</option>
                            <option value="name-desc">Tên Z-A</option>
                        </select>
                    </div>
                </div>
                <div class="mt-2 align-self-center">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm" aria-label="Recipient's username" aria-describedby="button-addon2">
                        <button class="btn btn-outline-primary" type="button" id="button-addon2">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <table class="table table-striped table-hover mt-2">
                <thead>
                    <tr>
                        <th scope="col">Mã lớp</th>
                        <th scope="col">Tên lớp</th>
                        <th scope="col">Giáo viên chủ nhiệm</th>
                        <th scope="col">Phòng học</th>
                        <th scope="col">Học kỳ</th>
                        <th scope="col">Năm học</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($classes as $class) : ?>
                            <td><?= Helper::htmlEscape((string)$class['class_id']) ?></td>
                            <td><?= Helper::htmlEscape((string)$class['class_name']) ?></td>
                            <td><?= Helper::htmlEscape((string)$class['full_name']) ?></td>
                            <td><?= Helper::htmlEscape((string)$class['room_number']) ?></td>
                            <td><?= Helper::htmlEscape((string)$class['semester']) ?></td>
                            <td><?= Helper::htmlEscape((string)$class['academic_year']) ?></td>
                            <td>
                                <button class="btn btn-sm ">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-sm" onclick="showEditForm()">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm" id="delete_button" data-class-id="<?= Helper::htmlEscape((string)$class['class_id']) ?>">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>


            <!-- Thêm phân trang -->
            <div class="d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>


        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#delete_button").click(function(){
            var class_id = $(this).data('class-id'); 
            callDeleteClass(class_id);
        });
    });

    function callDeleteClass(class_id) {
        var url = '/classes/' + class_id + '/delete';
        $.ajax({
            url: url,
            type: 'POST',
            success: function(response){
                alert(response);
            }
        });
    }
</script>

<?php

require_once __DIR__ . '/../partials/footer.php';


