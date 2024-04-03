<div class="position-fixed top-0 end-0 p-3" style="z-index: 101">
    <div id="toast-message" class="toast <?php

                                            use App\utils\Helper;

                                            echo (Helper::getFromSession('status')) ? 'show' : '';
                                            $textClass = "text-" . Helper::getOnceFromSession('status');
                                            ?>" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto <?= $textClass ?>">
                <?php
                if ($textClass == "text-danger") {
                    echo "Thất bại";
                } elseif ($textClass == "text-success") {
                    echo "Thành công";
                } elseif ($textClass == "text-warning") {
                    echo "Cảnh báo";
                } elseif ($textClass == "text-info") {
                    echo "Thông báo";
                }
                ?>
            </strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body <?= $textClass ?>">
            <?= Helper::getOnceFromSession('message') ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="delete-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Xác nhận</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                Bạn có chắc chắn muốn xóa thông tin này không?
          </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button id="confirm-submit" type="button" class="btn btn-primary">Đồng ý</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="/assets/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/assets/fontawesome/css/all.min.css"></script>
<script src="/assets/fontawesome/js/all.min.js"></script>
<script src="/assets/js/index.js"></script>
</body>

</html>