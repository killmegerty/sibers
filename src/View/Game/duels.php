<?= $this->element('logout_button') ?>

<div class="game-window-container">

  <?php if ($this->get('playerStatus') == 'ready') : ?>
    <?php if ($this->get('rating')) : ?>
      <p>Rating: <?= $this->get('rating')['rating'] ?></p>
    <?php endif; ?>
    <?php if ($this->get('player')) : ?>
      <p>Health: <?= $this->get('player')['health'] ?></p>
      <p>Damage: <?= $this->get('player')['damage'] ?></p>
    <?php endif; ?>
    <div class="action-bar-container">
      <a href="/game" class="inline">
        <button>Home</button>
      </a>
      <form class="inline" action="/game/duels" method="post">
        <button type="submit" name="startDuel">Start Duel</button>
      </form>
    </div>
  <?php elseif ($this->get('playerStatus') == 'in_queue') : ?>
    <p>In Queue...</p>
    <div class="action-bar-container">
      <form class="" action="/game/duels" method="post">
        <button type="submit" name="quitQueue">Quit Queue</button>
      </form>
    </div>
  <?php elseif ($this->get('playerStatus') == 'in_game') : ?>
    <p>In Game...</p>
    <?php if ($this->get('player') && $this->get('opponent')) : ?>
      <p>My Name: <?= $this->get('player')['name'] ?></p>
      <p>My Damage: <?= $this->get('player')['damage'] ?></p>
      <p>My Health:</p>
      <div class="health-bar">
        <div class="hb-current-health" style="width: <?= $this->get('player')['healthPerc'] ?>%;"></div>
      </div>
      <p>Enemy Name: <?= $this->get('opponent')['name'] ?></p>
      <p>Enemy Damage: <?= $this->get('opponent')['damage'] ?></p>
      <p>Enemy Health:</p>
      <div class="health-bar">
        <div class="hb-current-health" style="width: <?= $this->get('opponent')['healthPerc'] ?>%;"></div>
      </div>
    <?php endif; ?>

    <div class="action-bar-container">
      <form action="/game/duels" method="post">
        <?php if ($this->get('delayedAttackBtn')) : ?>
          <div class="countdown delayedHide"></div>
          <button type="submit" name="hitOpponent" class="delayedShow">Attack</button>
        <?php else : ?>
          <button type="submit" name="hitOpponent">Attack</button>
        <?php endif; ?>
      </form>
    </div>

    <div class="game-log-container">
      <?php if ($this->get('gameLog')) : ?>
        <?php foreach ($this->get('gameLog') as $logMsg) : ?>
          <div class="game-log-msg">
            <?= $logMsg ?>
          </div>
        <?php endforeach;  ?>
      <?php endif; ?>
    </div>

  <?php endif; ?>
</div>
