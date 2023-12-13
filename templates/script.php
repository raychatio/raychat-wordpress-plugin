<?php

if (!defined('ABSPATH')) exit;

?>

<?php if (get_option("version_id") === "version_2") { ?>

  <script type="text/javascript">
    window.RAYCHAT_TOKEN = "<?php echo $widget_id; ?>";
    (function() {
      d = document;
      s = d.createElement("script");
      s.src = "https://widget-react.raychat.io/install/widget.js";
      s.async = 1;
      d.getElementsByTagName("head")[0].appendChild(s);
    })();
  </script>

<?php } else { ?>

  <script type="text/javascript">
    ! function() {
      function t() {
        var t = document.createElement("script");
        t.type = "text/javascript", t.async = !0, localStorage.getItem("rayToken") ? t.src = "https://app.raychat.io/scripts/js/" + o + "?rid=" + localStorage.getItem("rayToken") + "&href=" + window.location.href : t.src = "https://app.raychat.io/scripts/js/" + o;
        var e = document.getElementsByTagName("script")[0];
        e.parentNode.insertBefore(t, e)
      }
      var e = document,
        a = window,
        o = "<?php echo $widget_id; ?>";
      "complete" == e.readyState ? t() : a.attachEvent ? a.attachEvent("onload", t) : a.addEventListener("load", t, !1)
    }();
  </script>

<?php } ?>