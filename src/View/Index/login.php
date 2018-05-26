<form class="" action="/" method="post">
  <label for="email">Email</label>
  <input type="text" name="email" value="<?= $this->get('email') ?>" required>
  <label for="password">Password</label>
  <input type="password" name="password" value="" required>
  <div class="error">
    <?= $this->get('error') ?>
  </div>

  <button type="submit" class="button">Login</button>
</form>
