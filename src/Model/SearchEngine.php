<?php

namespace App\Model;

use App\Service\HttpRequester;
use Sunra\PhpSimple\HtmlDomParser;

class SearchEngine extends Model
{
  public $httpRequester;

  public function __construct()
  {
    parent::__construct('search_engines');
    $this->httpRequester = new HttpRequester();
  }

  public function searchQuery($id, $q)
  {
    $searchEngine = $this->get($id);

    if (!$searchEngine) {
      return false;
    }

    $html = $this->httpRequester->urlRequest($searchEngine['query_url'] . $q);
    return $this->_parseHtmlResults(
      $html,
      $searchEngine['item_block_selector'],
      $searchEngine['item_block_child_title_selector'],
      $searchEngine['item_block_child_href_selector'],
      $searchEngine['item_block_child_description_selector']
    );
  }

  protected function _parseHtmlResults($html, $itemBlockSel, $childTitleSel, $childHrefSel, $childDescSel)
  {
    $items = [];
    $dom = HtmlDomParser::str_get_html($html);
    foreach($dom->find($itemBlockSel) as $domItemBlock) {
      $itemTitle = $domItemBlock->find($childTitleSel, 0);
      if (!$itemTitle) {
        continue;
      }
      $item['title'] = $itemTitle->plaintext;

      $itemLink = $domItemBlock->find($childHrefSel, 0);
      if (!$itemLink) {
        continue;
      }
      $item['link'] = $this->_addHttpToLink($itemLink->plaintext);

      $itemDesc = $domItemBlock->find($childDescSel, 0);
      if (!$itemDesc) {
        continue;
      }
      $item['description'] = $itemDesc->plaintext;

      $items[] = $item;
    }
    return $items;
  }

  protected function _addHttpToLink($link)
  {
    if (substr($link, 0, 4) === 'http' || empty($link)) {
      return $link;
    }
    return 'http://' . $link;
  }

}
