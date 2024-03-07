<?php
class RouterControllersRegistry {
  protected static $controllers = array();

  public static function add($controller) {
    self::$controllers[] = $controller;
  }

  public static function getControllers() {
    var_dump(self::$controllers);
    // return self::$controllers;s
  }

  public static function canHandle() {
    foreach (self::$controllers as $controller) {
      if ($controller->canHandle()) return true;
    }
    return false;
  }

  public static function handle() {
    foreach (self::$controllers as $controller) {
      if ($controller->canHandle()) {
        return $controller->handle();
      }
    }
  }
}
