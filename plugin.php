<?php
/*
Plugin Name: IP Information
Description: Shows comprehensive IP address details including geolocation, ISP, VPN detection, bot detection with filtering
Version: 1.9
Author: InfoSecREDD
Author URI: https://infosecredd.dev
*/

yourls_add_action( 'post_yourls_info_stats', 'ip_detail_page' );
// Add CSS to head
yourls_add_action( 'html_head', 'ip_info_add_css' );

function ip_info_add_css() {
    echo '<style>
    .ip-info-container {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        max-width: 1000px;
        margin: 20px 0;
    }
    .ip-info-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 20px;
        filter: drop-shadow(0 0 6px rgba(63, 81, 181, 0.05));
    }
    .ip-info-stats {
        background: #f5f5f5;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        filter: drop-shadow(0 0 6px rgba(63, 81, 181, 0.05));
    }
    .ip-info-stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 8px;
        width: 100%;
        margin-bottom: 10px;
    }
    .ip-info-stat-card {
        background: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .ip-info-stat-value {
        font-size: 18px;
        font-weight: 500;
        color: #3f51b5;
    }
    .ip-info-stat-label {
        font-size: 12px;
        color: #757575;
        margin-top: 4px;
    }
    .ip-info-buttons {
        display: flex;
        gap: 8px;
    }
    .ip-info-toggle, .ip-info-button {
        display: inline-block;
        color: #fff;
        background: #3f51b5;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        font-size: 14px;
        filter: drop-shadow(0 0 4px rgba(63, 81, 181, 0.2));
    }
    .ip-info-toggle:hover, .ip-info-button:hover {
        background: #303f9f;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        filter: drop-shadow(0 0 8px rgba(63, 81, 181, 0.3));
    }
    .ip-info-secondary {
        background: #757575;
    }
    .ip-info-secondary:hover {
        background: #616161;
    }
    .ip-info-table {
        width: 100%;
        border-collapse: collapse;
    }
    .ip-info-table th {
        background: #3f51b5;
        color: #fff;
        text-align: left;
        padding: 12px 15px;
        font-weight: 500;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .ip-info-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eeeeee;
        vertical-align: top;
    }
    .ip-info-table tr:last-child td {
        border-bottom: none;
    }
    .ip-info-table tr:hover td {
        background: #f9f9f9;
    }
    .ip-info-badge {
        display: inline-block;
        margin: 2px 4px 2px 0;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.05));
    }
    .ip-info-badge-vpn {
        background: #ffebee;
        color: #d32f2f;
    }
    .ip-info-badge-mobile {
        background: #e3f2fd;
        color: #1976d2;
    }
    .ip-info-badge-bot {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    .ip-info-badge-this {
        background: #e8f5e9;
        color: #388e3c;
    }
    .ip-info-agent-tag {
        display: inline-block;
        margin: 2px 4px 2px 0;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        background: #eeeeee;
        color: #424242;
        filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.05));
    }
    .ip-info-app-tag {
        background: #e8f5e9;
        color: #2e7d32;
    }
    /* App-specific branded colors */
    .ip-info-app-facebook {
        background: #e3f2fd;
        color: #1877F2;
    }
    .ip-info-app-instagram {
        background: #fce4ec;
        color: #E4405F;
    }
    .ip-info-app-twitter {
        background: #e1f5fe;
        color: #1DA1F2;
    }
    .ip-info-app-whatsapp {
        background: #e8f5e9;
        color: #25D366;
    }
    .ip-info-app-telegram {
        background: #e1f5fe;
        color: #0088CC;
    }
    .ip-info-app-discord {
        background: #ede7f6;
        color: #5865F2;
    }
    .ip-info-app-slack {
        background: #f3e5f5;
        color: #4A154B;
    }
    .ip-info-app-tiktok {
        background: #f5f5f5;
        color: #000000;
    }
    .ip-info-app-linkedin {
        background: #e1f5fe;
        color: #0A66C2;
    }
    .ip-info-app-pinterest {
        background: #ffebee;
        color: #E60023;
    }
    .ip-info-app-reddit {
        background: #ffebee;
        color: #FF4500;
    }
    .ip-info-app-snapchat {
        background: #fffde7;
        color: #FFFC00;
        color: #333333;
    }
    .ip-info-app-wechat {
        background: #e8f5e9;
        color: #07C160;
    }
    .ip-info-app-line {
        background: #e8f5e9;
        color: #00B900;
    }
    .ip-info-app-skype {
        background: #e1f5fe;
        color: #00AFF0;
    }
    .ip-info-app-teams {
        background: #e3f2fd;
        color: #6264A7;
    }
    .ip-info-app-zoom {
        background: #e1f5fe;
        color: #2D8CFF;
    }
    .ip-info-app-brave {
        background: #ffebee;
        color: #FB542B;
    }
    .ip-info-app-duckduckgo {
        background: #fff3e0;
        color: #DE5833;
    }
    .ip-info-app-outlook {
        background: #e1f5fe;
        color: #0078D4;
    }
    .ip-info-app-yahoo {
        background: #e3f2fd;
        color: #5F01D1;
    }
    .ip-info-app-thunderbird {
        background: #e3f2fd;
        color: #0A84FF;
    }
    .ip-info-app-viber {
        background: #ede7f6;
        color: #7360F2;
    }
    .ip-info-summary {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 12px;
    }
    .ip-info-summary-row {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .ip-info-icon {
        width: 16px;
        height: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
    }
    .ip-info-collapsible {
        margin-top: 8px;
    }
    .ip-info-toggle-btn {
        background: none;
        border: none;
        color: #3f51b5;
        font-size: 12px;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        font-weight: 500;
    }
    .ip-info-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 8px;
        font-size: 12px;
        background: #f9f9f9;
        border-radius: 4px;
        padding: 8px;
        border: 1px solid #eeeeee;
        filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.05));
    }
    .ip-info-details div {
        padding: 4px;
    }
    .ip-info-details strong {
        color: #616161;
    }
    .ip-info-details-hidden {
        display: none;
    }
    .ip-info-user-agent {
        margin-bottom: 8px;
    }
    .ip-info-agent-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-top: 5px;
    }
    .ip-info-referrer {
        color: #757575;
        font-size: 12px;
        word-break: break-all;
    }
    .ip-info-referrer-tag {
        background: #e0f7fa;
        color: #0097a7;
        font-weight: 500;
    }
    @media (max-width: 768px) {
        .ip-info-details {
            grid-template-columns: 1fr;
        }
        .ip-info-stats {
            flex-direction: column;
            align-items: flex-start;
        }
        .ip-info-stats-cards {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    </style>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add event listeners to toggle details buttons
        document.querySelectorAll(".ip-info-toggle-btn").forEach(btn => {
            btn.addEventListener("click", function() {
                const detailsId = this.dataset.target;
                const details = document.getElementById(detailsId);
                if (details.classList.contains("ip-info-details-hidden")) {
                    details.classList.remove("ip-info-details-hidden");
                    this.innerHTML = "▲ Hide Details";
                } else {
                    details.classList.add("ip-info-details-hidden");
                    this.innerHTML = "▼ Show Details";
                }
            });
        });
    });
    </script>';
}

