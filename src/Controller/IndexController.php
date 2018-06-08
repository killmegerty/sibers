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

    if (!isset($_POST['engineId']) || empty($_POST['engineId']) ||
    !isset($_POST['query']) || empty($_POST['query'])) {
      $this->view->disableRender();
      echo 'Error: Provide data: \'engineId\' and \'query\'';
      return;
    }

    $this->view->setLayout('empty');
    $items = [];
    $engineId = (int)$_POST['engineId'];
    $query = urlencode($_POST['query']);

    $searchEngineModel = new SearchEngine();
    $items = $searchEngineModel->searchQuery($engineId, $query);
    $this->view->set('items', $items);
  }

}
