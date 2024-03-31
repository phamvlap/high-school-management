<div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
<<<<<<< HEAD
    <div id="toast-message" class="toast 
    <?php
        use App\utils\Helper;

        echo (Helper::getFromSession('status')) ? 'show' : '';
        $textClass = "text-" . Helper::getOnceFromSession('status');
    ?>" 
    role="alert" aria-live="assertive" aria-atomic="true">
=======
    <div id="toast-message" class="toast <?php

                                            use App\utils\Helper;

                                            echo (Helper::getFromSession('status')) ? 'show' : '';
                                            $textClass = "text-" . Helper::getOnceFromSession('status');
                                            ?>" role="alert" aria-live="assertive" aria-atomic="true">
>>>>>>> fcae8fd3445bcf1d1b3699939b7e896f525c2768
        <div class="toast-header">
            <strong class="me-auto <?= $textClass ?>">Thành công</strong>
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
<<<<<<< HEAD
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
=======
<script src="/assets/js/index.js"></script>
>>>>>>> fcae8fd3445bcf1d1b3699939b7e896f525c2768
</body>

</html>