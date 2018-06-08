<div class="alert alert-danger">
  <?= $e->getMessage() ?>
  <br/>
  <?= nl2br($e->getTraceAsString()) ?>
</div>