function get_ip_info($ip) {
    // API endpoint with all fields
    $api_url = "http://ip-api.com/json/{$ip}?fields=status,message,continent,continentCode,country,countryCode,region,regionName,city,district,zip,lat,lon,timezone,offset,currency,isp,org,as,asname,reverse,mobile,proxy,hosting,query";
    
    // Make API request
    $response = @file_get_contents($api_url);
    return json_decode($response, true);
}

function is_bot($user_agent) {
    // Common bot signatures to check in user agent
    $bot_signatures = array(
        'bot', 'crawl', 'spider', 'slurp', 'search', 'fetch', 'snippet', 'Googlebot',
        'Baiduspider', 'PhantomJS', 'Sogou', 'facebookexternalhit', 'WhatsApp',
        'Screaming Frog', 'Pingdom', 'GTmetrix', 'WooRank', 'AhrefsBot',
        'Semrush', 'SemrushBot', 'Yandex', 'Amazonaws', 'scraper', 'lighthouse',
        'Chrome-Lighthouse', 'PTST', 'HeadlessChrome', 'YandexBot', 'Bytespider',
        'BingBot', 'Applebot', 'DuckDuckBot', 'Twitterbot'
    );
    
    // Check for bot signatures in the user agent string
    foreach ($bot_signatures as $signature) {
        if (stripos($user_agent, $signature) !== false) {
            return true;
        }
    }
    
    return false;
}

