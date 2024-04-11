<?php

declare(strict_types=1);

//define root dir path variable
define("ROOT_DIR", __DIR__);
define("STATIC_URL", '/');

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

include_once './app/controllers/MyPostsPageController.php';
include_once './app/controllers/PostCreatePageController.php';
include_once './app/controllers/FavouritesPageController.php';
include_once './app/controllers/ProfilePageController.php';

include_once './app/controllers/ProfileEditPageController.php';
include_once './app/controllers/ProfileImgDeleteController.php';
include_once './app/controllers/ProfileImgUpdateController.php';
include_once './app/controllers/ProfileChangePswPageController.php';

include_once './app/controllers/UserProfilePageController.php';

include_once './app/controllers/PostPageController.php';
include_once './app/controllers/PostEditPageController.php';
include_once './app/controllers/PostDeletePageController.php';
include_once './app/controllers/TagPageController.php';

$readAll = new PostReadAllController;
$create = new PostCreateController;
$readSingle = new PostReadSingleController;
$update = new PostUpdateController;
$delete = new PostDeleteController;

$appHome = new HomePageController;
$appLogin = new LoginPageController;
$appSignup = new SignupPageController;
$appLogout = new LogoutController;

$appMyPosts = new MyPostsPageController;
$appPostCreate = new PostCreatePageController;
$appFavourites = new FavouritesPageController;
$appProfile = new ProfilePageController;

$appProfileEdit = new ProfileEditPageController;
$appProfileImgDel = new ProfileImgDeleteController;
$appProfileImgUpd = new ProfileImgUpdateController;
$appProfileChangePsw = new ProfileChangePswPageController;

$appUserProfile = new UserProfilePageController;

$appPost = new PostPageController;
$appPostEdit = new PostEditPageController;
$appPostDelete = new PostDeletePageController;
$appTag = new TagPageController;


RouterControllersRegistry::add($readAll);
RouterControllersRegistry::add($create);
RouterControllersRegistry::add($readSingle);
RouterControllersRegistry::add($update);
RouterControllersRegistry::add($delete);

RouterControllersRegistry::add($appHome);
RouterControllersRegistry::add($appLogin);
RouterControllersRegistry::add($appSignup);
RouterControllersRegistry::add($appLogout);

RouterControllersRegistry::add($appMyPosts);
RouterControllersRegistry::add($appPostCreate);
RouterControllersRegistry::add($appFavourites);
RouterControllersRegistry::add($appProfile);

RouterControllersRegistry::add($appProfileEdit);
RouterControllersRegistry::add($appProfileImgDel);
RouterControllersRegistry::add($appProfileImgUpd);
RouterControllersRegistry::add($appProfileChangePsw);

RouterControllersRegistry::add($appUserProfile);

RouterControllersRegistry::add($appPost);
RouterControllersRegistry::add($appPostEdit);
RouterControllersRegistry::add($appPostDelete);
RouterControllersRegistry::add($appTag);

if (RouterControllersRegistry::canHandle()) {
  RouterControllersRegistry::handle();
} else {
  http_response_code(404);
  exit;
}
