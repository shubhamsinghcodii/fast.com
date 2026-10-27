<?php
/**
 * SpeedSignal — Internet Speed Guides
 * A self-contained classic WordPress theme. Content is created on activation,
 * without overwriting pages or posts that already exist.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function speedsignal_app_url() {
    return 'https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro';
}

function speedsignal_setup() {
    load_theme_textdomain( 'speedsignal', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-logo', array( 'height' => 72, 'width' => 240, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    register_nav_menus( array(
        'primary' => __( 'Primary navigation', 'speedsignal' ),
        'footer'  => __( 'Footer navigation', 'speedsignal' ),
    ) );
}
add_action( 'after_setup_theme', 'speedsignal_setup' );

function speedsignal_assets() {
    $theme = wp_get_theme();
    wp_enqueue_style( 'speedsignal-theme', get_stylesheet_uri(), array(), $theme->get( 'Version' ) );
    wp_enqueue_style( 'speedsignal-main', get_template_directory_uri() . '/assets/css/theme.css', array( 'speedsignal-theme' ), $theme->get( 'Version' ) );
    wp_enqueue_script( 'speedsignal-main', get_template_directory_uri() . '/assets/js/theme.js', array(), $theme->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'speedsignal_assets' );

function speedsignal_app_button_shortcode( $atts = array(), $content = null ) {
    $atts = shortcode_atts( array( 'label' => 'Get PingMaster Pro', 'class' => '' ), $atts, 'pingmaster_app_button' );
    return '<a class="button button-primary ' . esc_attr( $atts['class'] ) . '" href="' . esc_url( speedsignal_app_url() ) . '" target="_blank" rel="noopener">' . esc_html( $atts['label'] ) . ' <span aria-hidden="true">↗</span></a>';
}
add_shortcode( 'pingmaster_app_button', 'speedsignal_app_button_shortcode' );

/** Create a page only when a page with that path does not already exist. */
function speedsignal_seed_page( $page ) {
    $existing = get_page_by_path( $page['slug'], OBJECT, 'page' );
    if ( $existing ) {
        $id = (int) $existing->ID;
    } else {
        $id = wp_insert_post( array(
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $page['title'],
            'post_name'    => $page['slug'],
            'post_content' => $page['content'],
            'post_excerpt' => isset( $page['description'] ) ? $page['description'] : '',
            'menu_order'   => isset( $page['menu_order'] ) ? (int) $page['menu_order'] : 0,
        ), true );
        if ( is_wp_error( $id ) ) { return 0; }
    }
    if ( ! empty( $page['description'] ) ) {
        update_post_meta( $id, '_speedsignal_meta_description', sanitize_text_field( $page['description'] ) );
    }
    return (int) $id;
}

function speedsignal_seed_post( $post ) {
    $existing = get_page_by_path( $post['slug'], OBJECT, 'post' );
    if ( $existing ) { return (int) $existing->ID; }
    $id = wp_insert_post( array(
        'post_type'    => 'post',
        'post_status'  => 'publish',
        'post_title'   => $post['title'],
        'post_name'    => $post['slug'],
        'post_content' => $post['content'],
        'post_excerpt' => $post['description'],
    ), true );
    if ( is_wp_error( $id ) ) { return 0; }
    update_post_meta( $id, '_speedsignal_meta_description', sanitize_text_field( $post['description'] ) );
    return (int) $id;
}

