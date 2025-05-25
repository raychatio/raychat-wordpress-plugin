<?php

if (!defined('ABSPATH')) exit;

?>

<div class="wrap">
    <h1>
        <a href="https://raychat.io" target="_blank">
            <img src="<?php echo RAYCHAT_IMG_URL ?>raychat-logo.svg" />
        </a>
    </h1>
    
    <!-- ✅ CHANGED: Escaped error output -->
    <b style="color:red;"><?php echo esc_html($error); // ✅ CHANGED ?></b>

    <?php if (!$widget_id) { ?>
        <?php if ($error = get_transient('error_token_uuid')) : ?>
            <!-- ✅ CHANGED: Use 'notice notice-error' instead of raw div.error -->
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($error); // ✅ CHANGED ?></p>
            </div>
        <?php endif; ?>

        <div class="gray_form">
            <h3>تبریک می‌گوییم! شما برای نصب ابزارک رایچت در سایتتان نصف راه را پیموده‌اید.</h3>
            <p>
                اکنون به <a href="https://raychat.io/dashboard" target="_blank">پنل مدیریت رایچت</a>
                وارد شوید؛ سپس تب نصب و راه‌اندازی را باز کنید و با انتخاب وبسایت مورد نظر، از پایین صفحه توکن وبسایت را کپی کرده و در کادر پایین قرار دهید.
            </p>

            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="form-token">
                <input type="hidden" value="wp_save_token" name="action">

                <div>
                    <label for="raychat_id">توکن: </label>
                    <input type="text" class="" id="raychat_id" name="token-id" required /> <!-- ✅ CHANGED: Added required -->

                    <!-- Optional version selector commented out for now -->
                    <!--
                    <p>ورژن مورد نظرتون رو انتخاب کنید</p>
                    <input type="radio" id="html" name="version_id" value="version_1">
                    <label for="html">ورژن ۱</label><br><br>
                    <input type="radio" id="css" name="version_id" value="version_2">
                    <label for="css">ورژن ۲</label><br><br>
                    -->

                    <input type="submit" name="submit" class="button button-primary" value="ذخیره">
                </div>

                <br><br>
                <hr>
                <p>
                    چنانچه تا کنون در رایچت عضو نشده‌اید، می‌توانید از طریق لینک زیر در رایچت عضو شوید و به صورت نامحدود
                    با کاربران وبسایتتان مکالمه کنید و فروش خود را چند برابر کنید.
                </p>
                <a class="button button-primary" href="https://raychat.io/signup" target="_blank">عضویت رایگان</a>
                <a class="button button-primary" href="https://blog.raychat.io/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%B5%D9%81%D8%B1-%D8%AA%D8%A7-%D8%B5%D8%AF-%D8%B1%D8%A7%DB%8C%DA%86%D8%AA/?utm_medium=plugins&utm_source=wordpress_plugin" target="_blank">آموزش شروع کار با رایچت</a>
                <br><br>
                <hr>
                <p style="font-size: 12px">
                    رایچت، ابزار گفتگوی آنلاین |
                    <a href="https://raychat.io" target="_blank">کلیک کنید</a>
                </p>
            </form>
        </div>
    <?php } else { ?>
        <div class="notice notice-success is-dismissible"> <!-- ✅ CHANGED: Use WordPress success notice style -->
            <?php _e('تبریک میگوییم ابزارک رایچت در سایت شما با موفقیت نصب شد. برای فعال سازی ابزارک فقط کافیست یک بار دیگر سایت خود را بارگذاری کنید.', 'raychat'); ?>
        </div>
        <div class="gray_form">
            <h3>1. <?php _e('ورود به اپلیکیشن تحت وب', 'raychat'); ?></h3>
            <p><?php _e('شما می‌‌توانید با استفاده از اپلیکیشن تحت وب با کاربران گفتگو کنید', 'raychat'); ?></p>
            <a class="button button-primary" href="https://webapp.raychat.io" target="_blank"><?php _e('ورود به اپلیکیشن', 'raychat'); ?></a>

            <br><br>

            <h3>2. <?php _e('شخصی سازی ابزارک یا مدیریت اپراتورها از طریق پنل مدیریت', 'raychat'); ?></h3>
            <p><?php _e('بعد از نصب و فعال‌سازی ابزارک برای مدیریت اپراتورها و شخصی‌سازی ابزارک می‌توانید از طریق پنل مدیریت اقدام کنید', 'raychat'); ?></p>
            <a class="button button-primary" href="https://raychat.io/dashboard" target="_blank"><?php _e('ورود به پنل مدیریت', 'raychat'); ?></a>
        </div>
    <?php } ?>
</div>
