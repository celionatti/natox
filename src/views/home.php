<?php


?>

<?php $this->start('content'); ?>
<h1>Welcome <?= $this->name ?></h1>
<div class="col d-flex justify-content-between align-items-center p-3">
    <h5 class="text-danger"><i class="ri-sun-line sun-icon ri-2x"></i> Year of project innovation: <?= $this->age ?></h5>
    <h5 class="text-primary fw-bold">Owner/manager/president: <?= $this->author ?></h5>
</div>
<h1>Testing Ajax for Framework</h1>

<button id="btn" class="btn btn-primary w-100 mt-3">Get Text File</button>
<?php $this->end(); ?>