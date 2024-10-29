<?php
/*
 * Plugin Name: Anti Artificial Spam
 * Plugin URI: http://sinolog.it/?p=1293
 * Description: Ban some specified people from commenting, according to IPs, Names, URLs, Emails and keywords
 * Author: Lenin Lee
 * Author URI: http://sinolog.it
 * License: GNU General Public License 2.0 http://www.gnu.org/licenses/gpl.html
 * Version: 0.2
 */

// Full path of this plugin's directory
define ('AAS_DIR_PATH', plugin_basename(dirname(__FILE__)));

// Initialization when the plugin is to be activated
register_activation_hook(__FILE__, 'aas_reset_options');

// Add two filters which named 'aas_msg_sec' and are to be used to secure messages received from the options
add_filter('aas_msg_sec', 'wp_filter_kses');
add_filter('aas_msg_sec', 'wptexturize');

if (is_admin()) {
    require 'aas-config.php';
    // Add access URL of configuration page to the information of this plugin
    add_filter('plugin_action_links_'.$plugin, 'aas_set_config_access');
    // Add configuration page
    add_action('admin_menu', 'aas_add_config_page');
}else {
    // Add a filter
    add_filter('preprocess_comment', 'aas_valid_comment');
}

/**
 * Validate whether the information of the comment comes up to the banning condition
 *
 * @param array
 * @return array If the validation is successful, the parameter will be returned intactly, or the page will exit with a piece of message
 **/
function aas_valid_comment($comment)
{
    $options = aas_get_options();
    $aas_banned_ips = explode("\n", $options['aas_banned_ips']);
    $aas_banned_authors = explode("\n", $options['aas_banned_authors']);
    $aas_banned_emails = explode("\n", $options['aas_banned_emails']);
    $aas_banned_urls = explode("\n", $options['aas_banned_urls']);
    $aas_banned_keywords = explode("\n", $options['aas_banned_keywords']);
    $aas_ignore_case = $options['aas_ignore_case'] == 'yes' ? true : false;

    $msg = '';
    $ip = preg_replace( '/[^0-9a-fA-F:., ]/', '',$_SERVER['REMOTE_ADDR']);
    $author = trim($comment['comment_author']);
    $email = trim($comment['comment_author_email']);
    $url = trim($comment['comment_author_url']);
    if (!empty($ip) && in_array($ip, $aas_banned_ips)) {
        $msg = 'Your IP address is banned, you won\'t be able to comment on this site until you have asked the administrator to release it.';
    }
    if (!empty($author)) {
        foreach ($aas_banned_authors as $banned_author) {
            if (($aas_ignore_case && strtolower($author) == strtolower($banned_author)) || (!$aas_ignore_case && $author == $banned_author)) {
                $msg = 'Your name is banned, you won\'t be able to comment on this site until you have asked the administrator to release it.';
                break;
            }
        }
    }
    if (!empty($email)) {
        foreach ($aas_banned_emails as $banned_email) {
            if (($aas_ignore_case && strtolower($email) == strtolower($banned_email)) || (!$aas_ignore_case && $email == $banned_email)) {
                $msg = 'Your email address is banned, you won\'t be able to comment on this site until you have asked the administrator to release it.';
                break;
            }
        }
    }
    if (!empty($url)) {
        foreach ($aas_banned_urls as $banned_url) {
            if (($aas_ignore_case && false!==stripos($url, $banned_url)) || (!$aas_ignore_case && false!==strpos($url, $banned_url))) {
                $msg = 'Your URL is banned, you won\'t be able to comment on this site until you have asked the administrator to release it.';
                break;
            }
        }
    }
    foreach ($aas_banned_keywords as $keyword) {
        if ((!$aas_ignore_case && false!==strpos($comment['comment_content'], $keyword)) || ($aas_ignore_case && false!==stripos($comment['comment_content'], $keyword))) {
            $msg = 'Your comment contains (<strong>'.$keyword.'</strong>), which is a banned keyword.';
            break;
        }
    }

    if (!empty($msg)) {
        header('Content-Type: text/html; charset=UTF-8');
        exit($msg);
    }

    return $comment;
}

/**
 * Initialize or reset options
 *
 * @param none
 * @return none
 **/
function aas_reset_options()
{
    $options = array();
    $options['aas_banned_ips'] = '';
    $options['aas_banned_authors'] = '';
    $options['aas_banned_emails'] = '';
    $options['aas_banned_urls'] = '';
    $options['aas_banned_keywords'] = "fuck\nasshole";
    $options['aas_ignore_case'] = 'yes';

    add_option('aas_options', $options, '', 'yes');
}

/**
 * Get options
 *
 * @param none
 * @return array
 **/
function aas_get_options()
{
    return get_option('aas_options', true);
}
?>
