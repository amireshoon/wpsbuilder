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
        protected $status = null;

        public function __construct($builder) {
            $this->builder = $builder;
            add_action( 'admin_enqueue_scripts', array($this, 'load_render_js') );
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
            $this->createHiddenInput($this->builder->getFields()['hidden']);
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
                $this->get_status();
            }
        }

        private function proccessFields() {
            $fieldsGroup = $this->builder->getFields();
            if (!empty($fieldsGroup['hidden']))
                unset($fieldsGroup['hidden']);

            foreach ($fieldsGroup as $key => $field) {
                for ($i=0; $i <= sizeof($field) -1; $i++) {
                    $this->detectFieldType($key, $field[$i]);
                }
            }
        }

        private function detectFieldType($key, $field) {
            echo '<tr>';
            switch ($key) {
                case 'input':
                    $this->createInput($field);
                    break;
                case 'textArea':
                    $this->createTextArea($field);
                    break;
                case 'text':
                    $this->createTextField($field);
                    break;
                case 'checkbox':
                    $this->createCheckBox($field);
                    break;
                case 'radio':
                    $this->createRadio($field);
                    break;
                case 'select':
                    $this->createSelectBox($field);
                    break;
                case 'media':
                    $this->media($field);
                    break;
                default:
                    
                    break;
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

        private function createTextArea($att) {
            echo '<th scope="row"><label for="'.$att['field_id'].'">'.$att['field_placeholder'].'</label></th>';
            echo '<td><textarea name="'.$att['field_id'].'" id="'.$att['field_id'].'" class="large-text code" rows="3">'.$att['content'].'</textarea></td>';
        }

        private function createTextField($att) {
            echo '<th scope="row"><label for="'.$att['field_id'].'">'.$att['title'].'</label></th>';
            echo '<td><p name="'.$att['field_id'].'" id="'.$att['field_id'].'" class="large-text code" rows="3">'.$att['content'].'</p></td>';
        }

        private function createCheckBox($att) {
            if ($att['checked']) {
                $checked = 0;
                $checked2 = 'checked';
            }else {
                $checked = 1;
                $checked2 = '';
            }
            echo '<th scope="row">'.$att['field_title'].'</th>';
            echo '<td>
            <fieldset>
            <legend class="screen-reader-text"><span>'.$att['field_title'].'</span></legend><label for="'.$att['field_id'].'">
            <input name="'.$att['field_id'].'" type="checkbox" id="'.$att['field_id'].'" value="'.$checked.'" '.$checked2.'>
             '.$att['field_desc'].'</label>
            </fieldset>
            </td>';
        }

        private function createRadio($att) {
            echo '<th scope="row">'.$att['title'].'</th>';
            echo '<td><fieldset>';
            foreach ($att['field_options'] as $key => $value) {
                echo '
                <label>
                <input type="radio" name="'.$att['field_id'].'" value="'.$value.'">
                 <span>'.$value.'</span>
                 </label>
                ';
                echo '</br>';
            }
            echo '</fieldset></td>';
        }

        private function createSelectBox($att) {
            echo '<th scope="row">'.$att['title'].'</th>';
            echo '<td><select name="'.$att['field_id'].'">';
            foreach ($att['field_options'] as $key => $value) {
                echo '<option value="'.$value.'">'.$value.'</option>';
            }
            echo '</select></td>';
        }

        private function media($att) {
            echo '
            <th scope="row">'.$att['title'].'</th><td>';
            // Save attachment ID
            if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
                update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) );
            endif;
            wp_enqueue_media();
            ?><form method='post'>
            <div class='image-preview-wrapper'>
                <img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>' height='100'>
            </div>
            <input id="upload_image_button" type="button" class="button" value="<?php echo 'انتخاب عکس' ?>" />
            <input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
            <input name="submit_image_selector" value="Save" class="button-primary" type="hidden">
            </form><?php
            echo '</td>';
        
        }
       
        public function load_render_js() {
            wp_enqueue_media();
            wp_enqueue_script( 'myprefix_script', plugins_url( '/js/wpsBuilder.js' , __FILE__ ), array('jquery'), '0.1' );
        }

        private function createHiddenInput($fields) {
            foreach ($fields as $field) {
                echo '<input name="'.$field['field_id'].'" type="hidden" value="'.$field['field_value'].'"/>';
            }
        }

        private function get_status() {
            if (is_null($this->status))
                return 0;
            if ($this->status) {
                echo '<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
                <p><strong>تنظیمات ذخیره شد.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">رد کردن این اخطار</span></button></div>';
            }else if (!$this->status){
                echo '<div id="setting-error-invalid_siteurl" class="notice notice-error settings-error is-dismissible"> 
                <p><strong>گویا نشانی وردپرسی که وارد کردید معتبر نیست، لطفاً یک نشانی معتبر وارد کنید.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">رد کردن این اخطار</span></button></div>';
            }
        }

        public function response($func) {
            if ($this->builder->getFormMethod() == 'get') {
                $this->status = $func($_GET);
            }else {
                $this->status = $func($_POST);
            }
        }
    }
}
