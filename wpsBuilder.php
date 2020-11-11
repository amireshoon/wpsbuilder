<?php

if (!class_exists('wpsBuilder')) {
    class wpsBuilder {
        
        public function __construct() {
            
        }

        public function setPageTitle($pageTitle) {

        }

        public function attachToMenu($parentSlug) {

        }

        public function addMenu($slug) {

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

        public function build() {
            
        }
    }
}