function parse_user_agent($user_agent) {
    $result = array(
        'browser' => null,
        'browser_version' => null,
        'os' => null,
        'device' => null,
        'tags' => array()
    );
    
    // Device detection
    if (strpos($user_agent, 'Mobile') !== false || strpos($user_agent, 'Android') !== false && strpos($user_agent, 'Mobile') !== false) {
        $result['device'] = 'Mobile';
    } elseif (strpos($user_agent, 'iPad') !== false || strpos($user_agent, 'Android') !== false && strpos($user_agent, 'Mobile') === false) {
        $result['device'] = 'Tablet';
    } else {
        $result['device'] = 'Desktop';
    }
    
    // OS detection
    if (strpos($user_agent, 'Windows') !== false) {
        preg_match('/Windows NT ([0-9\.]+)/', $user_agent, $matches);
        $windows_version = isset($matches[1]) ? $matches[1] : '';
        
        $windows_versions = array(
            '10.0' => 'Windows 10',
            '6.3' => 'Windows 8.1',
            '6.2' => 'Windows 8',
            '6.1' => 'Windows 7',
            '6.0' => 'Windows Vista',
            '5.2' => 'Windows XP x64',
            '5.1' => 'Windows XP',
            '5.0' => 'Windows 2000'
        );
        
        $result['os'] = isset($windows_versions[$windows_version]) ? $windows_versions[$windows_version] : 'Windows';
    } elseif (strpos($user_agent, 'Macintosh') !== false) {
        preg_match('/Mac OS X ([0-9_\.]+)/', $user_agent, $matches);
        $result['os'] = 'macOS';
        if (isset($matches[1])) {
            $version = str_replace('_', '.', $matches[1]);
            if (version_compare($version, '10.16', '>=') || version_compare($version, '11.0', '>=')) {
                $result['os'] = 'macOS 11+';
            } else {
                $result['os'] = 'macOS ' . $version;
            }
        }
    } elseif (strpos($user_agent, 'iPhone') !== false) {
        preg_match('/iPhone OS ([0-9_]+)/', $user_agent, $matches);
        $result['os'] = 'iOS';
        if (isset($matches[1])) {
            $result['os'] = 'iOS ' . str_replace('_', '.', $matches[1]);
        }
    } elseif (strpos($user_agent, 'iPad') !== false) {
        preg_match('/CPU OS ([0-9_]+)/', $user_agent, $matches);
        $result['os'] = 'iPadOS';
        if (isset($matches[1])) {
            $result['os'] = 'iPadOS ' . str_replace('_', '.', $matches[1]);
        }
    } elseif (strpos($user_agent, 'Android') !== false) {
        preg_match('/Android ([0-9\.]+)/', $user_agent, $matches);
        $result['os'] = 'Android';
        if (isset($matches[1])) {
            $result['os'] = 'Android ' . $matches[1];
        }
    } elseif (strpos($user_agent, 'Linux') !== false) {
        $result['os'] = 'Linux';
    }
    
    // Browser detection
    if (strpos($user_agent, 'Firefox') !== false && strpos($user_agent, 'Seamonkey') === false) {
        preg_match('/Firefox\/([0-9\.]+)/', $user_agent, $matches);
        $result['browser'] = 'Firefox';
        if (isset($matches[1])) {
            $result['browser_version'] = $matches[1];
        }
    } elseif (strpos($user_agent, 'Chrome') !== false && strpos($user_agent, 'Chromium') === false && strpos($user_agent, 'Edg') === false) {
        preg_match('/Chrome\/([0-9\.]+)/', $user_agent, $matches);
        $result['browser'] = 'Chrome';
        if (isset($matches[1])) {
            $result['browser_version'] = $matches[1];
        }
    } elseif (strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Chrome') === false && strpos($user_agent, 'Chromium') === false) {
        preg_match('/Safari\/([0-9\.]+)/', $user_agent, $matches);
        $result['browser'] = 'Safari';
        if (isset($matches[1])) {
            preg_match('/Version\/([0-9\.]+)/', $user_agent, $version_matches);
            if (isset($version_matches[1])) {
                $result['browser_version'] = $version_matches[1];
            }
        }
    } elseif (strpos($user_agent, 'Edg') !== false) {
        preg_match('/Edg\/([0-9\.]+)/', $user_agent, $matches);
        $result['browser'] = 'Edge';
        if (isset($matches[1])) {
            $result['browser_version'] = $matches[1];
        }
    } elseif (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
        preg_match('/MSIE ([0-9\.]+)/', $user_agent, $matches);
        $result['browser'] = 'Internet Explorer';
        if (isset($matches[1])) {
            $result['browser_version'] = $matches[1];
        } else if (strpos($user_agent, 'Trident/7.0') !== false) {
            $result['browser_version'] = '11.0';
        }
    } elseif (strpos($user_agent, 'OPR') !== false || strpos($user_agent, 'Opera') !== false) {
        preg_match('/OPR\/([0-9\.]+)/', $user_agent, $matches);
        if (isset($matches[1])) {
            $result['browser'] = 'Opera';
            $result['browser_version'] = $matches[1];
        } else {
            preg_match('/Opera\/([0-9\.]+)/', $user_agent, $matches);
            if (isset($matches[1])) {
                $result['browser'] = 'Opera';
                $result['browser_version'] = $matches[1];
            }
        }
    }
    
    // Special app detection
    if (strpos($user_agent, 'FB_IAB') !== false || strpos($user_agent, 'FBAN') !== false) {
        $result['tags'][] = 'Facebook App';
    }
    if (strpos($user_agent, 'Instagram') !== false) {
        $result['tags'][] = 'Instagram App';
    }
    if (strpos($user_agent, 'Twitter') !== false) {
        $result['tags'][] = 'Twitter App';
    }
    if (strpos($user_agent, 'WhatsApp') !== false) {
        $result['tags'][] = 'WhatsApp';
    }
    if (strpos($user_agent, 'Telegram') !== false) {
        $result['tags'][] = 'Telegram';
    }
    // Additional app detection
    if (strpos($user_agent, 'Discord') !== false) {
        $result['tags'][] = 'Discord';
    }
    if (strpos($user_agent, 'Slack') !== false) {
        $result['tags'][] = 'Slack';
    }
    if (strpos($user_agent, 'TikTok') !== false || strpos($user_agent, 'Musical') !== false || strpos($user_agent, 'ByteDance') !== false) {
        $result['tags'][] = 'TikTok';
    }
    if (strpos($user_agent, 'LinkedIn') !== false) {
        $result['tags'][] = 'LinkedIn';
    }
    if (strpos($user_agent, 'Pinterest') !== false) {
        $result['tags'][] = 'Pinterest';
    }
    if (strpos($user_agent, 'Snapchat') !== false) {
        $result['tags'][] = 'Snapchat';
    }
    if (strpos($user_agent, 'reddit') !== false) {
        $result['tags'][] = 'Reddit';
    }
    if (strpos($user_agent, 'Viber') !== false) {
        $result['tags'][] = 'Viber';
    }
    if (strpos($user_agent, 'WeChat') !== false || strpos($user_agent, 'MicroMessenger') !== false) {
        $result['tags'][] = 'WeChat';
    }
    if (strpos($user_agent, 'Line/') !== false) {
        $result['tags'][] = 'Line';
    }
    if (strpos($user_agent, 'Skype') !== false) {
        $result['tags'][] = 'Skype';
    }
    if (strpos($user_agent, 'Teams') !== false) {
        $result['tags'][] = 'Microsoft Teams';
    }
    if (strpos($user_agent, 'Zoom') !== false) {
        $result['tags'][] = 'Zoom';
    }
    if (strpos($user_agent, 'DuckDuckGo') !== false) {
        $result['tags'][] = 'DuckDuckGo App';
    }
    if (strpos($user_agent, 'Brave') !== false) {
        $result['tags'][] = 'Brave Browser';
    }
    if (strpos($user_agent, 'Outlook') !== false) {
        $result['tags'][] = 'Outlook';
    }
    if (strpos($user_agent, 'YahooMobile') !== false || strpos($user_agent, 'YahooMailApp') !== false) {
        $result['tags'][] = 'Yahoo App';
    }
    if (strpos($user_agent, 'Thunderbird') !== false) {
        $result['tags'][] = 'Thunderbird';
    }
    
    // Build tags array
    if ($result['device']) {
        $result['tags'][] = $result['device'];
    }
    if ($result['os']) {
        $result['tags'][] = $result['os'];
    }
    if ($result['browser'] && $result['browser_version']) {
        // Just use major version number
        $version_parts = explode('.', $result['browser_version']);
        $result['tags'][] = $result['browser'] . ' ' . $version_parts[0];
    } elseif ($result['browser']) {
        $result['tags'][] = $result['browser'];
    }
    
    return $result;
}

