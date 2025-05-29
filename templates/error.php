<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap">
    <h1>
        <a href="https://raychat.io" target="_blank">
            <?php // phpcs:ignore WordPress.PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
            <img src="<?php echo esc_url( RAYCHAT_PLUGIN_URL . 'img/logo.png' ); ?>" alt="<?php esc_attr_e( 'Raychat Logo', 'raychat' ); ?>" />
        </a>
    </h1>

    <b style="color:red;">
        <?php echo esc_html( $error ); ?>
    </b>

    <div class="gray_form">
        <?php
        /* translators: %1$s is the URL to the Raychat signup/autoregistration page. */
        echo wp_kses_post( sprintf(
            /* translators: %1$s is the URL to the Raychat signup/autoregistration page. */
            __( 'Unfortunately, your server configuration does not allow the plugin to connect to Raychat servers to create an account. Please go to <a target="_blank" href="%1$s">%1$s</a> and sign up. During the signup process you will be offered to download another WordPress module that does not require communication over the network.', 'raychat' ),
            esc_url( 'https://admin.raychat.io/autoreg?lang=en' )
        ) );
        ?>
    </div>
</div>
