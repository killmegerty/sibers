<?= $this->element('logout_button') ?>
<?php if ($this->get('userRating')) : ?>
  <div class="">
    Rating: <?= $this->get('userRating')['rating'] ?>
  </div>
<?php else : ?>
  <div class="">
    Rating: no rating
  </div>
<?php endif; ?>

<?php if (!$this->get('inQueue')) : ?>
  <form class="" action="/game/duels" method="post">
    <button type="submit" name="startDuel">Start Duel</button>
  </form>
<?php else : ?>
  In Queue...
  <form class="" action="/game/duels" method="post">
    <button type="submit" name="quitQueue">Quit Queue</button>
  </form>
<?php endif; ?>
