<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 凡星
// +----------------------------------------------------------------------
namespace Admin\Controller;

/**
 * 在线升级控制器
 */
class UpdateController extends AdminController {
	public function index() {
		// 获取官方升级信息
		$url = 'http://www.weiphp.cn/index.php?s=/home/index/update_json&version=' . intval ( C ( 'SYSTEM_UPDATRE_VERSION' ) );
		
		$list = wp_file_get_contents ( $url );
		$list = json_decode ( $list, true );
		// dump ( $list );
		
		$this->assign ( '_list', $list );
		$this->display ();
	}
	function deal_sql() {
		$path = SITE_PATH . '/update_db_tool.php';
		if (! file_exists ( $path )) {
			$this->error ( '升级文件不存在，请先把升级文件update_db_tool.php放置在  ' . SITE_PATH . ' 目录下' );
		}
		
		require_once $path;
	}
	function getRemoteVersion() {
		// cookie ( 'cookie_close_version', 0 );
		$remote = 'http://www.weiphp.cn/index.php?s=/home/index/update_version';
		$new_version = wp_file_get_contents ( $remote );
		$res = $new_version > C ( 'SYSTEM_UPDATRE_VERSION' ) && cookie ( 'cookie_close_version' ) != $new_version;
		echo $res ? $new_version : 0;
	}
	// 获取关闭升级提醒
	public function set_cookie_close_version() {
		$cookie_close_version = intval ( $_GET ['cookie_close_version'] );
		cookie ( 'cookie_close_version', $cookie_close_version );
	}
	// 清空缓存
	function delcache() {
		$cahce_dirs = RUNTIME_PATH;
		$this->rmdirr ( $cahce_dirs );
		
		@mkdir ( $cahce_dirs, 0777, true );
		$this->display ();
	}
	function rmdirr($dirname) {
		if (! file_exists ( $dirname )) {
			return false;
		}
		if (is_file ( $dirname ) || is_link ( $dirname )) {
			return unlink ( $dirname );
		}
		$dir = dir ( $dirname );
		if ($dir) {
			while ( false !== $entry = $dir->read () ) {
				if ($entry == '.' || $entry == '..') {
					continue;
				}
				$this->rmdirr ( $dirname . DIRECTORY_SEPARATOR . $entry );
			}
		}
		$dir->close ();
		return rmdir ( $dirname );
	}
}
