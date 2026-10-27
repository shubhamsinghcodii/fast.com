<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'speedsignal' ); ?></a>
<div class="announcement-bar"><div class="container announcement-inner"><span class="announcement-dot" aria-hidden="true"></span><span>Practical guides for faster, more reliable everyday connections</span><a href="<?php echo esc_url( speedsignal_page_url( 'speed-test-guide' ) ); ?>">Start with the basics <span aria-hidden="true">→</span></a></div></div>
<header class="site-header">
  <div class="container header-inner">
    <div class="site-branding">
      <a class="brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> home">
        <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
          <span class="brand-mark" aria-hidden="true"><svg viewBox="0 0 40 40" role="img"><path d="M5 24.5a15 15 0 0 1 30 0" fill="none" stroke="currentColor" stroke-width="3.3" stroke-linecap="round"/><path d="M10 24.5a10 10 0 0 1 20 0" fill="none" stroke="currentColor" stroke-width="3.3" stroke-linecap="round"/><path d="M15.5 24.5a4.5 4.5 0 0 1 9 0" fill="none" stroke="currentColor" stroke-width="3.3" stroke-linecap="round"/><circle cx="20" cy="30.5" r="3" fill="currentColor"/></svg></span>
          <span class="brand-copy"><span class="brand-name">Internet Speed Test <em>Free</em></span><span class="brand-tagline">Understand your connection</span></span>
        <?php endif; ?>
      </a>
    </div>
    <button class="menu-toggle" type="button" aria-controls="primary-navigation" aria-expanded="false"><span class="menu-toggle-lines" aria-hidden="true"></span><span class="screen-reader-text">Open menu</span></button>
    <nav class="primary-navigation" id="primary-navigation" aria-label="Primary navigation">
      <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'primary-menu', 'fallback_cb' => false, 'depth' => 1 ) ); ?>
    </nav>
    <a class="header-download" href="<?php echo esc_url( speedsignal_app_url() ); ?>" target="_blank" rel="noopener">Get the app <span aria-hidden="true">↗</span></a>
  </div>
</header>
