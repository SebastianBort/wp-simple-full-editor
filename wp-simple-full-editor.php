<?php    
/*
Plugin Name: Pełen pasek narzędzi dla starego edytora
Description: Dodaje pełen edytor treści TinyMCE, wraz z opcją dodawania tabeli.
Version: 1.0.0
Author: Sebastian Bort
*/

class Full_TinyMCE {   
       
    const first_toolbar = [ 'formatselect', 'bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'wp_code', 'bullist', 'numlist', 'alignleft', 'aligncenter', 'alignjustify', 'alignright', 'outdent', 'indent', 'spellchecker', 'dfw' ]; 
    const second_toolbar = [ 'fontselect', 'fontsizeselect', 'forecolor', 'backcolor', 'removeformat', 'charmap', 'table', 'hr', 'link', 'unlink', 'fullscreen', 'superscript', 'table' ];               
    const font_sizes = '8px 10px 12px 14px 16px 20px 24px 28px 32px 36px 48px 60px 72px';           
    
    public function __construct() {
        
        add_filter('mce_buttons', [$this, 'set_first_toolbar'], 11, 2);
        add_filter('mce_buttons_2', [$this, 'set_second_toolbar'], 11, 2);                     
        add_filter('tiny_mce_before_init', [$this, 'configure_mce_options'], 10, 2);
        
        add_filter('mce_external_plugins', [$this, 'load_table_plugin']);
        add_filter('content_save_pre', [$this, 'fix_table_content'], 20);
    }         
    
	public static function load_table_plugin($plugin_array) {

		$plugin_array['table'] = plugin_dir_url(__FILE__) . 'table.min.js';
		return $plugin_array;
	}
      
	public function fix_table_content( $content ) {
		if(false !== strpos( $content, '<table')) {
			$content  = preg_replace("/<td([^>]*)>(.+\r?\n\r?\n)/m", "<td$1>\n\n$2", $content);
			if(substr( $content, -8 ) == '</table>') {
				$content .= "\n<br />";
			}
		} 		
		return $content;
	}
      
    public function set_first_toolbar() {
        return self::first_toolbar;
    }         
    public function set_second_toolbar() {
        return self::second_toolbar;
    }          
    public function configure_mce_options($init, $editor_id = '') {        
        $init['image_advtab'] = true;
        $init['menubar'] = true;
        $init['wordpress_adv_hidden'] = false;
        $init['fontsize_formats'] = self::font_sizes;   
        $init['table_toolbar'] = '';          		
        return $init;
    }
}     
new Full_TinyMCE();
?>