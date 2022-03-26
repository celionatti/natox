<?php

/** @var $exception \Exception */
?>
<?php $this->start('content'); ?>
<div class="container col-xl-12 col-xxl-10 px-2 py-5">
    <div class="col-lg-12 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 mb-3 text-danger"><span class="fw-bold text-info">Oops! &spades;</span> | <?= $this->error->getCode() ?>
        </h1>
        <h1 class="fw-bold lh-1 mt-3"><?= $this->error->getMessage() ?></h1>
        <p class="col-lg-10 fs-4"><i class="fas fa-smile-wink fa-x3"></i></p>
    </div>
    <div class="col-md-12 mx-auto col-lg-12">
        <a href="/" class="btn btn-primary btn-lg w-100">Back to Home</a>
    </div>
</div>
<?php $this->end(); ?>