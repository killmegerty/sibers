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

  /**
   * Get associative array with search results data
   *
   * @param string $searchEngineId
   * @param string $q Search query
   * @return array [['title' => ..., 'link' => ..., 'description' => ...], ...]
   */
  public function searchQuery($searchEngineId, $q)
  {
    $searchEngine = $this->get($searchEngineId);

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

  /**
   * Parse search result from html document string
   *
   * @param string $html Full html page with search results
   * @param string $itemBlockSel Selector of item block for HtmlDomParser
   * @param string $childTitleSel Selector of item block's children with title for HtmlDomParser
   * @param string $childHrefSel Selector of item block's children with href for HtmlDomParser
   * @param string $childDescSel Selector of item block's children with description for HtmlDomParser
   * @return array [['title' => ..., 'link' => ..., 'description' => ...], ...]
   */
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
