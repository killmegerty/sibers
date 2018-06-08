<?php

namespace App\Service;

class HttpRequester
{
  public function urlRequest($url)
  {
    $opts = [
      'http' => [
        'method' => "GET",
        'header' => "User-Agent: Mozilla 5.0\r\n"
      ]
    ];
    $context = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);
    return $result;
  }
}
