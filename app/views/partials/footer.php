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

<div id="footer">
    <p class="p-0 m-0">
        &copy; 2023 - 2024 High School Manangement System
    </p>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/452e9e3684.js" crossorigin="anonymous"></script>
<script src="/assets/js/index.js"></script>
</body>

</html>