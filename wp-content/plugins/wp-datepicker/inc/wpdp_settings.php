<?php  if ( ! defined( 'ABSPATH' ) ) exit; 
	if ( !current_user_can( 'administrator' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-datepicker' ) );
	}
// Save the field values
	if ( isset( $_POST['wpdp_fields_submitted'] ) && $_POST['wpdp_fields_submitted'] == 'submitted' ) {
		
		
						
			
			if ( 
				! isset( $_POST['wpdp_nonce_action_field'] ) 
				|| ! wp_verify_nonce( $_POST['wpdp_nonce_action_field'], 'wpdp_nonce_action' ) 
			) {
			
			   print 'Sorry, your nonce did not verify.';
			   exit;
			
			} else {
			
			   // process form data
						
				
				update_option( 'wp_datepicker_months', 0);
				
				if($wpdp_pro){
					foreach ( $_POST as $key => $value ) {		
						if(is_array($value)){
							$value = array_map( 'esc_attr', $value );
							//pree($value);
							update_option( sanitize_text_field($key), ($value) );
						}else{
							if ( get_option( $key ) != $value ) {
								update_option( sanitize_text_field($key), sanitize_text_field($value) );
							} else {
								add_option( sanitize_text_field($key), sanitize_text_field($value), '', 'no' );
							}
						}
					}
				}else{
				
					
					update_option( 'wp_datepicker', sanitize_text_field($_POST['wp_datepicker']));
					update_option( 'wp_datepicker_weekends', sanitize_text_field($_POST['wp_datepicker_weekends']));
					update_option( 'wp_datepicker_beforeShowDay', sanitize_text_field($_POST['wp_datepicker_beforeShowDay']));
					
					update_option( 'wp_datepicker_months', sanitize_text_field($_POST['wp_datepicker_months']));
					update_option( 'wp_datepicker_wpadmin', sanitize_text_field($_POST['wp_datepicker_wpadmin']));
					
					
					update_option( 'wp_datepicker_language', sanitize_text_field($_POST['wp_datepicker_language']));
				}
				
			}
			
		
		
		
	}
	$wpdp_selectors = get_option( 'wp_datepicker');
	
	$wp_datepicker_language = wpdp_slashes(get_option( 'wp_datepicker_language'));
	
	$wp_datepicker_weekends = get_option( 'wp_datepicker_weekends');
	$wp_datepicker_beforeShowDay = get_option( 'wp_datepicker_beforeShowDay');
	
	$wp_datepicker_months = get_option( 'wp_datepicker_months');
	$wp_datepicker_wpadmin = get_option( 'wp_datepicker_wpadmin');
	
	
	
	$wpdb_string = wpdp_slashes($wpdp_selectors);
	
	$wpdb_arr = explode(',', $wpdb_string);
	
	$wpdb_arr = array_filter($wpdb_arr, 'strlen');
	
	if(empty($wpdb_arr)){
		$wpdb_arr = array('.datepicker');
	}
	
	$attrib = array('type'); //array('accept', 'align', 'right', 'top', 'middle', 'bottom', 'alt ', 'autocomplete ', 'autofocus', 'checked', 'disabled', 'max', 'maxlength', 'min', 'multiple', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'size', 'src', 'step', 'type', 'value', 'width');
	$inputs = array(
		'class' => array('symbol' => '.',),
		'id' => array('symbol' => '#',),
		'input' => array(
			'symbol' => 'input',
			'type' => array(
			'button', 'checkboxcolor', 'date', 'datetime', 'datetime-local', 'email', 'file', 'hidden', 'image', 'month', 'number', 'password', 'radio', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'time', 'url', 'week')
			),
		'textarea' => array('symbol' => 'textarea',),
		'select' => array('symbol' => 'select',),		
	);

	
	
?>	
<div class="wrap wpdp">

<?php if(!$wpdp_pro): ?>
<a title="<?php _e('Click here to download pro version', 'wp-datepicker'); ?>" style="background-color: #25bcf0;    color: #fff !important;    padding: 2px 30px;    cursor: pointer;    text-decoration: none;    font-weight: bold;    right: 0;    position: absolute;    top: 0;    box-shadow: 1px 1px #ddd;" href="http://shop.androidbubbles.com/download/" target="_blank"><?php _e('Already a Pro Member?', 'wp-datepicker'); ?></a>
<?php endif; ?>
	
    
  <div class="head_area">
	<h2><span class="dashicons dashicons-welcome-widgets-menus"></span><?php echo 'WP Datepicker '.'('.$wpdp_data['Version'].($wpdp_pro?') '.__('Pro', 'wp-datepicker').'':')'); ?> - <?php _e('Settings', 'wp-datepicker'); ?></h2>
    
    
    </div>
<form method="post" action="">  
<?php wp_nonce_field( 'wpdp_nonce_action', 'wpdp_nonce_action_field' ); ?>
<input type="hidden" name="wpdp_fields_submitted" value="submitted" />
<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'wp-datepicker' ); ?>" /></p> 
<div class="wpdp_settings">

