<?= $this->element('logout_button') ?>

<?php if ($this->get('playerStatus') == 'ready') : ?>
  <?php if ($this->get('rating')) : ?>
    <div class="">
      Rating: <?= $this->get('rating')['rating'] ?>
    </div>
  <?php endif; ?>
  <form class="" action="/game/duels" method="post">
    <button type="submit" name="startDuel">Start Duel</button>
  </form>
<?php elseif ($this->get('playerStatus') == 'in_queue') : ?>
  In Queue...
  <form class="" action="/game/duels" method="post">
    <button type="submit" name="quitQueue">Quit Queue</button>
  </form>
<?php elseif ($this->get('playerStatus') == 'in_game') : ?>
  In Game...
  <?php if ($this->get('player') && $this->get('opponent')) : ?>
    Player: <?= json_encode($this->get('player')) ?>
    Opponent: <?= json_encode($this->get('opponent')) ?>

    My Health:
    <div class="health-bar">
      <div class="hb-current-health" style="width: <?= $this->get('player')['healthPerc'] ?>%;"></div>
    </div>
    Enemy Health:
    <div class="health-bar">
      <div class="hb-current-health" style="width: <?= $this->get('opponent')['healthPerc'] ?>%;"></div>
    </div>
  <?php endif; ?>

  <form action="/game/duels" method="post">
    <?php if ($this->get('delayedAttackBtn')) : ?>
      <div class="countdown delayedHide"></div>
      <button type="submit" name="hitOpponent" class="delayedShow">Attack</button>
    <?php else : ?>
      <button type="submit" name="hitOpponent">Attack</button>
    <?php endif; ?>
  </form>

<?php endif; ?>
