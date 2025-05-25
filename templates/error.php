<?php

if (!defined('ABSPATH')) exit;

?>
<div class="wrap">
    <h1>
        <a href="https://raychat.io" target="_blank">
            <img src="<?php echo esc_url(RAYCHAT_PLUGIN_URL); // ✅ CHANGED ?>img/logo.png" alt="Raychat Logo" />
            <!-- ✅ CHANGED: Removed incorrect use of _e() inside src attribute; used plain filename instead -->
        </a>
    </h1>

    <b style="color:red;">
        <?php echo esc_html($error); // ✅ CHANGED: Escaped error output for safety ?>
    </b>

    <div class="gray_form">
        <?php
        echo wp_kses_post(sprintf(
            // ✅ CHANGED: Used printf-style string and escaped properly
            __('Unfortunately, your server configuration does not allow the plugin to connect to Raychat servers to create an account. Please go to <a target="_blank" href="%1$s">%1$s</a> and sign up. During the signup process you will be offered to download another WordPress module that does not require communication over the network.', 'raychat'),
            'https://admin.raychat.io/autoreg?lang=en'
        ));
        ?>
    </div>
</div>
