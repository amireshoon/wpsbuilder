<?php

/**
 * Wordpress settings page Builder
 * 
 * @author  Amirhossein Meydani
 * @version 1.0.0
 * @license GPL2
 * @package wpsBuilder
 */

if (!class_exists('wpsBuilder')) {
    require_once('src/render.php');

    class wpsBuilder {
        
        protected $isSubMenu = false;
        protected $config = array();
        protected $fields = array();

        public function __construct() {
            return $this;
        }

        /**
         * Page title used for create menu/submenu.
         * 
         * @var     string  page title
         */
        public function setPageTitle($pageTitle) {
            $this->config['page_title'] = $pageTitle;
            return $this;
        }

        /**
         * Attach setting menu to another menu as submenu.
         * 
         * @var     string  parent menu slug
         */
        public function attachToMenu($parentSlug) {
            $this->isSubMenu = true;
            $this->config['parent_slug'] = $parentSlug;
            return $this;
        }

        /**
         * Creates menu for setting page.
         * 
         * @var     string  menu slug
         */
        public function addMenu($slug) {
            $this->isSubMenu = false;
            $this->config['menu_slug'] = $slug;
            return $this;
        }

        /**
         * Set menu slug for submenu.
         * 
         * @var     string  menu slug
         */
        public function setMenuSlug($slug) {
            $this->config['submenu_menu_slug'] = $slug;
            return $this;
        }

        /**
         * Set menu title for menu/submenu.
         * 
         * @var     string  menu title
         */
        public function setMenuTitle($menuTitle) {
            $this->config['menu_title'] = $menuTitle;
            return $this;
        }

        /**
         * Set page description for menu/submenu.
         * This text will place after title and in <p> tag.
         * 
         * @var     string  description
         */
        public function setPageDescription($desc) {
            $this->config['page_desc'] = $desc;
            return $this;
        }

        /**
         * Configure form method for setting.
         * Default is post.
         * 
         * @var     string  method
         */
        public function setFormMethod($method) {
            $this->config['form_method'] = $method;
            return $this;
        }

        /**
         * The capability required for this menu to be displayed to the user.
         * 
         * @refrence https://wordpress.org/support/article/roles-and-capabilities/
         */
        public function setCapability($capability) {
            $this->config['capability'] = $capability;
            return $this;
        }

        /**
         * The icon uses to render menu page. Only available when using @addMenu.
         * 
         * @refrence https://developer.wordpress.org/resource/dashicons
         */
        public function setIconUrl($iconUrl) {
            $this->config['icon_url'] = $iconUrl;
            return $this;
        }

        /**
         * Set position for menu.
         * 
         * @var     int position
         */
        public function setPosition($position) {
            $this->config['position'] = $position;
            return $this;
        }

        public function isSubMenu() : bool {
            return $this->isSubMenu;
        }

        public function getMenuSlug() {
            if(!empty($this->config['menu_slug']))
                return $this->config['menu_slug'];
        }

        public function getPosition() {
            if(!empty($this->config['position']))
                return $this->config['position'];
        }

        public function getIconUrl() {
            if(!empty($this->config['icon_url']))
                return $this->config['icon_url'];
        }

        public function getCapability() {
            if(!empty($this->config['capability']))
                return $this->config['capability'];
        }

        public function getPageTitle() {
            if(!empty($this->config['page_title']))
                return $this->config['page_title'];
        }

        public function getMenuTitle() {
            if(!empty($this->config['menu_title']))
                return $this->config['menu_title'];
        }

        public function getParentSlug() {
            if(!empty($this->config['parent_slug']))
                return $this->config['parent_slug'];
        }

        public function getSubMenuSlug() {
            if(!empty($this->config['submenu_menu_slug']))
                return $this->config['submenu_menu_slug'];
        }

        public function getPageDescription() {
            if(!empty($this->config['page_desc']))
                return $this->config['page_desc'];
        }

        public function getFormMethod() {
            if(!empty($this->config['form_method'])) {
                return $this->config['form_method'];
            }else {
                return 'post';
            }
        }

        public function input($id, $placeholder, $settings = array(), $content = '') {
            $this->fields['input'][] = [
                'field_id'            =>  $id,
                'field_placeholder'   => $placeholder,
                'field_content'       => $content,
                'field_settings'      => $settings
            ];
            return $this;
        }

        public function hiddenInput($id, $value) {
            $this->fields['hidden'][] = [
                'field_id'            =>  $id,
                'field_value'   => $value
            ];
            return $this;
        }

        public function textArea($id, $placeholder, $settings = array(), $content = '') {
            $this->fields['textArea'][] = [
                'field_id'            =>  $id,
                'field_placeholder'   => $placeholder,
                'field_content'       => $content,
                'field_settings'      => $settings
            ];
            return $this;
        }

        public function select($id, $options,$title, $selected = '') {
            $this->fields['select'][] = [
                'field_id'            =>  $id,
                'field_options'   => $options,
                'title' => $title,
                'selected_field'       => $selected
            ];
            return $this;
        }

        public function radio($id, $radios,$title, $selected = '') {
            $this->fields['radio'][] = [
                'field_id'            =>  $id,
                'field_title'       => $title,
                'field_options'   => $radios,
                'selected_field'       => $selected
            ];
            return $this;
        }

        public function checkbox($id, $title, $desc, $checked = false) {
            $this->fields['checkbox'][] = [
                'field_id'      =>  $id,
                'field_title'   => $title,
                'field_desc'    => $desc,
                'checked'       => $checked
            ];
            return $this;
        }

        public function media($id, $title) {
            $this->fields['media'][] = [
                'field_id'      =>  $id,
                'title'         =>  $title
            ];
            return $this;
        }

        public function text($id, $title, $content) {
            $this->fields['text'][] = [
                'field_id'      =>  $id,
                'title'         =>  $title,
                'content'       =>  $content
            ];
            return $this;
        }

        public function getFields() {
            return $this->fields;
        }
        /**
         * This function will render values.
         * 
         * @subpackage  wpsRender
         */
        public function build() {
            $render = new wpsRender($this);
        }
    }
}