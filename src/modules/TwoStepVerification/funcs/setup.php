<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_MOD_2STEP_VERIFICATION')) {
    die('Stop!!!');
}

if (!empty($user_info['active2step'])) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];

$nv_redirect = '';
if ($nv_Request->isset_request('nv_redirect', 'post,get')) {
    $nv_redirect = nv_get_redirect();
}

/**
 * nv_json_result()
 *
 * @param mixed $array
 * @return
 */
function nv_json_result($array)
{
    global $nv_redirect;

    $array['redirect'] = $nv_redirect ? nv_redirect_decrypt($nv_redirect) : '';
    nv_jsonOutput($array);
}

// Show QR-Image
if (isset($array_op[1]) and $array_op[1] == 'qr-image') {
    $url = 'otpauth://totp/' . $user_info['email'] . '?secret=' . $secretkey . '&issuer=' . urlencode(NV_SERVER_NAME . ' | ' . $user_info['username']);
    $qrCode = new Endroid\QrCode\QrCode($url);

    $qrCode->setSize(300);
    $qrCode->setWriterByName('png');
    $qrCode->setMargin(10);
    $qrCode->setEncoding('UTF-8');
    $qrCode->setErrorCorrectionLevel(Endroid\QrCode\ErrorCorrectionLevel::MEDIUM);

    header('Content-Type: '. $qrCode->getContentType());
    echo $qrCode->writeString();
    exit();
}

// Verify code
$checkss = $nv_Request->get_title('checkss', 'post', '');

if ($checkss == NV_CHECK_SESSION) {
    $opt = $nv_Request->get_title('opt', 'post', 6);

    if (!$GoogleAuthenticator->verifyOpt($secretkey, $opt)) {
        nv_json_result(array(
            'status' => 'error',
            'input' => 'opt',
            'mess' => $nv_Lang->getModule('wrong_confirm')
        ));
    }

    try {
        $db->query('UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET active2step=1 WHERE userid=' . $user_info['userid']);
    } catch (Exception $e) {
        trigger_error('Error active 2-step Auth!!!', 256);
    }

    nv_creat_backupcodes();

    nv_json_result(array(
        'status' => 'ok',
        'input' => '',
        'mess' => ''
    ));
}

$contents = nv_theme_config_2step($secretkey, $nv_redirect);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';