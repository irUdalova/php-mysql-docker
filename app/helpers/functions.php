<?php

function formatDate($date) {
  $time = strtotime($date);
  $dateFormated = date("d M Y", $time);
  return $dateFormated;
}
