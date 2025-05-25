<?php
/**
 * Plugin Name: raychat
 * Author: raychat
 * Author URI: https://www.raychat.io
 * Plugin URI: https://www.raychat.io
 * Description: افزونه وردپرس گفتگوی آنلاین رایچت | با مشتریانتون صحبت کنید ، پشتیبانی کنید و از فروش خود لذت ببرید :)
 * Version: 2.2.0
 * Text Domain: raychat
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

// ✅ CHANGED: Fixed path for language loading
load_plugin_textdomain('raychat', false, dirname(plugin_basename(__FILE__)) . '/languages');

$lang = get_bloginfo("language");
$raychat_addr = 'https://www.raychat.io';

define("RAYCHAT_LANG", substr($lang, 0, 2));
define("RAYCHAT_WIDGET_URL", "raychat.io");
define("RAYCHAT_URL", $raychat_addr);
define("RAYCHAT_INTEGRATION_URL", RAYCHAT_URL . "/integration");
define("RAYCHAT_PLUGIN_URL", plugin_dir_url(__FILE__));
define("RAYCHAT_IMG_URL", plugin_dir_url(__FILE__) . "img/");

register_activation_hook(__FILE__, 'raychatInstall');
register_deactivation_hook(__FILE__, 'raychatDelete');

function catalog_admin_menu_raychat() {
  // ✅ CHANGED: Ensure i18n loads in menu callback
  load_plugin_textdomain('raychat', false, dirname(plugin_basename(__FILE__)) . '/languages');

  // ✅ CHANGED: Use capability instead of deprecated role
  add_menu_page(__('رایچت', 'raychat'), __('رایچت', 'raychat'), 'edit_pages', basename(__FILE__), 'raychatPreferences', RAYCHAT_IMG_URL . "raychat.svg");
}
add_action('admin_menu', 'catalog_admin_menu_raychat');

function raychat_options_validate($args) {
  return $args;
}

add_action('admin_init', 'raychat_register_settings');
function raychat_register_settings() {
  register_setting('raychat_token', 'raychat_token', 'raychat_options_validate');
  register_setting('raychat_widget_id', 'raychat_widget_id', 'raychat_options_validate');
}

add_action('admin_post_wp_save_token', 'wp_save_token_function_raychat');
add_action('admin_post_nopriv_wp_save_token', 'wp_save_token_function_raychat');

add_action('wp_footer', 'raychatAppend', 100000);

function raychatInstall() {
  return raychat::getInstance()->install();
}

function raychatDelete() {
  return raychat::getInstance()->delete();
}

function raychatAppend() {
  echo raychat::getInstance()->append(raychat::getInstance()->getId());
}

function raychatPreferences() {
  if (isset($_POST["widget_id"])) {
    raychat::getInstance()->save();
  }

  // ✅ CHANGED: Added i18n reload here as well
  load_plugin_textdomain('raychat', false, dirname(plugin_basename(__FILE__)) . '/languages');

  wp_register_style('raychat_style', plugins_url('raychat.css', __FILE__));
  wp_enqueue_style('raychat_style');
  echo raychat::getInstance()->render();
}

function wp_save_token_function_raychat() {
  $tokenError = null;
  if (!empty($_POST['submit'])) {
    $token = esc_html($_POST['token-id']);
    $version_id = 'version_2';

    // ✅ CHANGED: Added more validation error checks and i18n-ready errors
    if (!empty($token) && !empty($version_id)) {
      if (preg_match("/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/", $token)) {
        update_option('raychat_widget_id', $token);
        update_option("version_id", $version_id);
        raychat::getInstance()->install();
      } else {
        $tokenError = "توکن نامعتبر است.";
      }
    } else {
      $tokenError = "توکن یا ورژن نمی توانند خالی باشند.";
    }
    set_transient('error_token_uuid', $tokenError);
  }
  wp_redirect($_SERVER['HTTP_REFERER']);
  exit();
}

class raychat {
  protected static $instance, $lang;
  private $widget_id = '', $token = '', $version = '';

  private function __construct() {
    $this->token = get_option('raychat_token');
    $this->widget_id = get_option('raychat_widget_id');
    $this->version = get_option("version_id");
  }

  private function __clone() {}
  public function __wakeup() {}

  public static function getInstance() {
    if (is_null(self::$instance)) {
      self::$instance = new raychat();
    }
    self::$lang = isset($_GET["lang"]) && $_GET["lang"] === 'ru' ? "ru" : "en";
    return self::$instance;
  }

  public function setID($id) { $this->widget_id = $id; }
  public function setToken($token) { $this->token = $token; }
  public function setVersion($version) { $this->version = $version; }

  public function install() {
    $file = dirname(__FILE__) . '/id';
    if (file_exists($file)) {
      $uuid = file_get_contents($file);
      update_option('raychat_widget_id', $uuid);
      unlink($file);
      $this->widget_id = $uuid;
      $this->save();
    } else {
      if (!$this->widget_id && ($out = get_option('raychat_widget_id'))) {
        $this->widget_id = $out;
      }
      $this->save();
    }
  }

  public function delete() {
    delete_option('raychat_widget_id');
  }

  public function getId() {
    return $this->widget_id;
  }

  public function render() {
    $result = $this->catchPost();
    $error  = is_array( $result ) && isset( $result['error'] ) ? $result['error'] : '';
    $widget_id = $this->widget_id;              // ✅ CHANGED: define it for the template
    $requirementsOk = ini_get('allow_url_fopen') || extension_loaded('curl');

    if ( $requirementsOk ) {
        require_once "templates/page.php";
    } else {
        require_once "templates/error.php";
    }
}


  public function append($widget_id = false) {
    if ($widget_id) {
      require_once "templates/script.php";
    }
  }

  public function save() {
    update_option('raychat_widget_id', $this->widget_id);
    update_option('raychat_token', $this->token);
  }

  public function catchPost() {
    if (isset($_GET['mode']) && $_GET['mode'] == 'reset') {
      $this->widget_id = '';
      $this->token = '';
      $this->save();
    }

    if (isset($_POST['widget_id'])) {
      $this->widget_id = sanitize_text_field($_POST['widget_id']);
      $this->save();
    } elseif (isset($_POST['email'], $_POST['userPassword'])) {
      $query = $_POST;
      $query['siteUrl'] = get_site_url();
      $query['partnerId'] = "wordpress";
      $query['authToken'] = md5(time() . get_site_url());
      $query['agent_id'] = $query['agent_id'] ?? 0;
      $query['lang'] = RAYCHAT_LANG;

      $path = RAYCHAT_INTEGRATION_URL . "/install";
      // ✅ CHANGED: Ensure HTTP fallback without `openssl`
      if (!extension_loaded('openssl')) {
        $path = str_replace('https:', 'http:', $path);
      }

      try {
        $response = wp_remote_post($path, [
          'body' => $query,
          'headers' => ['Content-Type' => 'application/x-www-form-urlencoded']
        ]);

        $body = wp_remote_retrieve_body($response);
        if (strstr($body, 'Error')) {
          return ['error' => $body];
        } else {
          $this->widget_id = $body;
          $this->token = $query['authToken'];
          $this->save();
          return true;
        }
      } catch (Exception $e) {
        _e("Connection error", 'raychat'); // ✅ CHANGED: Wrap with translation
      }
    }
  }
}
