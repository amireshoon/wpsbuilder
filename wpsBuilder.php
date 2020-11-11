<?php

if (!class_exists('wpsBuilder')) {
    require_once('src/render.php');

    class wpsBuilder {
        
        protected $isSubMenu = false;
        
        public function __construct() {
            
        }

        public function setPageTitle($pageTitle) {

        }

        public function attachToMenu($parentSlug) {
            $this->isSubMenu = true;
            return $this;
        }

        public function addMenu($slug) {
            $this->isSubMenu = false;
            return $this;
        }

        public function setMenuTitle($menuTitle) {

        }

        /**
         * The capability required for this menu to be displayed to the user.
         * 
         * @refrence https://wordpress.org/support/article/roles-and-capabilities/
         */
        public function capability($capability) {

        }

        /**
         * The icon uses to render menu page. Only available when using @addMenu.
         * 
         * @refrence https://developer.wordpress.org/resource/dashicons
         */
        public function iconUrl($iconUrl) {

        }

        public function position($position) {

        }

        public function isSubMenu() : bool {
            return $this->isSubMenu;
        }

        public function build() {
            $render = new wpsRender($this);
        }
    }
}