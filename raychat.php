<?php
/**
 * Plugin Name:     Raychat
 * Plugin URI:      https://www.raychat.io
 * Description:     افزونه وردپرس گفتگوی آنلاین رایچت | با مشتریانتون صحبت کنید ، پشتیبانی کنید و از فروش خود لذت ببرید :)
 * Version:         2.2.1
 * Author:          Raychat
 * Author URI:      https://www.raychat.io
 * Text Domain:     raychat
 * Domain Path:     /languages/
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load translation files
load_plugin_textdomain('raychat', false, dirname(plugin_basename(__FILE__)) . '/languages');

$lang = get_bloginfo('language');
$raychat_addr = 'https://www.raychat.io';

define('RAYCHAT_LANG', substr($lang, 0, 2));
define('RAYCHAT_WIDGET_URL', 'raychat.io');
define('RAYCHAT_URL', $raychat_addr);
define('RAYCHAT_INTEGRATION_URL', RAYCHAT_URL . '/integration');
define('RAYCHAT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RAYCHAT_IMG_URL', plugin_dir_url(__FILE__) . 'img/');

register_activation_hook(__FILE__, 'raychatInstall');
register_deactivation_hook(__FILE__, 'raychatDelete');

function catalog_admin_menu_raychat()
{
    // Reload translations in admin
    load_plugin_textdomain('raychat', false, dirname(plugin_basename(__FILE__)) . '/languages');

    add_menu_page(
        esc_html__('رایچت', 'raychat'),
        esc_html__('رایچت', 'raychat'),
        'edit_pages',
        'raychat',
        'raychatPreferences',
        esc_url(RAYCHAT_IMG_URL . 'raychat.svg')
    );
}
add_action('admin_menu', 'catalog_admin_menu_raychat');

function raychat_options_validate($args)
{
    return $args;
}

add_action('admin_init', 'raychat_register_settings');
function raychat_register_settings()
{
    register_setting('raychat_token', 'raychat_token', 'raychat_options_validate');
    register_setting('raychat_widget_id', 'raychat_widget_id', 'raychat_options_validate');
}

add_action('admin_post_wp_save_token', 'wp_save_token_function_raychat');
add_action('admin_post_nopriv_wp_save_token', 'wp_save_token_function_raychat');

add_action('wp_footer', 'raychatAppend', 100000);

function raychatInstall()
{
    return raychat::getInstance()->install();
}

function raychatDelete()
{
    return raychat::getInstance()->delete();
}

function raychatAppend()
{
    raychat::getInstance()->append(raychat::getInstance()->getId());
}

function raychatPreferences()
{
    // Verify capability
    if (!current_user_can('edit_pages')) {
        wp_die(esc_html__('Unauthorized', 'raychat'));
    }

    if (isset($_POST['raychat_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['raychat_nonce'])), 'save_raychat_token')) {
        raychat::getInstance()->save();
    }

    // Reload translations
    load_plugin_textdomain('raychat', false, dirname(plugin_basename(__FILE__)) . '/languages');

    // Enqueue style
    $style_path = plugin_dir_path(__FILE__) . 'raychat.css';
    wp_register_style(
        'raychat_style',
        plugins_url('raychat.css', __FILE__),
        array(),
        file_exists($style_path) ? filemtime($style_path) : false
    );
    wp_enqueue_style('raychat_style');

    raychat::getInstance()->render();
}

function wp_save_token_function_raychat()
{
    // Verify nonce and referer
    if (empty($_POST['raychat_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['raychat_nonce'])), 'save_raychat_token')) {
        wp_die(esc_html__('Security check failed', 'raychat'));
    }

    $tokenError = null;
    $token = isset($_POST['token-id']) ? sanitize_text_field(wp_unslash($_POST['token-id'])) : '';
    $version_id = 'version_2';

    if ($token) {
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $token)) {
            update_option('raychat_widget_id', $token);
            update_option('version_id', $version_id);
            raychat::getInstance()->install();
        } else {
            $tokenError = esc_html__('توکن نامعتبر است.', 'raychat');
        }
    } else {
        $tokenError = esc_html__('توکن نمی‌تواند خالی باشد.', 'raychat');
    }

    set_transient('error_token_uuid', $tokenError);

    $redirect = wp_get_referer() ? wp_get_referer() : admin_url();
    wp_safe_redirect(esc_url_raw($redirect));
    exit();
}

class raychat
{
    protected static $instance, $lang;
    private $widget_id = '', $token = '', $version = '';

    private function __construct()
    {
        $this->token = get_option('raychat_token');
        $this->widget_id = get_option('raychat_widget_id');
        $this->version = get_option('version_id');
    }

    private function __clone()
    {
    }
    public function __wakeup()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        self::$lang = (isset($_GET['lang']) && 'ru' === $_GET['lang']) ? 'ru' : 'en';
        return self::$instance;
    }

    public function save()
    {
        if (isset($_POST['token-id'])) {
            $this->widget_id = sanitize_text_field(wp_unslash($_POST['token-id']));
            update_option('raychat_widget_id', $this->widget_id);
        }
    }

    public function install()
    {
        $file = plugin_dir_path(__FILE__) . 'id';
        if (file_exists($file)) {
            $uuid = file_get_contents($file);
            update_option('raychat_widget_id', $uuid);
            wp_delete_file($file);
            $this->widget_id = $uuid;
        }
    }

    public function delete()
    {
        delete_option('raychat_widget_id');
    }

    public function getId()
    {
        return $this->widget_id;
    }

    public function render()
    {
        $result = $this->catchPost();
        $error = is_array($result) && isset($result['error']) ? $result['error'] : '';
        $widget_id = $this->widget_id;
        $requirementsOk = ini_get('allow_url_fopen') || extension_loaded('curl');

        if ($requirementsOk) {
            include plugin_dir_path(__FILE__) . 'templates/page.php';
        } else {
            include plugin_dir_path(__FILE__) . 'templates/error.php';
        }
    }

    public function append($widget_id = false)
    {
        if ($widget_id) {
            include plugin_dir_path(__FILE__) . 'templates/script.php';
        }
    }

    public function catchPost()
    {
        if (isset($_POST['raychat_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['raychat_nonce'])), 'save_raychat_token')) {
            $this->save();
            return true;
        }

        if (isset($_POST['email'], $_POST['userPassword'])) {
            $query = array_map('sanitize_text_field', wp_unslash($_POST));
            $query['siteUrl'] = esc_url_raw(get_site_url());
            $query['partnerId'] = 'wordpress';
            $query['authToken'] = md5(time() . get_site_url());
            $query['agent_id'] = isset($query['agent_id']) ? absint($query['agent_id']) : 0;
            $query['lang'] = RAYCHAT_LANG;

            $path = RAYCHAT_INTEGRATION_URL . '/install';
            if (!extension_loaded('openssl')) {
                $path = str_replace('https:', 'http:', $path);
            }

            try {
                $response = wp_remote_post(esc_url_raw($path), [
                    'body' => $query,
                    'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                ]);

                if (is_wp_error($response)) {
                    return ['error' => $response->get_error_message()];
                }

                $body = wp_remote_retrieve_body($response);
                if (false !== strpos($body, 'Error')) {
                    return ['error' => sanitize_text_field($body)];
                }

                $this->widget_id = sanitize_text_field($body);
                update_option('raychat_widget_id', $this->widget_id);
                return true;
            } catch (Exception $e) {
                return ['error' => esc_html__('Connection error', 'raychat')];
            }
        }
    }
}