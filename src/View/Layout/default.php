<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <?php if ($this->get('autoReloadPage')) : ?>
      <meta http-equiv="refresh" content="5">
    <?php endif; ?>
    <?php if (!$this->get('autoReloadPage') && $this->get('delayedAttackBtn')) : ?>
      <meta http-equiv="refresh" content="31">
    <?php endif; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/styles.css">
    <title>Test task - skytecgames</title>
  </head>
  <body>
    <div class="game-container">
      <?= $this->content() ?>
    </div>
  </body>
</html>
