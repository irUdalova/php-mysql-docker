<?php

function formatDate($date) {
  $time = strtotime($date);
  $dateFormated = date("d M Y", $time);
  return $dateFormated;
}

function createPath($page) {
  $urlQuery = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

  if ($urlQuery) {
    parse_str($urlQuery, $output);
    $output['page'] = $page;
    return http_build_query($output);
  }
  $output['page'] = $page;
  return http_build_query($output);
}
