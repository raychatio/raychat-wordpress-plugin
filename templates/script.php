<?php

if (!defined('ABSPATH')) exit;

?>

<?php if (get_option("version_id") === "version_2") { ?>

  <script type="text/javascript">
    // ✅ CHANGED: Escaped PHP output to prevent potential script injection
    window.RAYCHAT_TOKEN = "<?php echo esc_js($widget_id); // ✅ CHANGED ?>";

    (function() {
      var d = document;
      var s = d.createElement("script");
      s.src = "https://widget-react.raychat.io/install/widget.js";
      s.async = true;
      d.getElementsByTagName("head")[0].appendChild(s);
    })();
  </script>

<?php } else { ?>

  <script type="text/javascript">
    !function() {
      function t() {
        var t = document.createElement("script");
        t.type = "text/javascript";
        t.async = true;

        // ✅ CHANGED: Added better readability and `const` keyword in newer syntax would be better if not supporting legacy browsers
        var token = localStorage.getItem("rayToken");
        var srcBase = "https://app.raychat.io/scripts/js/";
        var widgetId = "<?php echo esc_js($widget_id); // ✅ CHANGED ?>";

        t.src = token
          ? srcBase + widgetId + "?rid=" + encodeURIComponent(token) + "&href=" + encodeURIComponent(window.location.href) // ✅ CHANGED: encoded href
          : srcBase + widgetId;

        var e = document.getElementsByTagName("script")[0];
        e.parentNode.insertBefore(t, e);
      }

      var e = document, a = window;
      // ✅ CHANGED: Slight formatting and readability
      if (e.readyState === "complete") {
        t();
      } else if (a.attachEvent) {
        a.attachEvent("onload", t);
      } else {
        a.addEventListener("load", t, false);
      }
    }();
  </script>

<?php } ?>
