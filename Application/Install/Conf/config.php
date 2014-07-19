<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 安装程序配置文件
 */

define('INSTALL_APP_PATH', realpath('./') . '/');



return defined('SAE_TMP_PATH') ? array(
    
    'ORIGINAL_TABLE_PREFIX' => 'wp_', //默认表前缀

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),

    /* SAE TODO */
    'SAE' => array( 
        'admin' => array(
            'username' => 'admin',
            'password' => 'admin',
            'email' => 'ytf606@gmail.com'
        ),
        'database' => array(
            'type' => 'mysqli',
            'host' => SAE_MYSQL_HOST_M,
            'DB' => SAE_MYSQL_DB,
            'user' => SAE_MYSQL_USER,
            'pass' => SAE_MYSQL_PASS,
            'port' => SAE_MYSQL_PORT,
            'prefix' => 'sae_',
        ),
        'auth_key' => 'd!CfIv#ZA(uW6O|r&*sM?:>"K[l3znX`R}otj.pe',
    ),

) : array(
     
    'ORIGINAL_TABLE_PREFIX' => 'wp_', //默认表前缀

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
    ),
);