function speedsignal_get_seed_content() {
    $pages = array();
    $pages[] = array(
        'title' => 'Home', 'slug' => 'home', 'menu_order' => 0,
        'description' => 'Learn how to check internet speed, understand download, upload, ping and jitter, and troubleshoot Wi-Fi with practical guides and PingMaster Pro for Android.',
        'content' => <<<'HTML'
<p class="lead">An internet connection is more than a single Mbps number. Learn what your speed test results mean, how latency and jitter affect everyday use, and what to check when Wi-Fi feels slower than expected.</p>
<h2>What does an internet speed test measure?</h2>
<p>A speed test estimates how your connection performs at a particular moment. <strong>Download speed</strong> describes how quickly data reaches your device; <strong>upload speed</strong> describes how quickly your device can send data; <strong>ping</strong> measures response time; and <strong>jitter</strong> describes variation in that response time. These measurements answer different questions, so a useful check looks at them together instead of treating one number as the complete story.</p>
<p>Results can change with your router, Wi-Fi signal, device, service plan, network congestion, test server and the time of day. Run a few tests under similar conditions before deciding that something is wrong. For a clearer picture, compare a test close to your router with one from the room where you usually work or stream.</p>
<h2>Check your connection on Android</h2>
<p>For an on-device check, use <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test on Google Play</a>. The app listing describes download and upload testing, ping and latency checks, jitter, Wi-Fi and mobile network testing, network information, saved test history and live network-speed indicators. The measurements you see reflect the connection and conditions at the time you run each test.</p>
<h2>Start with the result that matches your problem</h2>
<ul>
<li><strong>Downloads take a long time:</strong> focus on download throughput, then compare Wi-Fi with mobile data if possible.</li>
<li><strong>Files or videos take a long time to send:</strong> look at upload speed and check whether another device is uploading heavily.</li>
<li><strong>Games or calls feel delayed:</strong> pay attention to ping, jitter and whether the connection is stable over time.</li>
<li><strong>Speed changes from room to room:</strong> investigate Wi-Fi signal, router placement, walls and interference.</li>
</ul>
<h2>Make your speed test more useful</h2>
<p>Pause large downloads and cloud backups, stand near the router for a baseline, and repeat the test two or three times. If you are comparing locations, keep the same device and test method. Try a wired connection when available to help separate a broadband issue from a Wi-Fi issue. Record the date, location, connection type and results so you can compare like with like.</p>
<p>Explore our guides to <a href="/speed-test-guide/">running a reliable speed test</a>, <a href="/download-speed/">understanding download speed</a>, <a href="/ping-latency/">reading ping and latency</a>, and <a href="/fix-slow-wifi/">troubleshooting slow Wi-Fi</a>. The aim is not to chase a perfect score; it is to understand whether your connection is suitable for what you are trying to do and identify the next practical step.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Internet Speed Test Guide', 'slug' => 'speed-test-guide', 'menu_order' => 1,
        'description' => 'Learn how to run an internet speed test accurately, prepare your device, compare Wi-Fi and mobile data, and interpret the results.',
        'content' => <<<'HTML'
<p class="lead">An internet speed test gives you a snapshot of network performance. A careful test can help you compare locations, spot inconsistent Wi-Fi and collect useful evidence before contacting your internet provider.</p>
<h2>Before you start a speed test</h2>
<p>Use a device that is connected to the network you want to check. Pause large downloads, game updates, cloud backups and streaming where practical, because competing traffic can affect the result. If several people are actively using the connection, note that rather than assuming the test represents an idle network.</p>
<p>For a Wi-Fi baseline, stand in the same room as the router and keep the device in the same position for each run. If your device supports both 2.4 GHz and 5 GHz Wi-Fi, make a note of which band you are using. A wired Ethernet test can help you separate the broadband connection from wireless limitations.</p>
<h2>How to run a repeatable test</h2>
<ol>
<li>Choose the network: home Wi-Fi, office Wi-Fi or mobile data.</li>
<li>Close or pause tasks that use a lot of bandwidth, if convenient.</li>
<li>Run a test and record download speed, upload speed, ping and jitter when those measurements are available.</li>
<li>Repeat two or three times, leaving a short gap between runs.</li>
<li>Compare the results with tests taken under similar conditions, not with a number from a different device, location or time.</li>
</ol>
<p>On Android, <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a> provides download and upload speed checks, ping and latency measurements, jitter information, Wi-Fi and mobile network testing, network details and test history, according to its Google Play listing.</p>
<h2>What the results mean</h2>
<h3>Download speed</h3><p>Download speed is the rate at which data arrives at your device. It matters for loading websites, downloading files, installing apps and streaming media. See the full <a href="/download-speed/">download speed guide</a>.</p>
<h3>Upload speed</h3><p>Upload speed affects sending large files, cloud backups, live streaming and sharing your camera or screen in a call. Your upload rate may be different from your download rate. Read <a href="/upload-speed/">how upload speed works</a>.</p>
<h3>Ping and jitter</h3><p>Ping is a response-time measurement, usually expressed in milliseconds. Jitter describes changes in response time between packets. These can matter for online games and live voice or video calls even when download speed looks healthy. Learn more in our guide to <a href="/ping-latency/">ping and latency</a> and <a href="/jitter/">jitter</a>.</p>
<h2>Why can my test results change?</h2>
<p>Speed tests use a route between your device and a test server. Wireless signal strength, router performance, the number of active devices, provider congestion, server load and the route taken by traffic can all influence the measurement. A single result should not be treated as a guaranteed speed for every app or website.</p>
<p>If the results are consistently lower than expected, compare Wi-Fi with Ethernet or mobile data, restart the router when appropriate, check the service plan and look for provider outage information. Keep notes of repeated tests and their conditions. This gives you a clearer basis for deciding whether the issue is local to your home network or needs your provider's help.</p>
<div class="inline-app-cta"><strong>Keep your tests together</strong><p>Use PingMaster Pro to check connection performance and review previous test results on your Android device.</p><a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">View PingMaster Pro on Google Play ↗</a></div>
HTML
    );
    $pages[] = array(
        'title' => 'Download Speed Explained', 'slug' => 'download-speed', 'menu_order' => 2,
        'description' => 'Understand internet download speed in Mbps, what affects downloads and streaming, and how to troubleshoot consistently slow results.',
        'content' => <<<'HTML'
<p class="lead">Download speed is the rate at which data travels from the internet to your device. It influences how quickly web pages load, files download, apps update and video streams can receive data.</p>
<h2>What does Mbps mean?</h2>
<p>Download speeds are commonly shown in megabits per second (Mbps). A megabit is not the same as a megabyte: one byte contains eight bits. As a simplified theoretical conversion, 80 Mbps corresponds to about 10 megabytes per second before protocol overhead and other real-world factors. A file's download progress may therefore look slower than the Mbps figure suggests.</p>
<p>Actual file transfer rates can be lower than a speed test result because the remote server, the route to it, Wi-Fi conditions, device storage and other traffic also matter. A speed test is a useful comparison point, not a promise that every download will run at exactly that rate.</p>
<h2>How much download speed do you need?</h2>
<p>There is no single speed that suits everyone. Requirements depend on how many people and devices are active, the quality of video being streamed, whether large files are downloaded and whether work calls or games are happening at the same time. A single HD stream is different from several high-resolution streams plus cloud backups and downloads.</p>
<p>Rather than relying on one universal number, list your household's actual tasks. Test at the time those tasks normally happen, then check whether slowdowns line up with heavy use. If a connection performs well when idle but struggles when multiple devices are active, the issue may be available bandwidth or network congestion.</p>
<h2>How to troubleshoot slow download speed</h2>
<ol>
<li>Run two or three tests and note the time, location and device.</li>
<li>Move near the router to establish whether Wi-Fi coverage is part of the problem.</li>
<li>Temporarily pause large updates and backups on other devices.</li>
<li>Compare Wi-Fi with Ethernet, if available, or with mobile data as a separate connection.</li>
<li>Restart networking equipment according to the manufacturer's guidance and install available firmware updates.</li>
<li>Check your provider's service status and plan details if slow results persist.</li>
</ol>
<p>Do not assume a low download result automatically means your provider is at fault. A weak wireless signal, an older router, interference, a busy device or the website serving the file can all limit the observed rate. If wired tests remain consistently low across different times, save the results and contact your provider.</p>
<h2>Track the result over time</h2>
<p>For Android, <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a> offers download and upload testing and a test history so you can compare previous measurements. Run tests in similar conditions to make those comparisons meaningful.</p>
<p>For related topics, read our <a href="/speed-test-guide/">internet speed test guide</a>, <a href="/upload-speed/">upload speed explainer</a> and <a href="/fix-slow-wifi/">slow Wi-Fi troubleshooting checklist</a>.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Upload Speed Explained', 'slug' => 'upload-speed', 'menu_order' => 3,
        'description' => 'Learn what upload speed measures, how it affects video calls and file sharing, and practical ways to diagnose slow uploads.',
        'content' => <<<'HTML'
<p class="lead">Upload speed measures how quickly your device can send data to the internet. It can matter just as much as download speed when you work remotely, share files, make video calls or back up photos.</p>
<h2>When does upload speed matter?</h2>
<p>Every video call sends audio and video from your camera and microphone. Upload capacity can also be used when you send attachments, sync files to cloud storage, livestream, share your screen or publish content. If upload bandwidth is saturated, other people in the home may notice slower browsing or increased delay even if download speed is usually high.</p>
<h2>Why is upload speed often lower than download speed?</h2>
<p>Some internet plans are designed with different download and upload rates. The ratio depends on the technology and plan offered by your provider. Wi-Fi quality, router limits, network congestion and the remote service can also affect an upload. Compare the result against the upload rate promised by your specific plan rather than assuming all connections should be symmetrical.</p>
<h2>How to check slow upload speed</h2>
<ol>
<li>Pause large cloud backups, file syncs and outgoing uploads on your devices.</li>
<li>Run several tests using the same device and network.</li>
<li>Test near your router and compare with a wired connection if you can.</li>
<li>Check whether one device is uploading continuously or running a scheduled backup.</li>
<li>Try again at another time to see whether the problem appears during busy periods.</li>
</ol>
<p>For a call that freezes or drops, do not focus on upload Mbps alone. Latency, jitter, Wi-Fi packet loss and the call service's own conditions may also be relevant. If upload throughput looks normal but the call remains unstable, investigate these factors too.</p>
<h2>Keep a useful record</h2>
<p>Note the network type, approximate location, time and whether other devices were active. Repeat the same process after making one change, such as moving closer to the router. Changing several things at once makes it difficult to tell which action helped.</p>
<p>On Android, the <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test app</a> includes download and upload checks as described on its Google Play listing. You can also review the site's related guides on <a href="/ping-latency/">ping and latency</a>, <a href="/jitter/">jitter</a> and <a href="/speed-test-guide/">running repeatable speed tests</a>.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Ping and Latency Explained', 'slug' => 'ping-latency', 'menu_order' => 4,
        'description' => 'Learn what ping and latency mean, how response time affects games and calls, and how to check for consistently high latency.',
        'content' => <<<'HTML'
<p class="lead">Ping and latency describe how quickly a network responds. Download speed is about how much data can arrive over time; latency is about the delay before data makes a round trip.</p>
<h2>What is ping?</h2>
<p>Ping is commonly reported in milliseconds (ms). A test sends a small packet to a destination and measures the time taken for a response. The exact route, server and test method affect the result, so ping to one server is not necessarily the same as ping to a game server, video-call service or website.</p>
<p>Lower latency generally means a quicker response, which can be especially noticeable in online games, remote desktops, voice calls and other interactive tasks. A connection can have high download bandwidth and still feel sluggish when latency is high or varies unpredictably.</p>
<h2>What causes high latency?</h2>
<ul>
<li><strong>Wi-Fi interference or weak signal:</strong> retransmissions and contention can delay traffic.</li>
<li><strong>Network congestion:</strong> busy household devices or provider networks can add delay.</li>
<li><strong>Longer routes:</strong> a distant server usually takes longer to reach than a nearby one.</li>
<li><strong>Router or device load:</strong> equipment under heavy load may respond more slowly.</li>
<li><strong>Background transfers:</strong> large uploads or downloads can create queueing delay on some connections.</li>
</ul>
<h2>How to check ping fairly</h2>
<p>Repeat the measurement with the same test method and compare like with like. Note whether you are on Wi-Fi or mobile data, how far you are from the router and whether another device is using the connection heavily. Try a wired connection if available. If ping is high only to one service, the destination or route may be part of the explanation.</p>
<h2>Ping, jitter and packet loss are different</h2>
<p>Ping is a response-time measurement. <a href="/jitter/">Jitter</a> is the variation in delay between measurements or packets. Packet loss means that some packets do not reach their destination or their response is not received. Each can affect live applications differently. A low ping in one test does not prove that a network is stable at all times.</p>
<p>PingMaster Pro: Speed Test lists ping and latency checks among its features, together with download and upload tests, jitter information and network details. <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">View the app on Google Play</a> to check the current listing.</p>
<h2>Practical ways to reduce latency</h2>
<p>Move closer to the router, use Ethernet for stationary devices when practical, pause heavy uploads, reduce competing traffic and keep router firmware updated. Choose a game or call server geographically appropriate to you when the service allows it. If latency remains consistently high across different devices and connection types, save repeated observations and ask your ISP to investigate the route or line.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Jitter Explained', 'slug' => 'jitter', 'menu_order' => 5,
        'description' => 'Understand network jitter, why variable delay can disrupt calls and gaming, and how to investigate an unstable connection.',
        'content' => <<<'HTML'
<p class="lead">Jitter is variation in packet delay. A stable network delivers packets with relatively predictable timing; a connection with changing delay can sound choppy or make interactive applications feel inconsistent.</p>
<h2>Why jitter matters</h2>
<p>Real-time services, including voice calls, video meetings and online games, often need data to arrive at a predictable pace. These services may buffer a small amount of data to smooth timing differences. If the delay changes too much, the buffer may not be enough and users can experience gaps, freezes or uneven playback.</p>
<p>Jitter is not the same as download speed. A connection can have plenty of bandwidth and still exhibit variable response time. It is also different from average ping: two networks may report a similar average response time while one has much larger swings between readings.</p>
<h2>Common causes of jitter</h2>
<ul>
<li>Weak or congested Wi-Fi, especially in a room far from the access point.</li>
<li>Heavy network activity from uploads, cloud backups or multiple streams.</li>
<li>Congestion or routing changes beyond the home network.</li>
<li>Busy routers, network equipment or client devices.</li>
<li>Wireless interference from nearby networks or other devices.</li>
</ul>
<h2>How to investigate a jitter problem</h2>
<p>Repeat a test and note whether the issue occurs at a particular time or location. Move near the router, pause background transfers and test again. Compare with Ethernet if possible. If jitter improves dramatically near the router, wireless coverage or interference may be involved. If results remain unstable on a wired connection, check provider status and keep a record for support.</p>
<p>For gaming or calls, also check the actual service where the problem occurs. A general internet test cannot fully represent every route to every server. Use its results as one clue, not the only diagnosis.</p>
<h2>Use the measurements together</h2>
<p>Look at jitter alongside <a href="/ping-latency/">ping and latency</a>, download and upload performance, and whether any packets are being lost. Ping tells you about response time; jitter adds context about consistency. Together they give a more useful picture of an interactive connection than download speed alone.</p>
<p><a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test on Google Play</a> lists jitter information as one of its network-testing features. The app can be used as one part of a repeatable check alongside your own observations of call or gaming quality.</p>
HTML
    );
    $pages[] = array(
        'title' => 'How to Fix Slow Wi-Fi', 'slug' => 'fix-slow-wifi', 'menu_order' => 6,
        'description' => 'Troubleshoot slow Wi-Fi step by step: router placement, signal strength, busy devices, Wi-Fi bands and repeatable speed tests.',
        'content' => <<<'HTML'
<p class="lead">Slow Wi-Fi does not always mean that your broadband plan is slow. The wireless link between your device and router can be affected by distance, walls, interference, router load and the number of connected devices.</p>
<h2>1. Establish a baseline near the router</h2>
<p>Run a speed test while standing close to the router, then repeat it where you usually notice slowdowns. Keep the same device and testing method. If the result is much better near the router, coverage is a likely factor. If it is poor in both places, investigate the broadband line, router or wider network too.</p>
<h2>2. Improve router placement</h2>
<p>Place the router in an open, central position when possible, away from enclosed cabinets and large obstructions. Avoid hiding it behind a television or placing it on the floor. Walls, floors, metal objects and some building materials weaken radio signals. If the home has several floors or distant rooms, a properly placed access point or mesh system may provide better coverage than moving the router a few centimetres.</p>
<h2>3. Check the Wi-Fi band</h2>
<p>Many routers offer 2.4 GHz and 5 GHz networks. In general, 2.4 GHz can reach farther and pass through obstacles more effectively, while 5 GHz can provide higher throughput at shorter distances and in suitable conditions. The best choice depends on the router, device, interference and layout. If your router uses one network name for both bands, it may choose a band automatically.</p>
<h2>4. Find competing network activity</h2>
<p>Game downloads, operating-system updates, cloud backups and video streams can use bandwidth in the background. Pause them temporarily and repeat the test. If performance improves, schedule large transfers for quieter times or manage quality-of-service settings when your router supports them. Do not disconnect devices permanently without checking who relies on them.</p>
<h2>5. Restart and update carefully</h2>
<p>Follow the router manufacturer's instructions for restarting equipment and installing firmware updates. Before resetting the device to factory settings, check that you have the configuration information needed to reconnect. A factory reset is a larger step than a simple restart and can remove custom network settings.</p>
<h2>6. Compare Wi-Fi with a wired connection</h2>
<p>If you have a computer and Ethernet cable, test near the router over a wired connection. A much faster wired result points toward Wi-Fi coverage, interference or wireless equipment. Similar slow results across Wi-Fi and Ethernet make it worth checking your service plan, provider status, modem/router hardware and the test server.</p>
<h2>7. Record results before contacting your provider</h2>
<p>Keep the time, device, location, connection type and several results. Test during both busy and quieter periods. This record is more useful than a single screenshot and helps support teams understand whether the issue is persistent.</p>
<p>For Android checks, <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a> lists Wi-Fi and mobile network testing, network information, ping, jitter, download and upload checks, plus test history. Browse the <a href="/speed-test-guide/">speed test guide</a> to make your comparisons repeatable.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Mobile Internet Speed Guide', 'slug' => 'mobile-internet-speed', 'menu_order' => 7,
        'description' => 'Learn how to test mobile data speed, compare it fairly with Wi-Fi, and troubleshoot inconsistent 4G or 5G performance.',
        'content' => <<<'HTML'
<p class="lead">Mobile internet speed can change from one place to another, even when you use the same phone and data plan. Signal conditions, network load, indoor coverage and the technology available in your area all influence performance.</p>
<h2>How to test mobile data speed</h2>
<p>Turn off Wi-Fi when you want to measure mobile data, confirm the phone is using the intended mobile network and run a few tests from the same location. Keep track of the time and signal conditions. If you compare with Wi-Fi, switch networks deliberately so you know which connection the result represents.</p>
<h2>Why mobile speed changes</h2>
<ul>
<li><strong>Signal strength and quality:</strong> walls, buildings, terrain and distance from a cell site can affect the radio link.</li>
<li><strong>Network congestion:</strong> performance may change when many people use the same cell or network resources.</li>
<li><strong>Indoor location:</strong> moving near a window or outdoors may produce a different result.</li>
<li><strong>Phone capabilities:</strong> supported bands, modem performance and software can affect how a device connects.</li>
<li><strong>Plan or network management:</strong> check the current terms of your operator's plan if speeds consistently differ from expectations.</li>
</ul>
<h2>Compare fairly with Wi-Fi</h2>
<p>Use the same phone and similar test method, and avoid running large downloads in the background. Remember that a Wi-Fi test measures the full path through your router and broadband connection, while a mobile-data test uses your mobile operator's network. The result is a comparison of two end-to-end connections at a moment in time, not a universal rating of the technologies.</p>
<h2>What if mobile data is slow?</h2>
<p>Try another nearby location, check the operator's service-status information, toggle airplane mode briefly if appropriate, and restart the device when convenient. Make sure data is enabled and that any plan limits have not been reached. If the problem occurs in one building only, indoor coverage may be the main factor. If it happens across several locations and times, contact your operator with dates and repeatable results.</p>
<p><a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a> lists Wi-Fi and mobile network testing as well as download, upload, ping, latency, jitter and network information features. Use it to compare connections on your Android device, while remembering that each result reflects the conditions at the time of the test.</p>
HTML
    );
    $pages[] = array(
        'title' => 'About Internet Speed Test Free', 'slug' => 'about', 'menu_order' => 8,
        'description' => 'About Internet Speed Test Free: practical, plain-language guides to internet speed, Wi-Fi, latency, jitter and network troubleshooting.',
        'content' => <<<'HTML'
<p class="lead">Internet Speed Test Free is a practical learning resource for people who want to understand their connection instead of guessing why it feels slow.</p>
<h2>What this site covers</h2>
<p>Our guides explain common network measurements, including download speed, upload speed, ping, latency and jitter. We also cover repeatable testing methods, Wi-Fi coverage, mobile data and everyday troubleshooting. The goal is to help readers identify useful next steps and understand the limits of an individual measurement.</p>
<h2>Our approach</h2>
<p>Internet performance depends on several factors: your device, wireless or wired connection, router, provider, route to a destination, server load and time of day. We avoid presenting a single test number as a guarantee of performance everywhere. When possible, compare repeated results taken under similar conditions and look at more than one measurement.</p>
<h2>About PingMaster Pro</h2>
<p>We also share information about <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a>, an Android application published by MoonKnight Studios. Its Google Play listing describes download and upload checks, ping and latency, jitter, Wi-Fi and mobile network tests, network information, a test history and live network-speed features. Check the live listing for the latest details.</p>
<h2>Accuracy and updates</h2>
<p>Network technologies and service plans change over time. Use each guide as general information and check your device, router and internet provider's instructions for product-specific steps. If a page needs clarification, you can reach the site contact email at <a href="mailto:support.moonknightstudio@gmail.com">support.moonknightstudio@gmail.com</a>.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Contact', 'slug' => 'contact', 'menu_order' => 9,
        'description' => 'Contact Internet Speed Test Free about site corrections, guide suggestions, and PingMaster Pro Speed Test support.',
        'content' => <<<'HTML'
<p class="lead">Have a correction, a suggestion for an internet troubleshooting guide, or a question about the information published here? Get in touch.</p>
<h2>Site feedback</h2>
<p>For editorial feedback, broken links or topic suggestions, email <a href="mailto:support.moonknightstudio@gmail.com">support.moonknightstudio@gmail.com</a>. Please include the page URL and a short description of the issue so it is easier to investigate.</p>
<h2>PingMaster Pro support</h2>
<p>For questions about the PingMaster Pro: Speed Test Android app, use the support contact shown on its <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">Google Play listing</a>. Please do not email passwords, payment details or other sensitive information.</p>
<p>This website does not promise a particular internet speed or diagnose a provider's network from a single result. For persistent service problems, record repeat tests and contact your internet provider directly.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Privacy and Site Notes', 'slug' => 'privacy-site-notes', 'menu_order' => 10,
        'description' => 'Privacy and site notes for Internet Speed Test Free, including WordPress hosting and links to external services.',
        'content' => <<<'HTML'
<p class="lead">This page explains the basic privacy considerations for visitors to Internet Speed Test Free. Please review the active WordPress.com privacy and cookie settings before treating this text as a complete legal privacy policy.</p>
<h2>Website hosting</h2>
<p>This website is hosted on WordPress.com. The platform may process technical information and visitor data as described in its own privacy documentation and according to the site's active settings. Consult the current WordPress.com privacy documentation for details about platform processing, cookies and controls.</p>
<h2>External links</h2>
<p>Some pages link to Google Play and other external websites. When you follow an external link, that service's own privacy policy and terms apply. This site does not control how external services collect or use information.</p>
<h2>Contact</h2>
<p>For questions about this site page, email <a href="mailto:support.moonknightstudio@gmail.com">support.moonknightstudio@gmail.com</a>. If you add analytics, advertising, comment forms, newsletter subscriptions or other third-party integrations later, update this page to describe those tools accurately before publishing them.</p>
<p>As site features change, this page should be reviewed so that the information continues to match the services and settings actually in use.</p>
HTML
    );
    $pages[] = array(
        'title' => 'Guides', 'slug' => 'guides', 'menu_order' => 11,
        'description' => 'Read practical internet speed, Wi-Fi, ping, jitter and network troubleshooting articles.',
        'content' => '<p>Browse the latest practical guides to internet speed tests, download and upload rates, ping, jitter, Wi-Fi coverage and mobile data.</p>'
    );
    $posts = array();
    $posts[] = array(
        'title' => 'Mbps vs MBps: What Is the Difference?', 'slug' => 'mbps-vs-mbps-megabit-megabyte',
        'description' => 'Understand Mbps and MB/s, why download progress can look slower than your internet speed test, and how to convert the units.',
        'content' => <<<'HTML'
<p class="lead">Mbps and MB/s look similar but represent different units. Internet providers and speed tests commonly use megabits per second (Mbps), while some file managers display megabytes per second (MB/s).</p>
<h2>Bits and bytes</h2>
<p>A byte contains eight bits. As a simple unit conversion, divide a Mbps figure by eight to estimate a theoretical MB/s rate: 80 Mbps is about 10 MB/s, and 40 Mbps is about 5 MB/s. This is a mathematical conversion, not a promise about a real download.</p>
<h2>Why a real download may be slower</h2>
<p>File downloads include protocol overhead and depend on the server, route, disk, browser, Wi-Fi conditions and other traffic. A server may limit how quickly it sends data, and a test server may have a different route or capacity from the site hosting your file. Because of these differences, a download rate that does not exactly match the converted speed-test number is not automatically evidence of a problem.</p>
<h2>Compare the right measurements</h2>
<p>When checking performance, compare the same type of unit. If your speed test reports Mbps and your file manager reports MB/s, convert before comparing. Then repeat the test under similar conditions and note what else is using the network.</p>
<p>Read the <a href="/download-speed/">download speed guide</a> for more context, or use <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test on Google Play</a> to check download and upload performance on Android.</p>
HTML
    );
    $posts[] = array(
        'title' => 'Why Is My Wi-Fi Slower Than My Internet Plan?', 'slug' => 'why-wifi-is-slower-than-internet-plan',
        'description' => 'Discover common reasons Wi-Fi speed falls below an internet plan and how to compare wireless, wired and repeated test results.',
        'content' => <<<'HTML'
<p class="lead">The speed listed on an internet plan describes a service tier, but the result on an individual device can be affected by the whole path from your provider to the device.</p>
<h2>Separate broadband performance from Wi-Fi</h2>
<p>Start with a test close to the router, then try Ethernet if it is available. A much better wired result suggests the wireless connection could be the bottleneck. Similar results on Wi-Fi and Ethernet point toward other possibilities, including the service line, router, provider congestion or test server.</p>
<h2>Look at signal, band and distance</h2>
<p>Wi-Fi weakens through walls and floors, and interference from neighbouring networks can reduce performance. A 2.4 GHz connection may reach farther, while 5 GHz often offers higher capacity at shorter distances. Which works best depends on the room layout, device and local radio conditions.</p>
<h2>Check competing traffic</h2>
<p>Cloud backups, streaming, updates and large downloads can consume capacity. Pause them temporarily and repeat the test. If a connection is fast when only one device is active but struggles under normal household use, available bandwidth or router traffic management may be contributing.</p>
<h2>Make comparisons repeatable</h2>
<p>Use the same device and test method, note the time and location, and repeat a few times. A single result cannot show the normal performance of a connection. For Android, <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a> lists download/upload checks, ping, jitter, Wi-Fi and mobile testing, network details and test history.</p>
<p>Continue with our step-by-step <a href="/fix-slow-wifi/">slow Wi-Fi troubleshooting guide</a> or <a href="/speed-test-guide/">internet speed test guide</a>.</p>
HTML
    );
    $posts[] = array(
        'title' => 'What Internet Speed Do Video Calls, Gaming and Streaming Need?', 'slug' => 'internet-speed-for-calls-gaming-streaming',
        'description' => 'Learn how to assess internet speed for video calls, gaming and streaming without relying on one universal Mbps number.',
        'content' => <<<'HTML'
<p class="lead">The connection you need depends on the activity, its quality settings and how many devices share the network. No single Mbps figure guarantees the same experience for every service.</p>
<h2>Streaming video</h2>
<p>Video services adjust quality based on available throughput and their own buffering behaviour. Higher-resolution video generally needs more sustained data than lower-resolution video. Multiple streams, game downloads and backups running at the same time add to household demand. Check the current recommendations published by the service you use, as requirements can change.</p>
<h2>Video calls</h2>
<p>Calls need enough upload and download capacity, but stable timing also matters. High jitter, packet loss or inconsistent Wi-Fi can cause freezing and choppy audio even if a speed test reports healthy throughput. Move closer to the router, pause large uploads and compare with a wired connection where practical.</p>
<h2>Online gaming</h2>
<p>Many games do not require extremely high download speed during play, but updates may be large and responsiveness depends on latency, route quality and server location. A low ping to a generic test server is not proof that a particular game server will be equally responsive. Check in-game network indicators as well as a general speed test.</p>
<h2>Estimate household needs</h2>
<p>Think about simultaneous activities rather than the most demanding activity in isolation. Several video streams plus a work call and a large download can create more demand than one person browsing. Test at the time your household is busiest to understand how the connection behaves in real use.</p>
<p>For a more meaningful picture, compare download, upload, <a href="/ping-latency/">ping and latency</a>, and <a href="/jitter/">jitter</a>. <a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a> lists these network checks for Android.</p>
HTML
    );
    $posts[] = array(
        'title' => 'How to Keep an Internet Speed Test Fair and Repeatable', 'slug' => 'repeatable-internet-speed-test-checklist',
        'description' => 'Use this practical checklist to compare internet speed tests fairly across rooms, devices, Wi-Fi and mobile data.',
        'content' => <<<'HTML'
<p class="lead">A repeatable process is more useful than chasing a single record result. Follow the same steps each time so changes are easier to understand.</p>
<h2>Quick checklist</h2>
<ol>
<li>Use the same device and test method for each comparison.</li>
<li>Record whether the device uses Ethernet, Wi-Fi or mobile data.</li>
<li>Note location, time and whether other devices are busy.</li>
<li>Pause large transfers where practical, but also run a test during normal household use if that is when the problem occurs.</li>
<li>Run two or three tests instead of treating one result as final.</li>
<li>Compare like with like; a test against a different server may produce a different result.</li>
</ol>
<h2>What to write down</h2>
<p>Record download speed, upload speed, ping and jitter where available. Add short context such as “near router,” “bedroom,” “Ethernet,” or “mobile data.” This makes the history actionable rather than a list of disconnected numbers.</p>
<h2>When to escalate</h2>
<p>If repeated wired tests remain well below your plan across different times, keep the results and contact your provider. If performance changes sharply by room, investigate Wi-Fi coverage first. If throughput is fine but calls or games still feel unstable, check latency, jitter, packet loss and the specific service's status.</p>
<p><a href="https://play.google.com/store/apps/details?id=com.moonknightstudios.pingmasterpro" target="_blank" rel="noopener">PingMaster Pro: Speed Test</a> includes a test history feature according to its current Google Play listing. Read the main <a href="/speed-test-guide/">speed test guide</a> for a fuller explanation of the measurements.</p>
HTML
    );
    return array( $pages, $posts );
}

