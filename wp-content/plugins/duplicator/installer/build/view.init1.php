<?php
defined("DUPXABSPATH") or die("");
/** IDE HELPERS */
/* @var $GLOBALS['DUPX_AC'] DUPX_ArchiveConfig */

$_POST['secure-pass'] = isset($_POST['secure-pass']) ? DUPX_U::sanitize_text_field($_POST['secure-pass']) : '' ;
$_POST['secure-try']  = isset($_POST['secure-try'])  ? 1 : 0 ;
$_GET['debug']        = isset($_GET['debug']) ? DUPX_U::sanitize_text_field($_GET['debug']) : 0;
$page_url = DUPX_HTTP::get_request_uri();
$page_err = 0;
$pass_hasher = new DUPX_PasswordHash(8, FALSE);
$pass_check  = $pass_hasher->CheckPassword(base64_encode($_POST['secure-pass']), $GLOBALS['FW_SECUREPASS']);

//FORWARD: password not enabled
if (! $GLOBALS['FW_SECUREON'] && ! $_GET['debug']) {
	DUPX_HTTP::post_with_html($page_url, array('action_step' => '1', 'csrf_token' => DUPX_CSRF::generate('step1')));
	exit;
}

//POSTBACK: valid password
if ($pass_check) {
	DUPX_HTTP::post_with_html($page_url,
		array(
			'action_step' => '1',
			'csrf_token' => DUPX_CSRF::generate('step1'),
			'secure-pass' => $_POST['secure-pass']));
	exit;
}

//ERROR: invalid password
if ($_POST['secure-try'] && ! $pass_check) {
	$page_err = 1;
}
?>

<!-- =========================================
VIEW: STEP 0 - PASSWORD -->
<form method="post" id="i1-pass-form" class="content-form"  data-parsley-validate="" autocomplete="oldpassword">
	<input type="hidden" name="view" value="secure" />
	<input type="hidden" name="csrf_token" value="<?php echo DUPX_CSRF::generate('secure'); ?>">
	<input type="hidden" name="secure-try" value="1" />

	<div class="hdr-main">
		Installer Password
	</div>

	<?php if ($page_err) : ?>
		<div class="error-pane">
			<p>Invalid Password! Please try again. If the problem persists see the more details link below.</p>
		</div>
	<?php endif; ?>

	<div style="text-align: center">
		This file was password protected when it was created.   If you do not remember the password	check the details of the package on	the site where it was created or visit
		the online FAQ for <a href="https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-030-q" target="_blank">more details</a>.
		<br/><br/><br/>

		<div class="i1-pass-area">
			<label for="secure-pass">Enter Password</label>
			<div id="i1-pass-input">
				<input type="password" name="secure-pass" id="secure-pass" required="required"  autocomplete="oldpassword" /><br/>
				<div style="margin-top:7px">
					<input type="checkbox" class="pass-toggle" id="secure-lock" onclick="DUPX.togglePassword()" title="Show/Hide the password">
					<label class="secure-lock" for="secure-lock">Show Password</label>
				</div>
			</div>
			<div style="margin-top: 15px">
				<button type="button" class="default-btn" name="secure-btn" id="secure-btn" onclick="DUPX.checkPassword()">Submit</button>
			</div>
		</div>
	</div>
</form>

<script>
	/**
	 * Submits the password for validation
	 */
	DUPX.checkPassword = function()
	{
		var $form = $('#i1-pass-form');
		$form.parsley().validate();
		if (! $form.parsley().isValid()) {
			return;
		}
		$form.submit();
	}

	/**
	 * Submits the password for validation
	 */
	DUPX.togglePassword = function()
	{
		var $input = $('#secure-pass');
		var $lock  = $('#secure-lock');
		if (($input).attr('type') == 'text') {
			//$lock.html('<i class="fa fa-lock"></i>');
			$input.attr('type', 'password');
		} else {
			//$lock.html('<i class="fa fa-unlock"></i>');
			$input.attr('type', 'text');
		}
	}
</script>
<!-- END OF VIEW INIT 1 -->