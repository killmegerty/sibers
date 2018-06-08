<?php if (!empty($this->get('error'))) : ?>
  <div class="alert alert-danger">
    <?= $this->get('error') ?>
  </div>
<?php else : ?>

  <?php if (empty($this->get('items'))) : ?>
    <div class="row">
      <div class="col-md-6 mx-auto">
        <h2>No Results</h2>
      </div>
    </div>
  <?php else : ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Link</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->get('items') as $item) : ?>
          <tr>
            <td><a target="_blank" href="<?= $item['link'] ?>"><?= $item['title'] ?></a></td>
            <td><?= $item['description'] ?></td>
            <td><?= $item['link'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

<?php endif; ?>
