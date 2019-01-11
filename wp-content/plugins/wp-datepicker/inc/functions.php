<?php  if ( ! defined( 'ABSPATH' ) ) exit; 

	function sanitize_wpdp_data( $input ) {

		if(is_array($input)){
		
			$new_input = array();
	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?sanitize_wpdp_data($val):sanitize_text_field( $val ));
			}
			
		}else{
			$new_input = sanitize_text_field($input);
		}
		
		return $new_input;
	}
	//FOR QUICK DEBUGGING
	if(!function_exists('pre')){
	function pre($data){
			if(isset($_GET['debug'])){
				pree($data);
			}
		}	 
	} 
	
	if(!function_exists('pree')){
	function pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 




	function wpdp_menu()
	{



		 add_options_page('WP Datepicker', 'WP Datepicker', 'activate_plugins', 'wp_dp', 'wp_dp');



	}

	function wp_dp(){ 



		if ( !current_user_can( 'administrator' ) )  {



			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-datepicker' ) );



		}



		global $wpdb, $wpdp_dir, $wpdp_pro, $wpdp_data; 

		
		include($wpdp_dir.'inc/wpdp_settings.php');
		

	}	



	
	

	function wpdp_plugin_links($links) { 
		global $wpdp_premium_link, $wpdp_pro;
		
		$settings_link = '<a href="options-general.php?page=wp_dp">'.__('Settings', 'wp-datepicker').'</a>';
		
		if($wpdp_pro){
			array_unshift($links, $settings_link); 
		}else{
			 
			$wpdp_premium_link = '<a href="'.$wpdp_premium_link.'" title="'.__('Go Premium', 'wp-datepicker').'" target=_blank>'.__('Go Premium', 'wp-datepicker').'</a>'; 
			array_unshift($links, $settings_link, $wpdp_premium_link); 
		
		}
		
		
		return $links; 
	}
	
	function register_wpdp_scripts() {
		
			
		if (is_admin ()){
		

			
			if(isset($_GET['page']) && $_GET['page']=='wp_dp'){
				
					
				wp_enqueue_media ();
			
				
				 
				wp_enqueue_script(
					'wpdp-scripts1',
					plugins_url('js/scripts.js', dirname(__FILE__)),
					array('jquery')
				);	
				
				
			
				wp_enqueue_style( 'wpdp-style1', plugins_url('css/admin-styles.css', dirname(__FILE__)), array(), date('Ymdhi'));
			
				wp_enqueue_script(
					'wpdp-scripts3',
					plugins_url('js/jqColorPicker.min.js', dirname(__FILE__)),
					array('jquery')
				);					
				
			}
		
		}
		
		if(!is_admin() || (is_admin() && get_option( 'wp_datepicker_wpadmin', 0))){
			
				wp_enqueue_script(
					'wpdp-scripts2',
					plugins_url('js/scripts-front.js', dirname(__FILE__)),
					array('jquery', 'jquery-ui-datepicker')
				);	
						
			
				wp_register_style('wpdp-style2', plugins_url('css/front-styles.css', dirname(__FILE__)));	
				
				wp_enqueue_style( 'wpdp-style2' );	
	
				
				
				
				
				$wp_datepicker_language = wpdp_slashes(get_option( 'wp_datepicker_language'));
				if($wp_datepicker_language!=''){
					$lang = explode('|', $wp_datepicker_language);
					$filename = (!empty($lang)?end($lang):$lang);
					
					if(substr($filename, 0, strlen('Select'))!='Select'){
						wp_enqueue_script(
							'wpdp-i18n',
							plugins_url('js/i18n/'.$filename, dirname(__FILE__)),
							array('jquery')
						);	
					}
				}
				
				
			
				
				wp_register_style('wpdp-style3', plugins_url('css/jquery-ui.css', dirname(__FILE__)));	
				
				wp_enqueue_style( 'wpdp-style3' );	
				
				
				if(wp_is_mobile()){
					
					wp_enqueue_style( 'jquery.ui.datepicker.mobile', plugins_url('css/mobile/jquery.ui.datepicker.mobile.css', dirname(__FILE__)), array(), date('Yhi'));
					/*wp_enqueue_script(
						'wpdp-datepicker-ui',
						plugins_url('js/mobile/jQuery.ui.datepicker.js', dirname(__FILE__)),
						array('jquery')
					);*/	
					wp_enqueue_script(
						'wpdp-datepicker-mobile',
						plugins_url('js/mobile/jquery.ui.datepicker.mobile.js', dirname(__FILE__)),
						array('jquery')
					);	
												
				}
			
			
		}
							
	} 
		
	if(!function_exists('wp_datepicker')){
	function wp_datepicker(){

		
		}
	}
	
	
	if(!function_exists('wpdp_footer_scripts')){
	function wpdp_footer_scripts(){
		$wpdp_selectors = get_option( 'wp_datepicker');
		
		if($wpdp_selectors!=''){ 	
			$wpdp_selectors = wpdp_slashes($wpdp_selectors);
?>	
	
	<script type="text/javascript" language="javascript">
	

	jQuery(document).ready(function($){
		
		if($('.wpcf7-form-control.wpcf7-repeater-add').length>0){
			$('.wpcf7-form-control.wpcf7-repeater-add').on('click', function(){
				wpdp_refresh(jQuery, true);
			});
		}
		
<?php
			global $wpdp_options, $wpdp_js_options;
			//pree($wpdp_options);
			$options = array();
			if(!empty($wpdp_options)){
				$wpdp_options_db = get_option('wpdp_options');
				foreach($wpdp_options as $option=>$type){
					if(!isset($wpdp_options_db[$option])){
						$wpdp_options_db[$option] = '';
					}
					//pree($type);
					switch($type){
						default: 
							$val = $wpdp_options_db[$option];
							//pree($option);
							//pree($val);
							if($val==''){
								switch($option){
									case 'dateFormat':
										//$val = get_option('date_format'); pree($val);
										$val = 'mm/dd/yy'; //pree($val);
									break;
								}
							}
								
							$val = '"'.$val.'"';
							
						break;
						case 'checkbox':
							$val = ($wpdp_options_db[$option]==true?'true':'false');//exit;
						break;
					}
					$wpdp_js_options[$option] = $val;
					$options[] = $option.':'.$val.'';
					//$options[] = array('key'=>$option, 'val'=>$val);
				}
			}
			
			
			

			//pree($options);
?>
	
});
var wpdp_refresh_first = 'yes';
var wpdp_counter = 0;
var wpdp_month_array = [];
<?php 
	if(!empty($wpdp_js_options)){
		foreach($wpdp_js_options as $opts=>$vals){
?>
var wpdp_<?php echo $opts; ?> = <?php echo $vals; ?>;
<?php			
		}
	}
?>
function wpdp_refresh($, force){
<?php 
			if(!is_admin() || (isset($_GET['page']) && $_GET['page']!='wp_dp') || (is_admin() && get_option( 'wp_datepicker_wpadmin', 0))): 
		
			$wp_datepicker_language = wpdp_slashes(get_option( 'wp_datepicker_language'));
			$wp_datepicker_language = str_replace('Select Language', 'en-GB|datepicker-en-GB.js', $wp_datepicker_language);	
			$wp_datepicker_weekends = get_option( 'wp_datepicker_weekends');
			$wp_datepicker_beforeShowDay = trim(get_option( 'wp_datepicker_beforeShowDay'));
			$wp_datepicker_months = get_option( 'wp_datepicker_months');
			
			if($wp_datepicker_months){

				if (($key = array_search('changeMonth:false', $options)) !== false) {
					unset($options[$key]);
					$options[]='changeMonth:true';	
				}			
					
				$options[] = 'monthNamesShort:wpdp_month_array';
			}
			
			if($wp_datepicker_language != ''){
			
			$code = current(explode('|', $wp_datepicker_language));
			
?>
				if(typeof $.datepicker!='undefined' && typeof $.datepicker.regional["<?php echo $code; ?>"]!='undefined'){
				<?php if($wp_datepicker_months): ?>
				wpdp_month_array = $.datepicker.regional["<?php echo $code; ?>"].monthNamesShort;
				<?php else: ?>	
				wpdp_month_array = $.datepicker.regional["<?php echo $code; ?>"].monthNames;
				<?php endif; ?>					
				}
		
		
				
<?php
				$wpdp_selectors = explode(',', $wpdp_selectors);
				if(!empty($wpdp_selectors)){
					foreach($wpdp_selectors as $wpdp_selector){
						$wpdp_selector = trim($wpdp_selector);
?>	
				
				if($("<?php echo $wpdp_selector; ?>").length>0){
					
				$("<?php echo $wpdp_selector; ?>").attr("autocomplete", "off");
					
				//document.title = wpdp_refresh_first=='yes';
				force = true;
				if(wpdp_refresh_first=='yes' || force){
					
					if(typeof $.datepicker!='undefined')
					$("<?php echo $wpdp_selector; ?>").datepicker( "destroy" );
					
					$("<?php echo $wpdp_selector; ?>").removeClass("hasDatepicker");
					wpdp_refresh_first = 'done';
					
				}
				$("<?php echo $wpdp_selector; ?>").on('mouseover, mousemove', function(){
									
				if ($(this).val()!= "") {
					$(this).attr('data-default-val', $(this).val());
				}		
							
				if(wpdp_counter>2)
				clearInterval(wpdp_intv);		
				
				if(!$("<?php echo $wpdp_selector; ?>").hasClass('hasDatepicker')){

				
					
				$("<?php echo $wpdp_selector; ?>").datepicker($.extend(  
					{},  // empty object  
					$.datepicker.regional[ "<?php echo $code; ?>" ],       // Dynamically  
					{ <?php if($wp_datepicker_beforeShowDay!=''){?> beforeShowDay: <?php echo htmlspecialchars_decode($wp_datepicker_beforeShowDay); ?>, <?php }elseif($wp_datepicker_weekends){ ?>beforeShowDay: $.datepicker.noWeekends,
 <?php } ?>
 
 					dateFormat: wpdp_dateFormat
  } // your custom options 
				)); 
				
				$("<?php echo $wpdp_selector; ?>").attr('readonly', 'readonly');
				
				
				
<?php 
					if(!empty($options)){
						
						foreach($options as $option){	
						$opt = explode(':', $option);
						
						$key = current($opt);
						
						array_shift($opt);
						
						
						$val = implode(':', $opt);
						//pree($val);
						if(trim(str_replace('"', '', $val))!=''){
?>
				$("<?php echo $wpdp_selector; ?>").datepicker( "option", "<?php echo $key; ?>", <?php echo $val; ?> );
<?php
						}
						}
					}
?>
									
					$.each($("<?php echo $wpdp_selector; ?>"), function(){
						if($(this).data('default-val')!= ""){
							$(this).val($(this).data('default-val'));
						}
						
					});
						
				
				}
				});
				}
<?php
					
					}
				}
?>
		
<?php
			}else{
?>
				$("<?php echo $wpdp_selectors; ?>").datepicker({dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'], <?php echo implode(', ', $options); ?>});
<?php				
			}
?>
		$('.ui-datepicker').addClass('notranslate');
<?php 
			endif; 
?>
}
	var wpdp_intv = setInterval(function(){
		wpdp_counter++;
		wpdp_refresh(jQuery, false);
	}, 500);
	
	</script>    
<?php		
		}
	}
	}
	
	function wpdp_slashes($str, $s=false){
		return str_replace(array('"'), "'", stripslashes($str));
	}
	
	function wpdp_free_settings(){
		global $wpdp_pro, $wpdp_fonts, $wpdp_options, $wpdp_styles;
		//pree($_POST);
				
		$wpdp_fonts = unserialize(base64_decode($wpdp_fonts));
		if(!empty($_POST) && !$wpdp_pro){
			//pree($_POST);exit;
			if(isset($_POST['wpdp_options']))
			update_option('wpdp_options', sanitize_wpdp_data($_POST['wpdp_options']));
			
		}
		

?>
	<div class="wpdp_free_settings">
    
    
    <?php
		if(!empty($wpdp_options)){

			
			//pree($_POST);
			//pree($wpdp_options_db);
			foreach($wpdp_options as $item=>$type){
				?>
                
                <div style="clear:both; margin-top:20px;">
                <label for="<?php echo $item; ?>"><?php echo ucwords($item); ?>:</label>
                <?php 
					switch($type){
						case 'text':
						?>
                
                <input id="<?php echo $item; ?>" type="text" value="<?php echo (''!=wpdp_get($item))?wpdp_get($item):''; ?>" name="wpdp_options[<?php echo $item; ?>]" /> <a href="http://api.jqueryui.com/datepicker/#option-<?php echo $item; ?>" target="_blank" title="<?php _e('Click here for documentation about', 'wp-datepicker'); ?> <?php echo $item; ?>" style="text-decoration:none">?</a>
                <?php
						break;
						case 'checkbox':
						?>
                
                <input id="<?php echo $item; ?>" type="checkbox" value="1" <?php echo (''!=wpdp_get($item) && wpdp_get($item)==1)?'checked':''; ?> name="wpdp_options[<?php echo $item; ?>]" /> <a href="http://api.jqueryui.com/datepicker/#option-<?php echo $item; ?>" target="_blank" title="<?php _e('Click here for documentation about', 'wp-datepicker'); ?> <?php echo $item; ?>" style="text-decoration:none">?</a>
                <?php
						break;						
					}
						?>
                </div>
                <?php
			}
		}
    ?>
    </div>
<?php		
	}	
	if(!function_exists('wpdp_get')){
		function wpdp_get($index){
			global $wpdp_options;
			
			$wpdp_options_db = get_option('wpdp_options');
			$val = '';
			if(isset($wpdp_options_db[$index])){
				$val = $wpdp_options_db[$index];
			}
			return $val;
		}
	}	