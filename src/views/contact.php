<?php

use NatoxCore\form\Form;


?>

<?php $this->start('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto shadow p-2">
        <div class="card card-body bg-light mt-3">
            <div class="d-flex align-items-center">
                <a href="/" class="text-start text-muted">Back</a>
                <h2 class="mx-auto">Login Access</h2>
            </div>

            <p class="text-danger border-danger border-bottom border-3">Please fill in your crendentials to log in
            <p>
            <form action="" method="post">
                <?= Form::csrfField(); ?>
                <?= Form::inputBlock('E-Mail', 'email', '', ['class' => 'form-control', 'type' => 'email'], ['class' => 'col mb-3'], $this->errors); ?>

                <?= Form::inputBlock('Password', 'password', '', ['class' => 'form-control', 'type' => 'password'], ['class' => 'col mb-3'], $this->errors); ?>

                <div class="row">
                    <div class="col">
                        <?= Form::check('Remember Me', 'remember', '', ['class' => 'form-check-input'], ['class' => 'mb-3 form-check'], $this->errors); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                    <div class="col">
                        <a href="/register" class="btn btn-light w-100">Not a Member yet ? Register</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end(); ?>