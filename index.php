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
include_once './app/controllers/LoginPageController.php';
include_once './app/controllers/SignupPageController.php';
include_once './app/controllers/LogoutController.php';

include_once './app/controllers/ProfileTabController.php';
include_once './app/controllers/MyPostsTabController.php';
include_once './app/controllers/CreatePostTabController.php';
include_once './app/controllers/FavouritesTabController.php';

$readAll = new PostReadAllController;
$create = new PostCreateController;
$readSingle = new PostReadSingleController;
$update = new PostUpdateController;
$delete = new PostDeleteController;

$appHome = new HomePageController;
$appLogin = new LoginPageController;
$appSignup = new SignupPageController;
$appLogout = new LogoutController;

$profileTab = new ProfileTabController;
$myPostsTab = new MyPostsTabController;
$createTab = new CreatePostTabController;
$favouritesTab = new FavouritesTabController;


RouterControllersRegistry::add($readAll);
RouterControllersRegistry::add($create);
RouterControllersRegistry::add($readSingle);
RouterControllersRegistry::add($update);
RouterControllersRegistry::add($delete);


RouterControllersRegistry::add($appHome);
RouterControllersRegistry::add($appLogin);
RouterControllersRegistry::add($appSignup);
RouterControllersRegistry::add($appLogout);

RouterControllersRegistry::add($profileTab);
RouterControllersRegistry::add($myPostsTab);
RouterControllersRegistry::add($createTab);
RouterControllersRegistry::add($favouritesTab);

if (RouterControllersRegistry::canHandle()) {
  RouterControllersRegistry::handle();
} else {
  http_response_code(404);
  exit;
}