<?php 
	if(!empty($wpdb_arr)){
?>
		<div class="wpdp_cg">
		<h3><?php _e( 'Code Generator', 'wp-datepicker' ); ?>: <small>(<?php _e( 'Optional', 'wp-datepicker' ); ?>)</small></h3>
<?php        		
		foreach($wpdb_arr as $vals){
			$label = '';
			$type = substr($vals, 0, 1);
			$type_d = '';
			switch($type){
				case "#":
					$label = $type_d = 'id';
				break;	
				case ".":
					$label = $type_d = 'class';
				break;	
				case "i":
				case "s":
				case "t":
					$type_d = explode('[', $vals);
					$label = current($type_d);
				break;	
			}
?>

        <?php if(!empty($inputs)): ?>
        <div class="wpdp_demo_div">
		<select name="wpdp_sel[]" style="width:200px;">
        <?php foreach($inputs as $tag_type => $input): ?>
        	<option style="background-color:#CCC; font-weight:bold;" data-tag="<?php echo $tag_type; ?>" data-type="<?php echo $inputs[$tag_type]['symbol']; ?>" value=""><?php echo $tag_type; ?></option>
            <?php if(!empty($attrib)): ?>
            <?php foreach($attrib as $attr): ?>
            
            <?php if(!empty($input) && isset($input[$attr])): ?>
            <option style="padding-left:20px;" data-type="<?php echo $attr; ?>" value="<?php echo $attr; ?>" <?php selected( $type, $attr ); ?>><?php echo $attr; ?></option>            
            <?php foreach($input as $t => $t_array): ?>
            <?php if(!empty($t_array)): ?>
            <?php foreach($t_array as $tag_elem): ?>
            	<option style="padding-left:40px;" data-tag="<?php echo $tag_type; ?>" data-type="<?php echo $t; ?>" value="<?php echo $tag_elem; ?>" <?php selected( $type, $tag_elem ); ?>><?php echo $tag_elem; ?></option>
            <?php endforeach; ?>	
        	<?php endif; ?>   
            <?php endforeach; ?>	
        	<?php endif; ?>    
            
         	<?php endforeach; ?>	
        	<?php endif; ?>               
        <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <input name="wpdp_demo_str[]" class="" placeholder="" type="text" value="" />
		<input name="wpdp_demo_output[]" type="text" value="<?php echo $vals; ?>" style="width:300px" /><small><?php _e('Insert the output text below and glue with comma for next.', 'wp-datepicker'); ?></small>
        </div>
<?php			
		}
?><br />
<?php _e('Video Tutorial', 'wp-datepicker'); ?>:<br />
<iframe width="200" height="120" src="https://www.youtube.com/embed/eILaObbYucU" frameborder="0" allowfullscreen></iframe>
</div>
<?php 		
	}

?>

<?php
global $wpdp_dir;

?>




<select name="wp_datepicker_language" class="wpdp_selectors">
<option><?php _e('Select Language', 'wp-datepicker'); ?></option>
<?php
foreach (glob($wpdp_dir."js/i18n/*.js") as $filename) {
    $content = file_get_contents($filename);
	$lines = nl2br($content);
	$lines = explode('<br>', $lines);
	$line = explode(' ', $lines[0]);
	$title = $line[1];
	
	$code = str_replace(array('datepicker-', '.js'), '', basename($filename));
	$val = $code.'|'.basename($filename);
?>
	<option value="<?php echo $val; ?>" <?php echo ($wp_datepicker_language==$val?'selected="selected"':''); ?>><?php echo $code.' ('.$title.')'; ?></option>
<?php	
}
?>
</select>		


