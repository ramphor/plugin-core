<?php
namespace Ramphor\Core\UI;

class UIManager {
    protected static $instance;

    public $menu;

    public static function getInstance() {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct() {
        $this->init();
    }

    public function init() {
        $this->menu = new Menu();
    }

    public function initMenu() {
        add_action('admin_head-nav-menus.php', array($this->menu, 'registeMetabox'));
        add_filter('wp_setup_nav_menu_item', array($this->menu, 'setup_nav_menu_item'));
    }
}
