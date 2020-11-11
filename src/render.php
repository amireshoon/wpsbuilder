<?php

/**
 * Renderer class for wpsBuilder
 * 
 * @package wpsRender
 * @author  Amirhossein Meydani
 * @version 1.0.0
 */

if (!class_exists('wpsRender')) {
    class wpsRender {
        
        protected $builder = null;
        protected $inputs = array();
        protected $formSlug = '';

        public function __construct($builder) {
            $this->builder = $builder;
            if ($this->builder->isSubMenu()) {
                add_action( 'admin_menu', array($this, 'createSubMenu') );
                $this->formSlug = $this->builder->getSubMenuSlug();
            }else {
                add_action( 'admin_menu', array($this, 'createMenu') );
                $this->formSlug = $this->builder->getMenuSlug();
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
            $this->render_form(null);
        }
        
        private function render_form($inputs) {
            echo '<div class="wrap">'; // Content wrapper

            echo '<h1>'.$this->builder->getPageTitle().'</h1>'; // Page title

            if (!empty($this->builder->getPageDescription()))
                echo '<p>'.$this->builder->getPageDescription().'</p>'; // Page desc if available
            
            $this->status(); // Page errors or success

            echo '<form method="'.$this->builder->getFormMethod().'" action="admin.php?page='.$this->formSlug.'&option=save">'; // Begin form 
            echo '<table class="form-table" role="presentation"><tbody>';
            $this->proccessFields();
            echo '</tbody></table>';
            submit_button();    // Submit button
            echo'</form></div>'; // End of form
        }

        private function status() {
            if ($_POST['status'] == true) {
                echo '<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
                <p><strong>تنظیمات ذخیره شد.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">رد کردن این اخطار</span></button></div>';
            }else {
                echo '<div id="setting-error-invalid_siteurl" class="notice notice-error settings-error is-dismissible"> 
                <p><strong>گویا نشانی وردپرسی که وارد کردید معتبر نیست، لطفاً یک نشانی معتبر وارد کنید.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">رد کردن این اخطار</span></button></div>';
            }
        }

        private function proccessFields() {
            $fieldsGroup = $this->builder->getFields();
            foreach ($fieldsGroup as $key => $field) {
                for ($i=0; $i < sizeof($field); $i++) { 
                    $this->detectFieldType($key, $field[$i]);
                }
            }
        }

        private function detectFieldType($key, $field) {
            echo '<tr>';
            if ($key == 'input') {
                $this->createInput($field);
            }
            echo '</tr>';
        }

        private function createInput($att) {
            echo '<th scope="row"><label for="'.$att['field_id'].'">'.$att['field_placeholder'].'</label></th>';
            foreach ($att['field_settings'] as $setting) {
                $settings .= ' '.$setting['key'].'="'.$setting['value'].'" ';
            }
            echo '<td>
            <input name="'.$att['field_id'].'" '.$settings.' placeholder="'.$att['field_placeholder'].'" id="'.$att['field_id'].'" value="'.$att['field_content'].'" class="regular-text">
            </td>';
        }
    }
}
