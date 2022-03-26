<?php

/** @var $exception \Exception */
?>
<?php $this->start('content'); ?>
<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3 text-danger"><?= $this->error->getCode() ?> | </h1>
            <h1 class="fw-bold lh-1 mt-3"><?= $this->error->getMessage() ?></h1>
            <p class="col-lg-10 fs-4"><i class="fas fa-smile-wink fa-x3"></i></p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <h1>Oops! &spades;</h1>
            <a href="/" class="btn btn-primary btn-lg">Back to Home</a>
        </div>
    </div>
</div>
<?php $this->end(); ?>