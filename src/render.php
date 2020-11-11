<?php

if (!class_exists('wpsRender')) {
    class wpsRender {
        protected $builder = null;
        public function __construct($builder) {
            $this->builder = $builder;
            if ($this->builder->isSubMenu()) {
                $this->createSubMenu();
            }else {
                $this->createMenu();
            }
        }

        private function createMenu() {
            print_r('menu');exit;
        }

        private function createSubMenu() {
            print_r('submenu');exit;
        }
    }
}