function speedsignal_create_menu( $menu_name, $location ) {
    $menu_obj = wp_get_nav_menu_object( $menu_name );
    if ( $menu_obj ) { $menu_id = (int) $menu_obj->term_id; }
    else {
        $menu_id = wp_create_nav_menu( $menu_name );
        if ( is_wp_error( $menu_id ) ) { return; }
    }
    $locations = get_nav_menu_locations();
    if ( ! empty( $locations[ $location ] ) && (int) $locations[ $location ] === (int) $menu_id ) { return; }
    $items = wp_get_nav_menu_items( $menu_id );
    if ( empty( $items ) ) {
        $pages = array(
            array( 'title' => 'Home', 'url' => home_url( '/' ), 'type' => 'custom' ),
            array( 'title' => 'Speed Test Guide', 'url' => home_url( '/speed-test-guide/' ), 'type' => 'custom' ),
            array( 'title' => 'Download Speed', 'url' => home_url( '/download-speed/' ), 'type' => 'custom' ),
            array( 'title' => 'Ping & Latency', 'url' => home_url( '/ping-latency/' ), 'type' => 'custom' ),
            array( 'title' => 'Fix Slow Wi-Fi', 'url' => home_url( '/fix-slow-wifi/' ), 'type' => 'custom' ),
            array( 'title' => 'Guides', 'url' => home_url( '/guides/' ), 'type' => 'custom' ),
            array( 'title' => 'Get the App', 'url' => speedsignal_app_url(), 'type' => 'custom' ),
        );
        foreach ( $pages as $item ) {
            wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title' => $item['title'],
                'menu-item-url' => $item['url'],
                'menu-item-status' => 'publish',
                'menu-item-type' => $item['type'],
                'menu-item-attr-title' => $item['title'] === 'Get the App' ? 'Open PingMaster Pro on Google Play' : '',
                'menu-item-target' => $item['title'] === 'Get the App' ? '_blank' : '',
            ) );
        }
    }
    $locations[ $location ] = (int) $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
}

