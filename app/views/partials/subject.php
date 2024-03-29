
<?php use App\controllers\SubjectController; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống quản lý</title>
    <link rel="stylesheet" href="./assets/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div id="app">
        <div id="header">
            <div class="header-brand">
                <span class="fw-bold text-primary">Hệ thống quản lý Trường THPT Thực hành Sư phạm Cần Thơ</span>
            </div>

            <div class="header-homepage">
                <a href="index.html">
                    <i class="fa fa-home"></i>
                </a>
            </div>
        </div>

        <div id="nav">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-body-tertiary">
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="#" class="nav-link active" aria-current="page">
                            <i class="fa fa-home"></i>
                            Quản lý lớp học
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link link-body-emphasis">
                            <i class="fa fa-tachometer-alt"></i>
                            Quản lý giáo viên
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link link-body-emphasis">
                            <i class="fa fa-users"></i>
                            Quản lý sinh viên
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link link-body-emphasis">
                            <i class="fa-solid fa-pen"></i>
                            Quản lý điểm
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link link-body-emphasis">
                            <i class="fa-solid fa-book"></i>
                            Quản lý môn học
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link link-body-emphasis">
                            <i class="fa fa-sign-out-alt"></i>
                            Đăng xuất
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div id="main" class="">
            <div class="row">
                <div class="col-3 border-end">
                    <form action="">
                        <span class="fw-bold" style="font-size: 1rem;">Nhập thông tin Môn học</span>
                        <div class="mb-1">
                            <label for="name" class="form-label mb-0">Tên môn học</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-1">
                            <label for="grade" class="form-label mb-0">Khối</label>
                                <select class="form-select" id="class" name="class">
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                        </div>
                        <div class="d-flex mt-2">
                            <button type="reset" class="ms-auto me-2 px-3 btn btn-sm btn-outline-danger">
                                Hủy
                            </button>
                            <button type="submit" class="px-3 btn btn-sm btn-outline-primary">
                                Lưu
                            </button>
                        </div>
                    </form>

                    <!-- Thêm button cho phép them cùng lúc nhiều dữ liệu từ file excel -->
                    <div class="d-flex mt-5">
                        <button class="ms-auto px-3 btn btn-sm btn-outline-success">
                            Thêm từ file excel
                        </button>
                    </div>
                </div>
                <div class="col-9">
                    <!-- Hiển thị thông tin tất cả học sinh từ cơ sở dữ liệu kèm theo 2 button xem, sửa và xóa (sử dụng fa icon), ẩn địa chỉ-->
                    <!-- Hiển thị thanh filter và search -->
                    <form class="d-flex justify-content-between">
                        <div class="d-flex">
                            <div class="mb-1">
                                <label for="class" class="form-label mb-0">Lọc theo khối</label>
                                <select class="form-select" id="class" name="class">
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-1">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Tìm kiếm"
                                    aria-label="Recipient's username" aria-describedby="button-addon2">
                                <button class="btn btn-outline-primary" type="button" id="button-addon2">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <table class="table table-striped table-hover mt-2">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Mã môn</th>
                                <th scope="col">Tên môn</th>
                                <th scope="col">Khối</th>
                                <th scope="col" class="text-end ms-auto">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
						$uri = $_SERVER['REQUEST_URI'];
					?>
                  
                  <?php foreach($_SESSION['subjeccts'] as $subject): ?>
								<div class="col-md-3 mt-3 px-2">
									<div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo $subject['name'] ?></h5>
                                            <p class="card-text">Khối: <?php echo $subject['grade'] ?></p>
                                            <p class="card-text">Mã môn: <?php echo $subject['id'] ?></p>
                                            <a href="<?php echo $uri . '/' . $subject['id'] . '/edit' ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="<?php echo $uri . '/' . $subject['id'] . '/delete' ?>" class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>

								</div>
							<?php endforeach ?>
                            <tr>
                                <th scope="row">1</th>
                                <td>2</td>
                                <td>Lí</td>
                                <td>11</td>
                                <td class="text-end ms-auto">
                                   
                                    <button class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>3</td>
                                <td>Sinh học</td>
                                <td>12</td>
                                <td class="text-end ms-auto">
                                   
                                    <button class="btn btn-sm btn-outline-warning">
                                        <i class="fa fa-edit">
                                        </i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

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

        <div id="footer">
            <p class="p-0 m-0">
                &copy; 2023 - 2024 High School Manangement System
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/452e9e3684.js" crossorigin="anonymous"></script>
</body>

</html>