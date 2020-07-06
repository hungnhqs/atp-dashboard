<?php

/*
 * Plugin Name: Hùng NH Menu Mobile
 * Plugin URI: https://hungnh.com
 * Description: Show menu mobile on your website (support desktop and mobile).
 * Version: 1.0.6
 * Author: Hùng NH
 * Author URI: https://hungnh.com
 * Domain Path: /languages/
 * Text Domain: hungnh-mb
 */

if ( ! class_exists('HungNHMB') ) :
	class HungNHMB {
		public function __construct() {
			//Backend
			//add_action('admin_enqueue_scripts', array($this, 'Hungnh_MB_admin_scripts_and_styles'));
			add_action('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'Hungnh_MB_settings_link'));
			add_action('admin_menu', array($this, 'Hungnh_MB_create_menu'));
			add_filter('plugin_row_meta', array( $this, 'Hungnh_MB_add_plugin_links' ), 10, 2);
			add_action('admin_init', array($this, 'Hungnh_MB_register_settings'));
			
			//Frontend
			add_action('wp_enqueue_scripts', array($this, 'Hungnh_MB_scripts_and_styles'));
			add_action('wp_footer', array($this, 'Hung_NH_MB'));
			//action
			add_action('wp_ajax_saveCallMeBack', array($this,'saveCallMeBack'));
            add_action('wp_ajax_nopriv_saveCallMeBack', array($this,'saveCallMeBack'));
		}
		public function Hungnh_MB_settings_link($links){
			$settings_url = admin_url('admin.php?page=Hung-NH-MB');
			$settings_link = '<a href="' . $settings_url . '">'.__('Settings','hungnh-mb').'</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
		public function Hungnh_MB_add_plugin_links($links, $file) {
			if ( $file == plugin_basename(dirname(__FILE__).'/Hung-NH-MB.php') ) { $links[] = '<a href="https://www.facebook.com/profile.php?id=100013987777777" target="_blank">' . esc_html__('Messenger', 'hungnh-mb') . '</a>'; }
			return $links;
		}
		public function Hungnh_MB_scripts_and_styles() {
			wp_deregister_style( 'hungnh-mb-style-css' );
			wp_register_style( 'hungnh-mb-style-css', plugins_url( '/css/style.css', __FILE__ ));
			wp_enqueue_style( 'hungnh-mb-style-css' );
			$gettax = array('hungnhmb_gettax_nonce' => wp_create_nonce('hungnh_key_plugin'));
			
			wp_deregister_script( 'hungnh-mb-script-js' );
			wp_register_script( 'hungnh-mb-script-js', plugins_url('/js/script.js', __FILE__ ), array('jquery'));
			wp_localize_script( 'hungnh-mb-script-js','hungnhmb_gettax', $gettax );
			wp_enqueue_script( 'hungnh-mb-script-js' );
		}
		public function Hungnh_MB_get_version() {
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_version = $plugin_data['Version'];
			return $plugin_version;
		}
		public function saveCallMeBack() {
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		$thoigiangui = date('d/m/Y - H:i:s');
		$hungnhmb_text_subject1 = get_option('hungnhmb_link_mailto');
        $hungnhmb_link_mailto1 = get_option('hungnhmb_link_mailto');
        if ($hungnhmb_text_subject1 == "" || $hungnhmb_text_subject1 == NULL) { $hungnhmb_text_subject1 = "Gửi từ Form Gọi lại"; }
	    if ($hungnhmb_link_mailto1 == "" || $hungnhmb_link_mailto1 == NULL) { $hungnhmb_link_mailto1 = "hungnh.qs@gmail.com"; }
if ( isset( $_POST['hungnhmb_gettax'] ) &&
wp_verify_nonce( $_POST['hungnhmb_gettax'], 'hungnh_key_plugin' ) ) {
	$from = $hungnhmb_text_subject1;
	$to = $hungnhmb_link_mailto1;
	$subject = $hungnhmb_text_subject1." ".$thoigiangui;
	$message = "Vấn đề: ".$_POST['reason']."\r\nSố điện thoại: ".$_POST['phone']."\r\nKhu vực: ".$_POST['province']."\r\nGiờ gọi lại: ".$_POST['time'];
	//$headers = "From:" . $hungnhmb_text_subject1;
	$headers[] = 'From: '.$to;
    $headers[] = 'Cc: '.$to;
	wp_mail($to,$subject,$message, $headers);
	$Response = array('success' => 'true', 'msg' => 'Gửi thành công');
    echo json_encode($Response);
    exit;
} else {
$Response = array('err' => 'fail', 'msg' => 'Lỗi! Vui lòng thử lại sau!');
echo json_encode($Response);
exit;
}
        }
		public function Hungnh_MB_register_settings() {
			/*Display*/
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_page_id');
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_hide_pc' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_hide_mb');
			/*Mobile*/
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_cart' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_home' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_button_call' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_call' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_advice' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_advice' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_support' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_support' );
			/*PC*/
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_favourite' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_favourite' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_chat_zalo' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_chat_zalo' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_messenger' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_messenger' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_livechat' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_livechat' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_callback' );	
			/*Form call back*/
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_note_callback_1' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_note_callback_2' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_note_callback_3' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_text_subject' );
			register_setting( 'Hungnh_MB_settings_group', 'hungnhmb_link_mailto' );
		}
		public function Hungnh_MB_create_menu() {
			add_menu_page(__('Menu Mobile Settings','hungnh-mb'),__('Menu Mobile','hungnh-mb'), 'manage_options', 'Hung-NH-MB', array( $this, 'Hungnh_MB_settings_page' ), plugins_url('/img/icon-menu-hungnh.png', __FILE__), 30);
		}
		public function Hungnh_MB_settings_page() {
			if( isset($_GET['settings-updated']) ) { echo '<div id="message" class="updated notice is-dismissible"><p><strong>'.__('Saved!','hungnh-mb').'</strong></p></div>';}
			echo '<div class="hungnh-menu-mobile wrap">
			<h1>'.__('Menu Mobile Settings','hungnh-mb').' <small style="font-size:60%;color:#888;margin-left:5px;">('.__('Version','hungnh-mb').' '.$this->Hungnh_MB_get_version().')</small></h1>
			<form method="post" action="options.php">';
			settings_fields( 'Hungnh_MB_settings_group' );
			echo '<h3>'.__('Display','hungnh-mb').'</h3>
			<table class="form-table">
			<tr>
			<th><label for="hungnhmb_page_id">'.__('IDs pages need show Menu Mobile','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_page_id" id="hungnhmb_page_id" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_page_id').'" placeholder="2019,5924 (hiện tất cả các trang nếu để trống)" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_mb_pc">'.__('Setting Other','hungnh-mb').'</label></th>
			<td>';
			?>
			<label for="hungnhmb_hide_pc"><input type="checkbox" name="hungnhmb_hide_pc" value="1" id="hungnhmb_hide_pc" <?php if(get_option('hungnhmb_hide_pc')) echo "checked"; ?>> <?php echo __('Hide on Desktop','hungnh-mb');?></label> <br><label for="hungnhmb_hide_mb"><input type="checkbox" name="hungnhmb_hide_mb" value="1" id="hungnhmb_hide_mb" <?php if(get_option('hungnhmb_hide_mb')) echo "checked"; ?>> <?php echo __('Hide on mobile','hungnh-mb');?></label>
			<?php echo '</td></tr>
			</table>';
			echo '<h3>'.__('Setting Menu PC','hungnh-mb').'</h3><table class="form-table">
			<tr>
				<th><label for="hungnhmb_text_favourite">'.__('Text show Favourite','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_text_favourite" id="hungnhmb_text_favourite" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_favourite').'" placeholder="Yêu thích" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_link_favourite">'.__('Link Favourite','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_link_favourite" id="hungnhmb_link_favourite" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_favourite').'" placeholder="/top-san-pham-duoc-yeu-thich" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_text_chat_zalo">'.__('Text show Chat Zalo','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_text_chat_zalo" id="hungnhmb_text_chat_zalo" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_chat_zalo').'" placeholder="Chat Zalo" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_link_chat_zalo">'.__('Link Chat Zalo','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_link_chat_zalo" id="hungnhmb_link_chat_zalo" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_chat_zalo').'" placeholder="https://zalo.me/0967060091" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_text_messenger">'.__('Text show Messenger','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_text_messenger" id="hungnhmb_text_messenger" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_messenger').'" placeholder="Messenger" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_link_messenger">'.__('Link Messenger','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_link_messenger" id="hungnhmb_link_messenger" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_messenger').'" placeholder="https://m.me/100013987777777" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_text_livechat">'.__('Text show Live Chat','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_text_livechat" id="hungnhmb_text_livechat" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_livechat').'" placeholder="Live Chat" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_link_livechat">'.__('Link Live Chat','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_link_livechat" id="hungnhmb_link_livechat" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_livechat').'" placeholder="javascript:void(Tawk_API.toggle())" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_text_callback">'.__('Text show Call back','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_text_callback" id="hungnhmb_text_callback" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_callback').'" placeholder="Yêu cầu gọi lại" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_note_callback_1">'.__('Note callback line 1','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_note_callback_1" id="hungnhmb_note_callback_1" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_note_callback_1').'" placeholder="Hotline tư vấn" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_note_callback_2">'.__('Note callback line 2','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_note_callback_2" id="hungnhmb_note_callback_2" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_note_callback_2').'" placeholder="0967060091" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_note_callback_3">'.__('Note callback line 3','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_note_callback_3" id="hungnhmb_note_callback_3" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_note_callback_3').'" placeholder="Hoặc để lại số điện thoại để Hungnh.com gọi lại trong ít phút" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_text_subject">'.__('Subject mail','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_text_subject" id="hungnhmb_text_subject" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_subject').'" placeholder="Gửi từ Form Gọi lại" /></td>
			</tr>
			<tr>
				<th><label for="hungnhmb_link_mailto">'.__('Mail to','hungnh-mb').'</label></th>
				<td><input type="text" name="hungnhmb_link_mailto" id="hungnhmb_link_mailto" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_mailto').'" placeholder="hungnh.qs@gmail.com" /></td>
			</tr>
			
			</table>';
			echo '<h3>'.__('Setting Menu Mobile','hungnh-mb').'</h3><table class="form-table">
			<tr>
			<th><label for="hungnhmb_link_cart">'.__('Link button cart','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_link_cart" id="hungnhmb_link_cart" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_cart').'" placeholder="/gio-hang/" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_link_home">'.__('Link button home','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_link_home" id="hungnhmb_link_home" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_home').'" placeholder="/" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_text_button_call">'.__('Text button call','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_text_button_call" id="hungnhmb_text_button_call" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_button_call').'" placeholder="Gọi điện" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_link_call">'.__('Link button call','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_link_call" id="hungnhmb_link_call" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_call').'" placeholder="tel:0967060091" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_text_advice">'.__('Text button advice','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_text_advice" id="hungnhmb_text_advice" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_advice').'" placeholder="Tư vấn" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_link_advice">'.__('Link button advice','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_link_advice" id="hungnhmb_link_advice" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_advice').'" placeholder="https://m.me/100013987777777" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_text_support">'.__('Text button support','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_text_support" id="hungnhmb_text_support" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_text_support').'" placeholder="Hỗ trợ KT" /></td>
			</tr>
			<tr>
			<th><label for="hungnhmb_link_support">'.__('Link button support','hungnh-mb').'</label></th>
			<td><input type="text" name="hungnhmb_link_support" id="hungnhmb_link_support" class="regular-text input-text-wrap" value="'.get_option('hungnhmb_link_support').'" placeholder="https://zalo.me/0967060091" /></td>
			</tr>
			<tr>
			</table>';
			submit_button();
			echo '</form>';?>
			<?php echo '</div><!-- /.wrap -->';
		}
		public function Hung_NH_MB() {
			/*Display*/
			$hungnhmb_page_id = get_option('hungnhmb_page_id');
			$hungnhmb_hide_pc = get_option('hungnhmb_hide_pc');
			$hungnhmb_hide_mb = get_option('hungnhmb_hide_mb');
			
			if($hungnhmb_hide_pc == 1) { $hungnhmb_show_hide_pc = "none"; }
			else { $hungnhmb_show_hide_pc = "block"; }
			if($hungnhmb_hide_mb == 1) { $hungnhmb_show_hide_mb = "none"; }
			else { $hungnhmb_show_hide_mb = "block"; }

			$hungnhmb_page_id = esc_attr (get_option ( 'hungnhmb_page_id' ));
			if ( ! empty( $hungnhmb_page_id ) ) { 
				$hungnhmb_page_id = explode( ',', $hungnhmb_page_id );
				if ( is_page( $hungnhmb_page_id ) ) { 
					echo '<style type="text/css">.hungnh-m-b.pc.mbi { display: block; }</style>';
				} else {
					echo '<style type="text/css">.hungnh-m-b.pc.mbi { display: none; }</style>';
				}
			}
			/*Mobile*/
			$hungnhmb_link_cart = get_option('hungnhmb_link_cart');
			$hungnhmb_link_home = get_option('hungnhmb_link_home');
			$hungnhmb_text_button_call = get_option('hungnhmb_text_button_call');
			$hungnhmb_link_call = get_option('hungnhmb_link_call');
			$hungnhmb_text_advice = get_option('hungnhmb_text_advice');
			$hungnhmb_link_advice = get_option('hungnhmb_link_advice');
			$hungnhmb_text_support = get_option('hungnhmb_text_support');
			$hungnhmb_link_support = get_option('hungnhmb_link_support');
			
			if ($hungnhmb_link_cart == "" || $hungnhmb_link_cart == NULL) { $hungnhmb_link_cart = "/gio-hang/"; }
			if ($hungnhmb_link_home == "" || $hungnhmb_link_home == NULL) { $hungnhmb_link_home = "/"; }
			if ($hungnhmb_text_button_call == "" || $hungnhmb_text_button_call == NULL) { $hungnhmb_text_button_call = "Gọi điện"; }
			if ($hungnhmb_link_call == "" || $hungnhmb_link_call == NULL) { $hungnhmb_link_call = "tel:0967060091"; }
			if ($hungnhmb_text_advice == "" || $hungnhmb_text_advice == NULL) { $hungnhmb_text_advice = "Tư vấn"; }
			if ($hungnhmb_link_advice == "" || $hungnhmb_link_advice == NULL) { $hungnhmb_link_advice = "https://m.me/100013987777777"; }
			if ($hungnhmb_text_support == "" || $hungnhmb_text_support == NULL) { $hungnhmb_text_support = "Hỗ trợ KT"; }
			if ($hungnhmb_link_support == "" || $hungnhmb_link_support == NULL) { $hungnhmb_link_support = "https://zalo.me/0967060091"; }

			/*PC*/
			$hungnhmb_text_favourite = get_option('hungnhmb_text_favourite');
			$hungnhmb_link_favourite = get_option('hungnhmb_link_favourite');
			$hungnhmb_text_chat_zalo = get_option('hungnhmb_text_chat_zalo');
			$hungnhmb_link_chat_zalo = get_option('hungnhmb_link_chat_zalo');
			$hungnhmb_text_messenger = get_option('hungnhmb_text_messenger');
			$hungnhmb_link_messenger = get_option('hungnhmb_link_messenger');
			$hungnhmb_text_livechat = get_option('hungnhmb_text_livechat');
			$hungnhmb_link_livechat = get_option('hungnhmb_link_livechat');
			$hungnhmb_text_callback = get_option('hungnhmb_text_callback');
			if ($hungnhmb_text_favourite == "" || $hungnhmb_text_favourite == NULL) { $hungnhmb_text_favourite = "Yêu thích"; }
			if ($hungnhmb_link_favourite == "" || $hungnhmb_link_favourite == NULL) { $hungnhmb_link_favourite = "/top-san-pham-duoc-yeu-thich"; }
			if ($hungnhmb_text_chat_zalo == "" || $hungnhmb_text_chat_zalo == NULL) { $hungnhmb_text_chat_zalo = "Chat Zalo"; }
			if ($hungnhmb_link_chat_zalo == "" || $hungnhmb_link_chat_zalo == NULL) { $hungnhmb_link_chat_zalo = "https://zalo.me/0967060091"; }
			if ($hungnhmb_text_messenger == "" || $hungnhmb_text_messenger == NULL) { $hungnhmb_text_messenger = "Messenger"; }
			if ($hungnhmb_link_messenger == "" || $hungnhmb_link_messenger == NULL) { $hungnhmb_link_messenger = "https://m.me/100013987777777"; }
			if ($hungnhmb_text_livechat == "" || $hungnhmb_text_livechat == NULL) { $hungnhmb_text_livechat = "Live Chat"; }
			if ($hungnhmb_link_livechat == "" || $hungnhmb_link_livechat == NULL) { $hungnhmb_link_livechat = "javascript:void(Tawk_API.toggle())"; }
			if ($hungnhmb_text_callback == "" || $hungnhmb_text_callback == NULL) { $hungnhmb_text_callback = "Yêu cầu gọi lại"; }
			/*Form call back*/
			$hungnhmb_note_callback_1 = get_option('hungnhmb_note_callback_1');
			$hungnhmb_note_callback_2 = get_option('hungnhmb_note_callback_2');
			$hungnhmb_note_callback_3 = get_option('hungnhmb_note_callback_3');
			$hungnhmb_text_subject = get_option('hungnhmb_text_subject');
			$hungnhmb_link_mailto = get_option('hungnhmb_link_mailto');
			if ($hungnhmb_note_callback_1 == "" || $hungnhmb_note_callback_1 == NULL) { $hungnhmb_note_callback_1 = "Hotline tư vấn"; }
			if ($hungnhmb_note_callback_2 == "" || $hungnhmb_note_callback_2 == NULL) { $hungnhmb_note_callback_2 = "0967060091"; }
			if ($hungnhmb_note_callback_3 == "" || $hungnhmb_note_callback_3 == NULL) { $hungnhmb_note_callback_3 = "Hoặc để lại số điện thoại để Hungnh.com gọi lại trong ít phút"; }
			if ($hungnhmb_text_subject == "" || $hungnhmb_text_subject == NULL) { $hungnhmb_text_subject = "Gửi từ Form Gọi lại"; }
			if ($hungnhmb_link_mailto == "" || $hungnhmb_link_mailto == NULL) { $hungnhmb_link_mailto = "hungnh.qs@gmail.com"; }
			?>
<div class="hungnh-m-b pc mbi">
<?php if ( wp_is_mobile() ) : ?>
    <ul class="menu-mobile-hungnh">
		<li><a href="<?php echo $hungnhmb_link_cart;?>" rel="nofollow" class="button_footer"><i class="fa fa-shopping-cart" aria-hidden="true"></i><span id="count-cart">0</span>Giỏ hàng</a></li>
		<li><a href="<?php echo $hungnhmb_link_home;?>" rel="nofollow" class="button_footer"> <i class="fa fa-home" aria-hidden="true"></i>Trang chủ</a></li>
		<li>
			<a href="<?php echo $hungnhmb_link_call;?>" rel="nofollow" class="button_footer">
				<span class="phone_animation animation-shadow">
					<i class="icon-phone-w" aria-hidden="true"></i>
				</span>
				<span class="btn_phone_name"><?php echo $hungnhmb_text_button_call;?></span>
			</a>
		</li>
		<li><a href="<?php echo $hungnhmb_link_advice;?>" class="button_footer chat_animation">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="圖層_1" x="0px" y="0px" viewBox="0 0 100 100" style="transform-origin: 50px 50px 0px;" xml:space="preserve"><g style="transform-origin: 50px 50px 0px;"><g style="transform-origin: 50px 50px 0px; transform: scale(0.8);"><g style="transform-origin: 50px 50px 0px;"><g><style type="text/css" class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -1s; animation-direction: normal;">.st0{fill:#849B87;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st1{fill:#A0C8D7;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st2{fill:#F5E6C8;} .st3{fill:#ABBD81;} .st4{fill:#F5E6C8;stroke:#000000;stroke-width:2.5;stroke-miterlimit:10;} .st5{fill:#333333;} .st6{fill:#F5E6C8;stroke:#000000;stroke-width:3.5;stroke-miterlimit:10;} .st7{fill:#849B87;} .st8{fill:#F5E6C8;stroke:#333333;stroke-width:4;stroke-miterlimit:10;} .st9{fill:#C33737;} .st10{fill:#A0C8D7;} .st11{fill:#F5E6C8;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st12{fill:#A3A3A3;} .st13{fill:#F5E6C8;stroke:#000000;stroke-width:3.8621;stroke-miterlimit:10;} .st14{fill:#FFDC6C;} .st15{fill:#ABBD81;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st16{fill:#E15B64;} .st17{fill:#F5E6C8;stroke:#333333;stroke-width:3.6781;stroke-miterlimit:10;} .st18{fill:#F5E6C8;stroke:#333333;stroke-width:2.7586;stroke-miterlimit:10;} .st19{fill:#F5E6C8;stroke:#333333;stroke-width:3.9518;stroke-miterlimit:10;} .st20{fill:#FFFFFF;stroke:#333333;stroke-width:3.9092;stroke-miterlimit:10;} .st21{fill:#FFFFFF;stroke:#333333;stroke-width:3.6699;stroke-miterlimit:10;} .st22{fill:#F5E6C8;stroke:#000000;stroke-width:3;stroke-miterlimit:10;} .st23{fill:#F47E60;} .st24{fill:#F8B26A;} .st25{fill:#FFFFFF;} .st26{fill:#F5E6C8;stroke:#000000;stroke-width:3.9059;stroke-miterlimit:10;} .st27{fill:#F5E6C8;stroke:#000000;stroke-width:4;stroke-miterlimit:10;} .st28{fill:#C33636;} .st29{fill:#F5E6C8;stroke:#000000;stroke-width:3.8153;stroke-miterlimit:10;} .st30{fill:#E0E0E0;} .st31{fill:#F5E6C8;stroke:#000000;stroke-width:2.9643;stroke-miterlimit:10;} .st32{fill:#E15B64;stroke:#000000;stroke-width:2.9419;stroke-miterlimit:10;} .st33{fill:#666666;} .st34{fill:#FCEDCE;} .st35{fill:#FFF2D9;} .st36{fill:#7A8F7C;} .st37{fill:#96B099;}</style><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.9375s; animation-direction: normal;"><path class="st8" d="M72.903,14.078h-0.603H15.427c-4.247,0-7.927,3.078-7.927,7.326v27.762v2.19c0,4.247,3.679,8.055,7.927,8.055 h6.24v14.091c6.611-2.188,11.916-7.48,13.731-14.091h37.505c4.247,0,7.319-3.808,7.319-8.055V21.403 C80.222,17.156,77.15,14.078,72.903,14.078z" fill="#ffffff" stroke="rgb(51, 51, 51)" style="fill: rgb(255, 255, 255); stroke: rgb(51, 51, 51);"></path></g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.875s; animation-direction: normal;"><path class="st12" d="M85.852,25.872V51.59c0,6.901-6.101,12.503-13.059,12.503H38.602c-1.074,2.849-2.545,4.611-4.36,6.656 c0.704,0.214,1.45-0.008,2.224-0.008h28.455c1.825,7.598,6.685,13.13,14.282,15.329V70.742h3.083c5.772,0,10.213-3.988,10.213-9.759 V33.681C92.5,29.526,89.651,26.135,85.852,25.872z" fill="rgb(163, 163, 163)" style="fill: rgb(163, 163, 163);"></path></g><g style="transform-origin: 50px 50px 0px;"><g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.8125s; animation-direction: normal;"><circle class="st36" cx="23.7" cy="37" r="4.5" fill="#5ea9dd" style="fill: rgb(94, 169, 221);"></circle></g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.75s; animation-direction: normal;"><circle class="st7" cx="43.5" cy="37" r="4.5" fill="#5ea9dd" style="fill: rgb(94, 169, 221);"></circle></g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.6875s; animation-direction: normal;"><circle class="st37" cx="63.3" cy="37" r="4.5" fill="#5ea9dd" style="fill: rgb(94, 169, 221);"></circle></g></g></g><metadata xmlns:d="https://loading.io/stock/" class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.625s; animation-direction: normal;"><d:name class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.5625s; animation-direction: normal;">discussion</d:name><d:tags class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.5s; animation-direction: normal;">sending,typing,thread,interview,talk,chitchat,chat,discussion,conversation</d:tags><d:license class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.4375s; animation-direction: normal;">rf</d:license><d:slug class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.375s; animation-direction: normal;">bpwnfg</d:slug></metadata></g></g></g></g>
			<style type="text/css" class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.3125s; animation-direction: normal;">
				@keyframes ld-breath { 0% { -webkit-transform: scale(0.86); transform: scale(0.86); } 50% { -webkit-transform: scale(1.06); transform: scale(1.06); } 100% { -webkit-transform: scale(0.86); transform: scale(0.86); } }
				@-webkit-keyframes ld-breath { 0% { -webkit-transform: scale(0.86); transform: scale(0.86); } 50% { -webkit-transform: scale(1.06); transform: scale(1.06); } 100% { -webkit-transform: scale(0.86); transform: scale(0.86); } }
				.ld.ld-breath { -webkit-animation: ld-breath 1s infinite; animation: ld-breath 1s infinite; }
			</style></svg>    
			<span class="btn_chat_name"><?php echo $hungnhmb_text_advice;?></span></a>
		</li>
		<li class="fixed-footer-zalo"><a href="<?php echo $hungnhmb_link_support;?>" rel="nofollow" class="button_footer" target="_blank"><i class="icon-zalo"></i><?php echo $hungnhmb_text_support;?></a></li>
	</ul>
<?php else : ?>
<div class="cover-background"></div>
<div class="cover-background-fullscreen"></div>
<div class="cover-scrolltop-background"></div>
<div class="menu-pc-hungnh">
    <ul>
        <li><a href="<?php echo $hungnhmb_link_favourite;?>"><i class="icon-hungnh-heart"></i><?php echo $hungnhmb_text_favourite;?></a></li>
        <li><a href="<?php echo $hungnhmb_link_chat_zalo;?>" rel="nofollow" target="_blank"><i class="icon-hungnh-zalo-circle2"></i><?php echo $hungnhmb_text_chat_zalo;?></a></li>
        <li><a href="<?php echo $hungnhmb_link_messenger;?>" rel="nofollow" target="_blank"><i class="icon-hungnh-messenger"></i><?php echo $hungnhmb_text_messenger;?></a></li>
        <li><a href="<?php echo $hungnhmb_link_livechat;?>" class="chat_animation">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="圖層_1" x="0px" y="0px" viewBox="0 0 100 100" style="transform-origin: 50px 50px 0px;" xml:space="preserve"><g style="transform-origin: 50px 50px 0px;"><g style="transform-origin: 50px 50px 0px; transform: scale(0.8);"><g style="transform-origin: 50px 50px 0px;"><g><style type="text/css" class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -1s; animation-direction: normal;">.st0{fill:#849B87;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st1{fill:#A0C8D7;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st2{fill:#F5E6C8;} .st3{fill:#ABBD81;} .st4{fill:#F5E6C8;stroke:#000000;stroke-width:2.5;stroke-miterlimit:10;} .st5{fill:#333333;} .st6{fill:#F5E6C8;stroke:#000000;stroke-width:3.5;stroke-miterlimit:10;} .st7{fill:#849B87;} .st8{fill:#F5E6C8;stroke:#333333;stroke-width:4;stroke-miterlimit:10;} .st9{fill:#C33737;} .st10{fill:#A0C8D7;} .st11{fill:#F5E6C8;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st12{fill:#A3A3A3;} .st13{fill:#F5E6C8;stroke:#000000;stroke-width:3.8621;stroke-miterlimit:10;} .st14{fill:#FFDC6C;} .st15{fill:#ABBD81;stroke:#333333;stroke-width:3;stroke-miterlimit:10;} .st16{fill:#E15B64;} .st17{fill:#F5E6C8;stroke:#333333;stroke-width:3.6781;stroke-miterlimit:10;} .st18{fill:#F5E6C8;stroke:#333333;stroke-width:2.7586;stroke-miterlimit:10;} .st19{fill:#F5E6C8;stroke:#333333;stroke-width:3.9518;stroke-miterlimit:10;} .st20{fill:#FFFFFF;stroke:#333333;stroke-width:3.9092;stroke-miterlimit:10;} .st21{fill:#FFFFFF;stroke:#333333;stroke-width:3.6699;stroke-miterlimit:10;} .st22{fill:#F5E6C8;stroke:#000000;stroke-width:3;stroke-miterlimit:10;} .st23{fill:#F47E60;} .st24{fill:#F8B26A;} .st25{fill:#FFFFFF;} .st26{fill:#F5E6C8;stroke:#000000;stroke-width:3.9059;stroke-miterlimit:10;} .st27{fill:#F5E6C8;stroke:#000000;stroke-width:4;stroke-miterlimit:10;} .st28{fill:#C33636;} .st29{fill:#F5E6C8;stroke:#000000;stroke-width:3.8153;stroke-miterlimit:10;} .st30{fill:#E0E0E0;} .st31{fill:#F5E6C8;stroke:#000000;stroke-width:2.9643;stroke-miterlimit:10;} .st32{fill:#E15B64;stroke:#000000;stroke-width:2.9419;stroke-miterlimit:10;} .st33{fill:#666666;} .st34{fill:#FCEDCE;} .st35{fill:#FFF2D9;} .st36{fill:#7A8F7C;} .st37{fill:#96B099;}</style><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.9375s; animation-direction: normal;"><path class="st8" d="M72.903,14.078h-0.603H15.427c-4.247,0-7.927,3.078-7.927,7.326v27.762v2.19c0,4.247,3.679,8.055,7.927,8.055 h6.24v14.091c6.611-2.188,11.916-7.48,13.731-14.091h37.505c4.247,0,7.319-3.808,7.319-8.055V21.403 C80.222,17.156,77.15,14.078,72.903,14.078z" fill="#ffffff" stroke="rgb(51, 51, 51)" style="fill: rgb(255, 255, 255); stroke: rgb(51, 51, 51);"></path></g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.875s; animation-direction: normal;"><path class="st12" d="M85.852,25.872V51.59c0,6.901-6.101,12.503-13.059,12.503H38.602c-1.074,2.849-2.545,4.611-4.36,6.656 c0.704,0.214,1.45-0.008,2.224-0.008h28.455c1.825,7.598,6.685,13.13,14.282,15.329V70.742h3.083c5.772,0,10.213-3.988,10.213-9.759 V33.681C92.5,29.526,89.651,26.135,85.852,25.872z" fill="rgb(163, 163, 163)" style="fill: rgb(163, 163, 163);"></path></g><g style="transform-origin: 50px 50px 0px;"><g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.8125s; animation-direction: normal;"><circle class="st36" cx="23.7" cy="37" r="4.5" fill="#5ea9dd" style="fill: rgb(94, 169, 221);"></circle></g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.75s; animation-direction: normal;"><circle class="st7" cx="43.5" cy="37" r="4.5" fill="#5ea9dd" style="fill: rgb(94, 169, 221);"></circle></g><g class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.6875s; animation-direction: normal;"><circle class="st37" cx="63.3" cy="37" r="4.5" fill="#5ea9dd" style="fill: rgb(94, 169, 221);"></circle></g></g></g><metadata xmlns:d="https://loading.io/stock/" class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.625s; animation-direction: normal;">
            <d:name class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.5625s; animation-direction: normal;">discussion</d:name>
            <d:tags class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.5s; animation-direction: normal;">sending,typing,thread,interview,talk,chitchat,chat,discussion,conversation</d:tags>
            <d:license class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.4375s; animation-direction: normal;">rf</d:license>
            <d:slug class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.375s; animation-direction: normal;">bpwnfg</d:slug>
            </metadata></g></g></g></g>
            <style type="text/css" class="ld ld-breath" style="transform-origin: 50px 50px 0px; animation-duration: 1s; animation-delay: -0.3125s; animation-direction: normal;">
            @keyframes ld-breath { 0% { -webkit-transform: scale(0.86); transform: scale(0.86); } 50% { -webkit-transform: scale(1.06); transform: scale(1.06); } 100% { -webkit-transform: scale(0.86); transform: scale(0.86); } }
            @-webkit-keyframes ld-breath { 0% { -webkit-transform: scale(0.86); transform: scale(0.86); } 50% { -webkit-transform: scale(1.06); transform: scale(1.06); } 100% { -webkit-transform: scale(0.86); transform: scale(0.86); } }
            .ld.ld-breath { -webkit-animation: ld-breath 1s infinite; animation: ld-breath 1s infinite; }
            </style></svg>    
            <?php echo $hungnhmb_text_livechat;?></a>
        </li>
        <li class="callMeBack">
            <div class="custom-tooltips">
                <a id="requestCallbackBtn" class="toggle-tooltips-click"><i class="icon-hungnh-call"></i><?php echo $hungnhmb_text_callback; ?></a>
                <div class="custom-tooltips-container tooltips-right">
                    <div class="toggle-tooltips-body">
                        <div class="callMeBack-cnt">
                            <button type="button" class="close-custom-tooltips callMeBack-close" id="closeCallMeBack"><i class="icon-hungnh-close"></i></button>
                            <p>
                                <strong><?php echo $hungnhmb_note_callback_1;?></strong>
                                <br/>
                                <span><?php echo $hungnhmb_note_callback_2;?></span>
                                <br/>
                                <?php echo $hungnhmb_note_callback_3;?>
                                </p>
                            <p id="send_request_msg" class="ajax_result_msg"></p>
                            <div class="callMeBack-row">
                                <div class="callMeBack-row-left">
                                    <input type="text" id="callMeBack-reason" placeholder="Về việc"/>
                                </div>
                                <div class="callMeBack-row-right">
                                    <input type="text" id="callMeBack-time" placeholder="Giờ (24h)" maxlength="2" onkeyup="isNumberKey(event);"/>
                                    <div class="select-field-hungnh">
                                        <input type="text" id="callMeBack-province-input" readonly="">
                                        <i class="fa fa-caret-down"></i>
                                        <select id="callMeBack-province">
                                            <option value="hanoi" selected>Hà Nội</option>
                                            <option value="hcm">HCM</option>
                                            <option value="others">Tỉnh khác</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="callMeBack-row">
                                <div class="callMeBack-row-left">
                                    <input type="text" id="callMeBack-phone" onkeyup="isNumberKey(event);" placeholder="Nhập số điện thoại"/>
                                </div>
                                <div class="callMeBack-row-right">
                                    <button class="button" onclick="submitCallbackForm();">Gọi lại cho tôi</button>
                                </div>
                            </div>
                            <a class="close-custom-tooltips" id="callBackmeDontShowToday">Không hiển thị trong 24h</a>
                        </div>
                        <div class='loading_img callMeBack-loading'>
                            <img src='/wp-content/plugins/hungnh-menu-mobile/img/loading.gif'/>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li>
            <a href="#" rel="nofollow">
                <i class="icon-hungnh-angle-up" aria-hidden="true" title="<?php echo __('Back to top','hungnh-mb'); ?>"></i>
            </a>
        </li>
    </ul>
</div>
<?php endif; ?>
</div>
			<style type="text/css">
			@media (max-width: 780px) {
				.mbi {
					display: <?php echo $hungnhmb_show_hide_mb; ?>;
				}
			}
			@media (min-width: 780px) {
				.pc {
					display: <?php echo $hungnhmb_show_hide_pc; ?>;
				}
			}
		</style> 
	<?php }
}	
new HungNHMB;
endif;
add_filter( 'woocommerce_add_to_cart_fragments', 'hungnh_add_number_cart');
function hungnh_add_number_cart($hungnh_number_cart){
    ob_start();
    ?>
    <span id="count-cart">
        <?php echo WC()->cart->get_cart_contents_count(); ?>
    </span>
    <?php
        $hungnh_number_cart['#count-cart'] = ob_get_clean();
    return $hungnh_number_cart;
}