function speedsignal_activate_theme() {
    list( $pages, $posts ) = speedsignal_get_seed_content();
    $ids = array();
    foreach ( $pages as $page ) { $ids[ $page['slug'] ] = speedsignal_seed_page( $page ); }
    foreach ( $posts as $post ) { speedsignal_seed_post( $post ); }

    if ( ! empty( $ids['home'] ) ) {
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', (int) $ids['home'] );
    }
    if ( ! empty( $ids['guides'] ) ) { update_option( 'page_for_posts', (int) $ids['guides'] ); }

    $site_title = get_option( 'blogname' );
    if ( ! $site_title || 'internetspeedtestfree' === strtolower( $site_title ) || 'site title' === strtolower( $site_title ) ) {
        update_option( 'blogname', 'Internet Speed Test Free' );
    }
    speedsignal_create_menu( 'SpeedSignal Primary', 'primary' );
    speedsignal_create_menu( 'SpeedSignal Footer', 'footer' );
}
add_action( 'after_switch_theme', 'speedsignal_activate_theme' );

function speedsignal_is_seo_plugin_active() {
    return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

function speedsignal_document_title_parts( $parts ) {
    if ( is_front_page() ) { $parts['title'] = 'Internet Speed Test Free: Speed, Ping, Jitter & Wi-Fi Guides'; }
    return $parts;
}
add_filter( 'document_title_parts', 'speedsignal_document_title_parts' );

function speedsignal_meta_and_schema() {
    if ( speedsignal_is_seo_plugin_active() ) { return; }
    $title = wp_get_document_title();
    $description = '';
    if ( is_singular() ) {
        $id = get_queried_object_id();
        $description = get_post_meta( $id, '_speedsignal_meta_description', true );
        if ( ! $description ) {
            $post = get_post( $id );
            if ( $post ) { $description = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '' ); }
        }
    } elseif ( is_front_page() ) {
        $description = 'Learn how to check internet speed, understand download, upload, ping and jitter, and troubleshoot Wi-Fi with practical guides and PingMaster Pro for Android.';
    } elseif ( is_search() ) {
        $description = 'Search practical guides to internet speed tests, Wi-Fi, ping, jitter and network troubleshooting.';
    } elseif ( is_archive() ) {
        $description = 'Explore practical internet speed, Wi-Fi, ping, jitter and network troubleshooting guides.';
    }
    $description = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $description ) ) );
    if ( $description ) { echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n"; }
    if ( is_singular() ) {
        $canonical = wp_get_canonical_url();
        if ( $canonical ) { echo '<meta property="og:url" content="' . esc_url( $canonical ) . '">' . "\n"; }
    }
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    if ( $description ) { echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n"; }
    echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary">' . "\n";

    if ( is_front_page() ) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@graph' => array(
                array(
                    '@type' => 'WebSite',
                    '@id' => home_url( '/#website' ),
                    'url' => home_url( '/' ),
                    'name' => get_bloginfo( 'name' ),
                    'description' => $description,
                    'inLanguage' => get_bloginfo( 'language' ),
                ),
                array(
                    '@type' => 'Organization',
                    '@id' => home_url( '/#organization' ),
                    'name' => 'MoonKnight Studios',
                    'url' => home_url( '/' ),
                ),
                array(
                    '@type' => 'SoftwareApplication',
                    'name' => 'PingMaster Pro: Speed Test',
                    'applicationCategory' => 'UtilitiesApplication',
                    'operatingSystem' => 'Android',
                    'url' => speedsignal_app_url(),
                    'downloadUrl' => speedsignal_app_url(),
                    'publisher' => array( '@type' => 'Organization', 'name' => 'MoonKnight Studios' ),
                ),
            ),
        );
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
    if ( is_singular( 'post' ) ) {
        $post_id = get_queried_object_id();
        $post = get_post( $post_id );
        if ( $post ) {
            $article = array(
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => get_the_title( $post_id ),
                'datePublished' => get_the_date( DATE_W3C, $post_id ),
                'dateModified' => get_the_modified_date( DATE_W3C, $post_id ),
                'mainEntityOfPage' => get_permalink( $post_id ),
                'author' => array( '@type' => 'Organization', 'name' => 'Internet Speed Test Free' ),
                'publisher' => array( '@type' => 'Organization', 'name' => 'MoonKnight Studios' ),
            );
            echo '<script type="application/ld+json">' . wp_json_encode( $article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
        }
    }
}
add_action( 'wp_head', 'speedsignal_meta_and_schema', 2 );

function speedsignal_excerpt_more( $more ) { return '…'; }
add_filter( 'excerpt_more', 'speedsignal_excerpt_more' );

function speedsignal_page_url( $slug ) {
    $page = get_page_by_path( $slug, OBJECT, 'page' );
    return $page ? get_permalink( $page->ID ) : home_url( '/' . trim( $slug, '/' ) . '/' );
}
