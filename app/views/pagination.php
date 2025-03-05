<?php

include_once ROOT_DIR . '/app/helpers/functions.php';

function setStart($currentPage, $totalPages) {
  if ($totalPages <= 3) {
    return 1;
  }
  if ($currentPage - 1 === 0) {
    return 1;
  }

  if ($currentPage == $totalPages) {
    return $currentPage - 2;
  }

  return $currentPage - 1;
}

function setEnd($currentPage, $totalPages) {
  if ($totalPages <= 3) {
    return $totalPages;
  }

  if ($currentPage - 1 === 0) {
    return $currentPage + 2;
  }

  if ($currentPage >= $totalPages) {
    return $totalPages;
  }

  return $currentPage + 1;
}

// function createPath($page) {
//   $urlQuery = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

//   if ($urlQuery) {
//     parse_str($urlQuery, $output);
//     $output['page'] = $page;
//     return http_build_query($output);
//   }
//   $output['page'] = $page;
//   return http_build_query($output);
// }
?>


<div class="page-info"> Showing <?= $pagination['page'] ?> of <?= $pagination['totalPages'] ?> </div>
<div class="pagination">

  <?php

  if (isset($_GET['page']) && $pagination['page'] > 1) { ?>
    <a href="?<?= createPath(1) ?>" class="page-link first">First</a>
    <a href="?<?= createPath($pagination['page'] - 1) ?>" class="page-link previous">Previous</a>
  <?php } else { ?>
    <!-- <a class="page-link previous disabled">Previous</a> -->
  <?php } ?>

  <div class="page-numbers">
    <?php
    $pageStart = setStart(intval($pagination['page']), $pagination['totalPages']);
    $pageEnd = setEnd(intval($pagination['page']), $pagination['totalPages']);

    for ($counter = $pageStart; $counter <= $pageEnd; $counter++) {
    ?>
      <a href="?<?= createPath($counter) ?>" class="page-link <?php echo (intval($pagination['page']) === $counter) ? "active" : null; ?>"><?= $counter ?></a>
    <?php }
    ?>
  </div>

  <?php
  if ($pagination['page'] >= 1 && $pagination['page'] < $pagination['totalPages']) {
  ?>
    <a href="?<?= createPath($pagination['page'] + 1) ?>" class="page-link next">Next</a>
    <a href="?<?= createPath($pagination['totalPages']) ?>" class="page-link last">Last</a>
  <?php } elseif ($pagination['page'] >= $pagination['totalPages']) { ?>
    <!-- <a class="page-link previous disabled">Next</a> -->
  <?php } ?>

</div>