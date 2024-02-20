<?php

declare(strict_types=1);

//define root dir path variable
define("ROOT_DIR", __DIR__);

include_once './router/RouterControllersRegistry.php';

include_once './api/controllers/PostCreateController.php';
include_once './api/controllers/PostReadAllController.php';
include_once './api/controllers/PostReadSingleController.php';
include_once './api/controllers/PostUpdateController.php';
include_once './api/controllers/PostDeleteController.php';

include_once './app/controllers/HomePageController.php';
include_once './app/controllers/MyNotesPageController.php';

$readAll = new PostReadAllController;
$create = new PostCreateController;
$readSingle = new PostReadSingleController;
$update = new PostUpdateController;
$delete = new PostDeleteController;

$appHome = new HomePageController;
$appMyNotes = new MyNotesPageController;

RouterControllersRegistry::add($readAll);
RouterControllersRegistry::add($create);
RouterControllersRegistry::add($readSingle);
RouterControllersRegistry::add($update);
RouterControllersRegistry::add($delete);


RouterControllersRegistry::add($appHome);
RouterControllersRegistry::add($appMyNotes);

if (RouterControllersRegistry::canHandle()) {
  RouterControllersRegistry::handle();
} else {
  http_response_code(404);
  exit;
}
