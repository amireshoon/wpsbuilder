<?php

if (!class_exists('wpsRender')) {
    class wpsRender {
        protected $builder = null;
        public function __construct($builder) {
            $this->builder = $builder;
            if ($this->builder->isSubMenu()) {
                add_action( 'admin_menu', array($this, 'createSubMenu') );
            }else {
                add_action( 'admin_menu', array($this, 'createMenu') );
            }
        }

        public function createMenu() {
            add_menu_page( 
                $this->builder->getMenuTitle(),
                $this->builder->getPageTitle(),
                $this->builder->getCapability(),
                $this->builder->getMenuSlug(),
                array($this, 'menu_content'),
                $this->builder->getIconUrl(),
                $this->builder->getPosition()
            ); 
        }

        public function createSubMenu() {
            add_submenu_page(
                $this->builder->getParentSlug(),
                $this->builder->getPageTitle(),
                $this->builder->getMenuTitle(),
                $this->builder->getCapability(),
                $this->builder->getSubMenuSlug(),
                array($this, 'menu_content')
            );
        }

        public function menu_content(){
            esc_html_e( 'Admin Page Test', 'textdomain' );  
        }

    }
}
