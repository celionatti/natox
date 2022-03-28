<?php


?>

<?php $this->start('content'); ?>

<h1>Welcome To about me page</h1>
<h2>
    <?php foreach ($this->articles as $article) : ?>
        <h2 class="text-primary mb-3 border-danger border-bottom border-3"><?= $article->title ?></h2>
    <?php endforeach; ?>
</h2>
<div class="mt-5 px-3 border border-3 border-success">
    <?php foreach ($this->users as $user) : ?>
        <h2 class="text-primary mb-3 border-danger border-bottom border-3"><?= $user->fname ?> ~ <?= $user->lname ?></h2>
    <?php endforeach; ?>
</div>

<?= $this->partial('pager') ?>

<?php $this->end(); ?>