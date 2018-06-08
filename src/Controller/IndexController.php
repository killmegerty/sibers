<?php

namespace App\Controller;
use App\Controller\AppController;
use App\Model\SearchEngine;

class IndexController extends AppController
{

  public function index()
  {
    $searchEngineModel = new SearchEngine();
    $searchEngines = $searchEngineModel->getAll();
    $this->view->set('searchEngines', $searchEngines);
  }

  public function ajaxSearch()
  {
    if (!$this->isAjax()) {
      $this->_redirect('/');
    }

    $this->view->setLayout('empty');

    if (!isset($_POST['engineId']) || empty($_POST['engineId']) ||
    !isset($_POST['query']) || empty($_POST['query'])) {
      $this->view->set('error', "'query' and 'engineId' should be provided");
      return;
    }

    $items = [];
    $engineId = (int)$_POST['engineId'];
    $query = urlencode($_POST['query']);

    $searchEngineModel = new SearchEngine();
    $items = $searchEngineModel->searchQuery($engineId, $query);
    $this->view->set('items', $items);
  }

}
