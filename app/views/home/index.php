<?php

use App\utils\Helper;

require_once __DIR__ . '/../partials/header.php';
require_once __DIR__ . '/../partials/nav.php';

?>

<div id="main" class="d-flex justify-content-center align-items-center">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-2 offset-2">
                    <form action="/classes" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://cdn-icons-png.flaticon.com/512/906/906175.png" alt="">
                                <h6 class="card-title">Quản lý lớp học</h6>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="col-2">
                    <form action="/rooms" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://cdn-icons-png.flaticon.com/512/3492/3492080.png" alt="">
                                <h6 class="card-title">Quản lý phòng học</h6>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="col-2">
                    <form action="/statistics" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://w7.pngwing.com/pngs/41/691/png-transparent-bar-chart-statistics-computer-icons-business-statistics-text-presentation-statistics-thumbnail.png" alt="">
                                <h6 class="card-title">Thống kê</h6>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="col-2">
                    <form action="/users" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/1024px-User_icon_2.svg.png" alt="">
                                <h6 class="card-title">Quản lý người dùng</h6>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-2 offset-2">
                    <form action="/teachers" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://cdn.iconscout.com/icon/premium/png-256-thumb/banking-time-3071132-2552439.png" alt="">
                                <h6 class="card-title">Quản lý giáo viên</h6>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="col-2">
                    <form action="/students" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://cdn-icons-png.flaticon.com/512/354/354637.png" alt="">
                                <h6 class="card-title">Quản lý học sinh</h6>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="col-2">
                    <form action="/marks" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://cdn2.iconfinder.com/data/icons/sports-and-games-vol-01-7/32/score-scorecard-paper-pad-4096.png" alt="">
                                <h6 class="card-title">Quản lý điểm</h6>
                            </div>
                        </button>
                    </form>
                </div>

                <div class="col-2">
                    <form action="/others" method="GET">
                        <button type="submit" class="card card--hover rounded-4">
                            <div class="card-body p-1 text-center">
                                <img class="img-fluid" src="https://cdn-icons-png.freepik.com/512/8637/8637645.png" alt="">
                                <h6 class="card-title">Chức năng khác</h6>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const fields = {
        'teacher_id': 'teacher_id',
        'full_name': 'full_name',
        'date_of_birth': 'date_of_birth',
        'phone_number': 'phone_number',
        'address': 'address'
    };
    const formId = 'teacher_form';
    const trClass = 'teacher';
</script>

<?php

require_once __DIR__ . '/../partials/footer.php';
