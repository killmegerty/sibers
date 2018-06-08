<div class="row mt-5">
  <div class="col-md-6 mx-auto">
    <div class="input-group">
      <input id="search-query" type="text" class="form-control" aria-label="Text input with segmented dropdown button">
      <div class="input-group-append">

        <button id="search-btn" type="button" class="btn btn-outline-secondary">Search</button>
        <button id="search-engine-dropdown"
                type="button"
                class="btn btn-outline-secondary dropdown-toggle"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                data-engine-id="<?= $this->get('searchEngines')[0]['id'] ?>"><?= $this->get('searchEngines')[0]['name'] ?></button>
        <div id="search-engine-dropdown-menu" class="dropdown-menu">
          <?php foreach ($this->get('searchEngines') as $searchEngine) : ?>
            <a class="dropdown-item" href="#" data-engine-id="<?= $searchEngine['id'] ?>"><?= $searchEngine['name'] ?></a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-5">
  <div class="col-md-12 mx-auto">
    <div class="loader hidden"></div>
    <div id="search-results">
    </div>
  </div>
</div>


<?= $this->script('/js/index-index.js') ?>
