<?php
/**
 * Add access to configuration page to the information of this plugin
 *
 * @param array
 * @return array
 **/
function aas_set_config_access($links)
{
    $url = sprintf('<a href="options-general.php?page=%s">%s</a>', AAS_DIR_PATH.'/aas-config.php', __('Settings'));
    array_unshift($links, $url);

    return $links;
}

/**
 * Add configuration page access to administrator panel
 *
 * @param none
 * @return none
 **/
function aas_add_config_page()
{
    add_options_page('Anti Artificial Spam', 'Anti Artificial Spam', 8, __FILE__, 'aas_load_config_page');
}

/**
 * Output HTML content of the configuration page
 *
 * @param none
 * @return string
 **/
function aas_gen_config_page()
{
    // Save options when the submit button is clicked
    if ($_POST['aas_btn_submit'] === 'yes') {
        $options = array();
        $options['aas_banned_ips'] = stripslashes(apply_filters('aas_msg_sec', trim($_POST['aas_banned_ips'])));
        $options['aas_banned_authors'] = stripslashes(apply_filters('aas_msg_sec', trim($_POST['aas_banned_authors'])));
        $options['aas_banned_emails'] = stripslashes(apply_filters('aas_msg_sec', trim($_POST['aas_banned_emails'])));
        $options['aas_banned_urls'] = stripslashes(apply_filters('aas_msg_sec', trim($_POST['aas_banned_urls'])));
        $options['aas_banned_keywords'] = stripslashes(apply_filters('aas_msg_sec', trim($_POST['aas_banned_keywords'])));
        $options['aas_ignore_case'] = $_POST['aas_ignore_case'];
        // Filter empty lines out of every option list
        foreach ($options as $key=>$strOpt) {
            $arrOpt = explode("\n", $strOpt);
            $arrTmp = array();
            foreach ($arrOpt as $opt) {
                $opt = trim($opt);
                if (!empty($opt)) {
                    $arrTmp[] = $opt;
                }
            }
            $strOpt = implode("\n", $arrTmp);
            $options[$key] = trim($strOpt);
        }
        update_option('aas_options', $options);
        echo '<div class="updated"><p><strong>Options updated !</strong></p></div>';
    }

    // Get values of options, preparing for displaying
    $options = aas_get_options();
?>
<div class="wrap" style="margin:10px">
    <h2>Anti Artificial Spam</h2>
    <div style="margin-bottom:10px">
        <span style="">Keywords</span>
        <span style="">|</span>
        <span style=""><a href="<?php echo './options-general.php?page='.AAS_DIR_PATH.'/aas-config.php?disp=comments'; ?>">Add from comments</a></span>
    </div>
    <form name="aas_form_1" method="post" action="<?php echo wp_nonce_url('./options-general.php?page='.AAS_DIR_PATH.'/aas-config.php'); ?>">
        <input type="hidden" name="aas_btn_submit" value='yes'>
        <fieldset>
            <legend>Banned IP addresses, one IP address per line.</legend>
            <textarea name="aas_banned_ips" id="aas_banned_ips" cols="80" rows="5" class="aas_config"><?php echo attribute_escape($options['aas_banned_ips']); ?></textarea>
            <p></p>
        </fieldset>
        <fieldset>
            <legend>Banned authors, one name per line.</legend>
            <textarea name="aas_banned_authors" id="aas_banned_authors" cols="80" rows="5" class="aas_config"><?php echo attribute_escape($options['aas_banned_authors']); ?></textarea>
            <p></p>
        </fieldset>
        <fieldset>
            <legend>Banned emails, one email address per line.</legend>
            <textarea name="aas_banned_emails" id="aas_banned_emails" cols="80" rows="5" class="aas_config"><?php echo attribute_escape($options['aas_banned_emails']); ?></textarea>
            <p></p>
        </fieldset>
        <fieldset>
            <legend>Banned URLs, one URL per line.</legend>
            <textarea name="aas_banned_urls" id="aas_banned_urls" cols="80" rows="5" class="aas_config"><?php echo attribute_escape($options['aas_banned_urls']); ?></textarea>
            <p></p>
        </fieldset>
        <fieldset>
            <legend>Banned keywords, one word per line.</legend>
            <textarea name="aas_banned_keywords" id="aas_banned_keywords" cols="80" rows="5" class="aas_config"><?php echo attribute_escape($options['aas_banned_keywords']); ?></textarea>
            <p></p>
        </fieldset>
        <fieldset>
            <legend>Whether ignore case while validating ?</legend>
            <input type="checkbox" name="aas_ignore_case" id="aas_ignore_case" value="yes" <?php echo ($options['aas_ignore_case'] == 'yes' ? 'checked' : '')?>/>
            <label for="aas_ignore_case">Yes</label>
        </fieldset>
        <fieldset class="submit">
            <legend></legend>
            <input type="submit" name="submit" value="Save"/>
        </fieldset>
    </form>
</div>
<?php
}

function aas_load_config_page()
{
    if ('comments' == $_GET['disp']) {
        echo 'a';
    } else {
        aas_gen_config_page();
    }
}
?>