<div class="wp_datepicker_wpadmin">
<label for=""><?php _e( 'Enable for backend (wp-admin)?', 'wp-datepicker' ); ?></label>
<input type="radio" name="wp_datepicker_wpadmin" id="wp_datepicker_wpadmin_yes" value="1" <?php checked($wp_datepicker_wpadmin); ?> /><label for="wp_datepicker_wpadmin_yes"><?php _e('Enable'); ?></label>
<input type="radio" name="wp_datepicker_wpadmin" id="wp_datepicker_wpadmin_no" value="0" <?php checked(!$wp_datepicker_wpadmin); ?> /><label for="wp_datepicker_wpadmin_no"><?php _e('Disable'); ?></label> 
<small><?php _e( 'Will implement datepicker in wp-admin pages as well.', 'wp-datepicker' ); ?></small>
</div>

<div class="wp_datepicker_months">
<label for=""><?php _e( 'Weekends?', 'wp-datepicker' ); ?></label>
<input type="radio" name="wp_datepicker_weekends" id="wp_datepicker_weekends_yes" value="0" <?php checked(!$wp_datepicker_weekends); ?> /><label for="wp_datepicker_weekends_yes"><?php _e('Enable'); ?></label>
<input type="radio" name="wp_datepicker_weekends" id="wp_datepicker_weekends_no" value="1" <?php checked($wp_datepicker_weekends); ?> /><label for="wp_datepicker_weekends_no"><?php _e('Disable'); ?></label>
<small><?php _e( 'Will remove Saturdays & Sundays from date picker. Some service businesses don\'t offer weekend service.', 'wp-datepicker' ); ?></small>
</div>

<?php if($wpdp_pro){ ?>
<div class="wp_datepicker_months beforeShowDay">
<label for=""><?php _e( 'Any other requirements with weekdays?', 'wp-datepicker' ); ?></label>
<div class="textarea_div">
<textarea name="wp_datepicker_beforeShowDay" id="wp_datepicker_beforeShowDay" placeholder="<?php _e('Insert your custom code here for beforeShowDay'); ?>"><?php echo $wp_datepicker_beforeShowDay; ?></textarea><br /><br />
<small><?php echo __( 'e.g.').' '.__('Should enable first Thursday Only?').'<br /><pre>function (date) { <br />var day = date.getDay(); <br />return [(day == 4) && date.getDate()<8];  <br />}</pre><br />'.__('It will disable every date except first thursday of each month.').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' ); ?></small><br />
</div>
</div>
<?php } ?>

<div class="wp_datepicker_months">
<label><?php _e( 'Need months in full or short?', 'wp-datepicker' ); ?></label>
<input type="radio" name="wp_datepicker_months" id="wp_datepicker_months_yes" value="1" <?php checked($wp_datepicker_months); ?> /><label for="wp_datepicker_months_yes"><?php _e('Short'); ?></label>
<input type="radio" name="wp_datepicker_months" id="wp_datepicker_months_no" value="0" <?php checked(!$wp_datepicker_months); ?> /><label for="wp_datepicker_months_no"><?php _e('Full'); ?></label>
<small><?php _e( 'e.g. Sep or September?', 'wp-datepicker' ); ?></small>
</div>


<input type="text" width="100%" value="<?php echo wpdp_slashes($wpdp_selectors); ?>" class="wpdp_selectors" name="wp_datepicker" placeholder="<?php _e('Enter', 'wp-datepicker'); ?> id, class, name based and/or type based CSS <?php _e('selector', 'wp-datepicker'); ?>" /><br />
<small>
<?php _e('You can enter multiple selectors as CSV', 'wp-datepicker'); ?> (<?php _e('Comma Separated Values', 'wp-datepicker'); ?>).<br />

e.g. <br />
<span class="wpdp_1">#datepicker</span><br />
or<br />
<span class="wpdp_2">#datepicker, .hasDatepicker, .date-field</span><br />
and<br />
<span class="wpdp_3"><?php _e('Sample', 'wp-datepicker'); ?> HTML: &lt;input type=&quot;text&quot; id=&quot;datepicker&quot; /&gt;</span>
</small>

<?php  ?>
<?php if($wpdp_pro){ wpdp_pro_settings(); }else{ wpdp_free_settings(); } ?>


<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'wp-datepicker' ); ?>" /></p>
</div>
</form>
</div>
<style type="text/css">
.update-nag, #message{ display:none; }
</style>