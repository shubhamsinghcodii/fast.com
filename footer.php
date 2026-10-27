<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<footer class="site-footer">
  <div class="footer-cta-wrap"><div class="container footer-cta"><div><span class="eyebrow eyebrow-light">PUT YOUR CONNECTION IN CONTEXT</span><h2>Know more than just the bars.</h2><p>Check your Android connection and make your next troubleshooting step a more informed one.</p></div><a class="button button-light" href="<?php echo esc_url( speedsignal_app_url() ); ?>" target="_blank" rel="noopener">Explore PingMaster Pro <span aria-hidden="true">↗</span></a></div></div>
  <div class="container footer-main">
    <div class="footer-brand"><a class="footer-brand-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">Internet Speed Test Free<span class="footer-brand-dot">.</span></a><p>Clear, practical explanations of internet speed, Wi-Fi, ping, latency and jitter—without the unnecessary jargon.</p></div>
    <div class="footer-links"><h3>Explore</h3><?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'footer-menu', 'fallback_cb' => false, 'depth' => 1 ) ); ?></div>
    <div class="footer-links"><h3>Popular guides</h3><ul><li><a href="<?php echo esc_url( speedsignal_page_url( 'speed-test-guide' ) ); ?>">Internet speed test guide</a></li><li><a href="<?php echo esc_url( speedsignal_page_url( 'download-speed' ) ); ?>">Download speed explained</a></li><li><a href="<?php echo esc_url( speedsignal_page_url( 'ping-latency' ) ); ?>">Ping and latency</a></li><li><a href="<?php echo esc_url( speedsignal_page_url( 'fix-slow-wifi' ) ); ?>">Fix slow Wi-Fi</a></li></ul></div>
  </div>
  <div class="container footer-bottom"><p>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. All rights reserved.</p><p>Speed-test results vary with your device, network, server and test conditions.</p><a href="<?php echo esc_url( speedsignal_page_url( 'privacy-site-notes' ) ); ?>">Privacy & site notes</a></div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
