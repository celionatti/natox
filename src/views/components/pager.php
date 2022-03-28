<?php

use NatoxCore\Request;


$request = new Request();
$page = $request->get('p');
if (!$page || $page < 1) $page = 1;
$limit = $request->get('limit') ? $request->get('limit') : 25;
$totalPages = ceil($this->total / $limit);
$canBack = $page > 1;
$canForward = $page < $totalPages;
?>

<form action="" method="get" id="pagerForm" onsubmit="return false;">
    <div class="d-flex justify-content-center align-items-center my-5 pagination">
        <input type="hidden" id="p" name="p" value="<?= $page ?>" />
        <input type="hidden" name="limit" value="<?= $limit ?>" />

        <button class="btn btn-sm btn-outline-primary" <?= $canBack ? "" : "disabled" ?> onclick="pager(<?= $page - 1 ?>)">
            Older </button>
        <div class="mx-3"><?= $page ?> / <?= $totalPages ?></div>
        <button class="btn btn-sm btn-outline-primary" <?= $canForward ? "" : "disabled" ?> onclick="pager(<?= $page + 1 ?>)">Newer</button>
    </div>
</form>

<script>
    function pager(page) {
        document.getElementById('p').value = page;
        var form = document.getElementById('pagerForm');
        form.submit();
    }
</script>