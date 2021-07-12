<?php

/**
 * NUKEVIET Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$module_version = [
    'name' => 'Users',
    'modfuncs' => 'main,login,logout,register,lostpass,active,editinfo,avatar,lostactivelink,memberlist,groups',
    'submenu' => 'main,login,logout,register,lostpass,active,editinfo,lostactivelink,groups,memberlist',
    'is_sysmod' => 1,
    'virtual' => 1,
    'version' => '5.0.00',
    'date' => 'Tuesday, June 22, 2021 16:00:00 GMT+07:00',
    'author' => 'VINADES.,JSC <contact@vinades.vn>',
    'note' => ''
];