function ip_detail_page($shorturl) {
        $nonce = yourls_create_nonce('ip');
        global $ydb;
        $base  = YOURLS_SITE;
        $table_url = YOURLS_DB_TABLE_URL;
        $table_log = YOURLS_DB_TABLE_LOG;
        $outdata = '';
        
        // Get hide_bots value from GET or set default to 0 (show bots)
        $hide_bots = isset($_GET['hide_bots']) ? intval($_GET['hide_bots']) : 0;
        
        // Create toggle URL (keep other GET parameters intact)
        $get_params = $_GET;
        $get_params['hide_bots'] = $hide_bots ? 0 : 1;
        $toggle_url = $_SERVER['REQUEST_URI'];
        $toggle_url = strtok($toggle_url, '?') . '?' . http_build_query($get_params);
        
        // Toggle button text
        $toggle_text = $hide_bots ? 'Show Bots' : 'Hide Bots';
        
        // Open container for styling
        echo '<div class="ip-info-container">';

        $query = $ydb->fetchObjects("SELECT * FROM `$table_log` WHERE shorturl='$shorturl[0]' ORDER BY click_id DESC LIMIT 1000");

        if ($query) {
                $bot_count = 0;
                $total_count = 0;
                $vpn_count = 0;
                $unique_ips = array();
                $countries = array();
                $browsers = array();
                $operating_systems = array();
                
                foreach( $query as $query_result ) {
                        $total_count++;
                        
                        // Track unique IPs
                        if (!in_array($query_result->ip_address, $unique_ips)) {
                            $unique_ips[] = $query_result->ip_address;
                        }
                        
                        // Track countries
                        if (!empty($query_result->country_code) && !in_array($query_result->country_code, $countries)) {
                            $countries[] = $query_result->country_code;
                        }
                        
                        // Parse user agent for browser and OS stats
                        $parsed_ua = parse_user_agent($query_result->user_agent);
                        if (!empty($parsed_ua['browser']) && !in_array($parsed_ua['browser'], $browsers)) {
                            $browsers[] = $parsed_ua['browser'];
                        }
                        if (!empty($parsed_ua['os']) && !in_array($parsed_ua['os'], $operating_systems)) {
                            $operating_systems[] = $parsed_ua['os'];
                        }
                        
                        // Check if it's a bot
                        $is_bot = is_bot($query_result->user_agent);
                        if ($is_bot) {
                            $bot_count++;
                        }
                        
                        // Skip bots if hide_bots is enabled
                        if ($hide_bots && $is_bot) {
                            continue;
                        }
                        				
                        $me = "";
                        $is_current_ip = $query_result->ip_address == $_SERVER['REMOTE_ADDR'];
                        if ($is_current_ip) { 
                            $me = " style='background: #f1f8e9;'"; 
                        }
                        
                        // Get complete IP information
                        $ip_data = get_ip_info($query_result->ip_address);
                        
                        // Track VPN and mobile connections
                        if ($ip_data && $ip_data['status'] == 'success') {
                            if ((isset($ip_data['proxy']) && $ip_data['proxy']) || (isset($ip_data['hosting']) && $ip_data['hosting'])) {
                                $vpn_count++;
                            }
                        }
                        
                        // Format IP information for display
                        $badges = "";
                        $ip_summary = "";
                        $ip_details = "";
                        $detail_id = "details-" . $query_result->click_id;
                        
                        // Prepare badge for current IP
                        if ($is_current_ip) {
                            $badges .= "<span class='ip-info-badge ip-info-badge-this'>Your IP</span>";
                        }
                        
                        if ($ip_data && $ip_data['status'] == 'success') {
                            // Prepare badges
                            if (isset($ip_data['proxy']) && $ip_data['proxy'] || isset($ip_data['hosting']) && $ip_data['hosting']) {
                                $badges .= "<span class='ip-info-badge ip-info-badge-vpn'>VPN/Proxy</span>";
                            }
                            
                            if ($is_bot) {
                                $badges .= "<span class='ip-info-badge ip-info-badge-bot'>Bot</span>";
                            }
                            
                            // Build location for summary view
                            $location_parts = array();
                            if (!empty($ip_data['city'])) $location_parts[] = $ip_data['city'];
                            if (!empty($ip_data['regionName'])) $location_parts[] = $ip_data['regionName'];
                            if (!empty($ip_data['country'])) $location_parts[] = $ip_data['country'];
                            $location_summary = implode(', ', $location_parts);
                            
                            // Create summary view (always visible)
                            $ip_summary = "<div class='ip-info-summary'>";
                            
                            if (!empty($location_summary)) {
                                $ip_summary .= "<div class='ip-info-summary-row'>
                                    <span class='ip-info-icon'>📍</span> {$location_summary}
                                </div>";
                            }
                            
                            if (!empty($ip_data['isp'])) {
                                $ip_summary .= "<div class='ip-info-summary-row'>
                                    <span class='ip-info-icon'>🌐</span> " . htmlspecialchars(substr($ip_data['isp'], 0, 40)) . (strlen($ip_data['isp']) > 40 ? '...' : '') . "
                                </div>";
                            }
                            
                            $ip_summary .= "</div>";
                            
                            // Create detailed view (expandable)
                            $ip_details = "<div class='ip-info-collapsible'>
                                <button class='ip-info-toggle-btn' data-target='{$detail_id}'>▼ Show Details</button>
                                <div id='{$detail_id}' class='ip-info-details ip-info-details-hidden'>";
                                
                            // Location information
                            if (!empty($ip_data['city']) || !empty($ip_data['regionName']) || !empty($ip_data['country'])) {
                                $ip_details .= "<div><strong>City:</strong> " . (!empty($ip_data['city']) ? $ip_data['city'] : "N/A") . "</div>";
                                $ip_details .= "<div><strong>Region:</strong> " . (!empty($ip_data['regionName']) ? $ip_data['regionName'] : "N/A") . "</div>";
                                $ip_details .= "<div><strong>Country:</strong> " . (!empty($ip_data['country']) ? $ip_data['country'] . " (" . $ip_data['countryCode'] . ")" : "N/A") . "</div>";
                                $ip_details .= "<div><strong>Continent:</strong> " . (!empty($ip_data['continent']) ? $ip_data['continent'] . " (" . $ip_data['continentCode'] . ")" : "N/A") . "</div>";
                                $ip_details .= "<div><strong>ZIP:</strong> " . (!empty($ip_data['zip']) ? $ip_data['zip'] : "N/A") . "</div>";
                                $ip_details .= "<div><strong>Coordinates:</strong> " . (!empty($ip_data['lat']) && !empty($ip_data['lon']) ? 
                                    "{$ip_data['lat']}, {$ip_data['lon']}" : "N/A") . "</div>";
                            }
                            
                            // Network information
                            $ip_details .= "<div><strong>ISP:</strong> " . (!empty($ip_data['isp']) ? htmlspecialchars($ip_data['isp']) : "N/A") . "</div>";
                            $ip_details .= "<div><strong>Organization:</strong> " . (!empty($ip_data['org']) ? htmlspecialchars($ip_data['org']) : "N/A") . "</div>";
                            $ip_details .= "<div><strong>AS:</strong> " . (!empty($ip_data['as']) ? htmlspecialchars($ip_data['as']) : "N/A") . "</div>";
                            $ip_details .= "<div><strong>AS Name:</strong> " . (!empty($ip_data['asname']) ? htmlspecialchars($ip_data['asname']) : "N/A") . "</div>";
                            
                            // Connection information
                            $ip_details .= "<div><strong>Proxy:</strong> " . (isset($ip_data['proxy']) ? ($ip_data['proxy'] ? "Yes" : "No") : "Unknown") . "</div>";
                            $ip_details .= "<div><strong>Hosting:</strong> " . (isset($ip_data['hosting']) ? ($ip_data['hosting'] ? "Yes" : "No") : "Unknown") . "</div>";
                            
                            // Time information
                            $ip_details .= "<div><strong>Timezone:</strong> " . (!empty($ip_data['timezone']) ? $ip_data['timezone'] : "N/A") . "</div>";
                            $ip_details .= "<div><strong>UTC Offset:</strong> " . (isset($ip_data['offset']) ? "UTC " . ($ip_data['offset'] >= 0 ? '+' : '') . $ip_data['offset'] : "N/A") . "</div>";
                            $ip_details .= "<div><strong>Currency:</strong> " . (!empty($ip_data['currency']) ? $ip_data['currency'] : "N/A") . "</div>";
                            
                            // Reverse DNS
                            $ip_details .= "<div><strong>Reverse DNS:</strong> " . (!empty($ip_data['reverse']) ? htmlspecialchars($ip_data['reverse']) : "N/A") . "</div>";
                            
                            $ip_details .= "</div></div>";
                        }
                        
                        // Generate User Agent tags
                        $ua_tags = '';
                        if (!empty($parsed_ua['tags'])) {
                            $ua_tags .= '<div class="ip-info-agent-tags">';
                            foreach ($parsed_ua['tags'] as $tag) {
                                // Check if this is an app tag
                                $app_tags = array(
                                    'Facebook App' => 'facebook',
                                    'Instagram App' => 'instagram',
                                    'Twitter App' => 'twitter',
                                    'WhatsApp' => 'whatsapp',
                                    'Telegram' => 'telegram',
                                    'Discord' => 'discord',
                                    'Slack' => 'slack',
                                    'TikTok' => 'tiktok',
                                    'LinkedIn' => 'linkedin',
                                    'Pinterest' => 'pinterest',
                                    'Snapchat' => 'snapchat',
                                    'Reddit' => 'reddit',
                                    'Viber' => 'viber',
                                    'WeChat' => 'wechat',
                                    'Line' => 'line',
                                    'Skype' => 'skype',
                                    'Microsoft Teams' => 'teams',
                                    'Zoom' => 'zoom',
                                    'DuckDuckGo App' => 'duckduckgo',
                                    'Brave Browser' => 'brave',
                                    'Outlook' => 'outlook',
                                    'Yahoo App' => 'yahoo',
                                    'Thunderbird' => 'thunderbird'
                                );
                                
                                $tag_class = 'ip-info-agent-tag';
                                
                                // Apply specific app class if available
                                if (isset($app_tags[$tag])) {
                                    $tag_class .= ' ip-info-app-' . $app_tags[$tag];
                                } else {
                                    // For non-app tags, keep using the default styling
                                    // or apply other specific styling as needed
                                }
                                
                                $ua_tags .= "<span class='{$tag_class}'>{$tag}</span>";
                            }
                            $ua_tags .= '</div>';
                        }
                        
                        // Handle referrer - format "direct" as "Directly Linked"
                        $referrer = $query_result->referrer;
                        $referrer_display = '';
                        if (trim($referrer) == 'direct') {
                            $referrer_display = "<div class='ip-info-agent-tags'><span class='ip-info-agent-tag ip-info-referrer-tag'>Directly Linked</span></div>";
                        } else if (!empty($referrer)) {
                            $referrer_display = "<div class='ip-info-referrer'>{$referrer}</div>";
                        }
                        
			$outdata .= "<tr{$me}><td>{$query_result->click_time}</td>
						<td>{$query_result->country_code}</td>
						<td>
                            <div style='display:flex; justify-content:space-between; align-items:start;'>
                                <span>{$query_result->ip_address}</span>
                                <div>{$badges}</div>
                            </div>
                            {$ip_summary}
                            {$ip_details}
                        </td>
						<td>
                            <div class='ip-info-user-agent'>{$ua_tags}</div>
                            {$referrer_display}
                        </td></tr>";
                }
                
                // Calculate percentages
                $bot_percentage = $total_count > 0 ? round(($bot_count / $total_count) * 100, 1) : 0;
                $vpn_percentage = $total_count > 0 ? round(($vpn_count / $total_count) * 100, 1) : 0;
                
                // Display enhanced Material Design statistics cards
                echo "<div class='ip-info-stats'>
                        <div class='ip-info-stats-cards'>
                            <div class='ip-info-stat-card'>
                                <div class='ip-info-stat-value'>{$total_count}</div>
                                <div class='ip-info-stat-label'>Total Clicks</div>
                            </div>
                            <div class='ip-info-stat-card'>
                                <div class='ip-info-stat-value'>" . count($unique_ips) . "</div>
                                <div class='ip-info-stat-label'>Unique IPs</div>
                            </div>
                            <div class='ip-info-stat-card'>
                                <div class='ip-info-stat-value'>" . count($countries) . "</div>
                                <div class='ip-info-stat-label'>Countries</div>
                            </div>
                            <div class='ip-info-stat-card'>
                                <div class='ip-info-stat-value'>" . count($browsers) . "</div>
                                <div class='ip-info-stat-label'>Browsers</div>
                            </div>
                            <div class='ip-info-stat-card'>
                                <div class='ip-info-stat-value'>{$bot_count}</div>
                                <div class='ip-info-stat-label'>Bots ({$bot_percentage}%)</div>
                            </div>
                            <div class='ip-info-stat-card'>
                                <div class='ip-info-stat-value'>{$vpn_count}</div>
                                <div class='ip-info-stat-label'>VPN/Proxy ({$vpn_percentage}%)</div>
                            </div>
                        </div>
                        <div class='ip-info-buttons'>
                            <a href='" . htmlspecialchars($toggle_url) . "' class='ip-info-toggle'>{$toggle_text}</a>
                        </div>
                      </div>";
                
                // Table inside a card
                echo "<div class='ip-info-card'>
                        <table class='ip-info-table'>
                            <thead>
                                <tr>
                                    <th width='100'>Timestamp</th>
                                    <th width='60'>Country</th>
                                    <th width='350'>IP Address & Details</th>
                                    <th>Device & Referrer</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$outdata}
                            </tbody>
                        </table>
                      </div>";
        }
        
        // Close container
        echo '</div>';
} 
