<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<div class="wrap">
    <h1>
        <a href="https://raychat.io" target="_blank">
            <?php // phpcs:ignore WordPress.PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
            <img src="<?php echo esc_url( RAYCHAT_IMG_URL . 'raychat-logo.svg' ); ?>" alt="<?php esc_attr_e( 'Raychat Logo', 'raychat' ); ?>" />
        </a>
    </h1>
    
    <!-- Escaped error output -->
    <b style="color:red;"><?php echo esc_html( $error ); ?></b>

    <?php if ( ! $widget_id ) : ?>
        <?php if ( $error = get_transient( 'error_token_uuid' ) ) : ?>
            <!-- Use WordPress error notice style -->
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html( $error ); ?></p>
            </div>
        <?php endif; ?>

        <div class="gray_form">
            <h3><?php esc_html_e( 'تبریک می‌گوییم! شما برای نصب ابزارک رایچت در سایتتان نصف راه را پیموده‌اید.', 'raychat' ); ?></h3>
            <p>
                <?php
                /* translators: %1$s: Raychat dashboard URL placeholder */
                printf(
                    /* translators: %1$s: URL to Raychat dashboard */
                    wp_kses_post( __( 'اکنون به <a href="%1$s" target="_blank">پنل مدیریت رایچت</a> وارد شوید؛ سپس تب نصب و راه‌اندازی را باز کنید و با انتخاب وبسایت مورد نظر، از پایین صفحه توکن وبسایت را کپی کرده و در کادر پایین قرار دهید.', 'raychat' ) ),
                    esc_url( 'https://raychat.io/dashboard' )
                );
                ?>
            </p>

            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="form-token">
                <?php wp_nonce_field( 'save_raychat_token', 'raychat_nonce' ); ?>
                <input type="hidden" name="action" value="wp_save_token" />

                <div>
                    <label for="raychat_id"><?php esc_html_e( 'توکن:', 'raychat' ); ?></label>
                    <input type="text" id="raychat_id" name="token-id" class="regular-text" required />

                    <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e( 'ذخیره', 'raychat' ); ?>" />
                </div>

                <hr />
                <p><?php esc_html_e( 'چنانچه تا کنون در رایچت عضو نشده‌اید، می‌توانید از طریق لینک زیر در رایچت عضو شوید و به صورت نامحدود با کاربران وبسایتتان مکالمه کنید و فروش خود را چند برابر کنید.', 'raychat' ); ?></p>
                <a class="button button-primary" href="<?php echo esc_url( 'https://raychat.io/signup' ); ?>" target="_blank"><?php esc_html_e( 'عضویت رایگان', 'raychat' ); ?></a>
                <a class="button button-primary" href="<?php echo esc_url( 'https://blog.raychat.io/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%B5%D9%81%D8%B1-%D8%AA%D8%A7-%D8%B5%D8%AF-%D8%B1%D8%A7%DB%8C%DA%86%D8%AA/?utm_medium=plugins&utm_source=wordpress_plugin' ); ?>" target="_blank"><?php esc_html_e( 'آموزش شروع کار با رایچت', 'raychat' ); ?></a>

                <hr />
                <p style="font-size:12px;">
                    <?php esc_html_e( 'رایچت، ابزار گفتگوی آنلاین |', 'raychat' ); ?>
                    <a href="<?php echo esc_url( 'https://raychat.io' ); ?>" target="_blank"><?php esc_html_e( 'کلیک کنید', 'raychat' ); ?></a>
                </p>
            </form>
        </div>
    <?php else : ?>
        <div class="notice notice-success is-dismissible">
            <?php esc_html_e( 'تبریک میگوییم ابزارک رایچت در سایت شما با موفقیت نصب شد. برای فعال سازی ابزارک فقط کافیست یک بار دیگر سایت خود را بارگذاری کنید.', 'raychat' ); ?>
        </div>
        <div class="gray_form">
            <h3><?php esc_html_e( '1. ورود به اپلیکیشن تحت وب', 'raychat' ); ?></h3>
            <p><?php esc_html_e( 'شما می‌‌توانید با استفاده از اپلیکیشن تحت وب با کاربران گفتگو کنید', 'raychat' ); ?></p>
            <a class="button button-primary" href="<?php echo esc_url( 'https://webapp.raychat.io' ); ?>" target="_blank"><?php esc_html_e( 'ورود به اپلیکیشن', 'raychat' ); ?></a>

            <h3><?php esc_html_e( '2. شخصی سازی ابزارک یا مدیریت اپراتورها از طریق پنل مدیریت', 'raychat' ); ?></h3>
            <p><?php esc_html_e( 'بعد از نصب و فعال‌سازی ابزارک برای مدیریت اپراتورها و شخصی‌سازی ابزارک می‌توانید از طریق پنل مدیریت اقدام کنید', 'raychat' ); ?></p>
            <a class="button button-primary" href="<?php echo esc_url( 'https://raychat.io/dashboard' ); ?>" target="_blank"><?php esc_html_e( 'ورود به پنل مدیریت', 'raychat' ); ?></a>
        </div>
    <?php endif; ?>
</div>
