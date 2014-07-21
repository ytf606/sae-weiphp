<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Install\Controller;
use Think\Controller;
use Think\Db;
use Think\Storage;

class SaeInstallController extends Controller
{
	protected function _initialize(){
		if(session('step') === null){
			$this->redirect('Index/index');
		}

		if(Storage::has(MODULE_PATH . 'Data/install.lock')){
			$this->error('已经成功安装了WeiPHP，请不要重复安装!');
		}
	}
    
    public function index(){
	    Storage::domainSet(C('SAE_STORAGE'));	
        if(Storage::has(MODULE_PATH . 'Data/install.lock')){
			$this->error('已经成功安装了WeiPHP，请不要重复安装!');
		}
       
        //环境检测
		$env = check_env();

		//目录文件读写检测
		if(IS_WRITE){
			$dirfile = check_dirfile();
			$this->assign('dirfile', $dirfile);
		}

		//函数检测
		$func = check_func();

		$this->assign('env', $env);
		$this->assign('func', $func);
        
        //检测管理员信息
        $sae_info = C('SAE');
        $info = array();
        list($info['username'], $info['password'], $info['email'])
        = array_values($sae_info['admin']);

        //检测数据库配置
        $DB = array();
        list($DB['DB_TYPE'], $DB['DB_HOST'], $DB['DB_NAME'], $DB['DB_USER'], $DB['DB_PWD'],
             $DB['DB_PORT'], $DB['DB_PREFIX']) = array_values($sae_info['database']);
        
        //创建数据库
        $dbname = $DB['DB_NAME'];
        
        $db  = Db::getInstance($DB);
        $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
        $db->execute($sql) || $this->error($db->getError());
        
		$this->display();
		
        //创建数据表
		create_tables($db, $DB['DB_PREFIX']);

		//注册创始人帐号
        $auth = $sae_info['auth_key'];
		register_administrator($db, $DB['DB_PREFIX'], $info, $auth);

		//创建配置文件
		$conf 	=	write_config($DB, $auth);
		
        Storage::put(MODULE_PATH . 'Data/install.lock', 'lock');

		//创建配置文件
		$this->assign('info', $conf);
    }

    //查看用户协议
    public function protocol()
    {
		$this->display();
    }

    public function saeEnv()
    {
        //环境检测
		$env = check_env();

		//目录文件读写检测
		if(IS_WRITE){
			$dirfile = check_dirfile();
			$this->assign('dirfile', $dirfile);
		}

		//函数检测
		$func = check_func();

		$this->assign('env', $env);
		$this->assign('func', $func);
        $this->display();
    }
    
    	//安装第一步，检测运行所需的环境设置
	public function step1(){
		session('error', false);

		//环境检测
		$env = check_env();

		//目录文件读写检测
		if(IS_WRITE){
			$dirfile = check_dirfile();
			$this->assign('dirfile', $dirfile);
		}

		//函数检测
		$func = check_func();

		session('step', 1);

		$this->assign('env', $env);
		$this->assign('func', $func);
		$this->display();
	}

	//安装第二步，创建数据库
	public function step2($db = null, $admin = null){
		if(IS_POST){
			//检测管理员信息
			if(!is_array($admin) || empty($admin[0]) || empty($admin[1]) || empty($admin[3])){
				$this->error('请填写完整管理员信息');
			} else if($admin[1] != $admin[2]){
				$this->error('确认密码和密码不一致');
			} else {
				$info = array();
				list($info['username'], $info['password'], $info['repassword'], $info['email'])
				= $admin;
				//缓存管理员信息
				session('admin_info', $info);
			}

			//检测数据库配置
			$DB = array(
                	'DB_TYPE'			=> 	C('DB_TYPE'),     // 数据库类型
					'DB_HOST'			=> 	C('DB_HOST'), // 服务器地址
					'DB_NAME'			=> 	C('DB_NAME'),        // 数据库名
					'DB_USER'			=> 	C('DB_USER'),    // 用户名
					'DB_PWD'			=> 	C('DB_PWD'),         // 密码
					'DB_PORT'			=> 	C('DB_PORT'),        // 端口
    				'DB_PREFIX'         =>  C('DB_PREFIX'),
            );
            //缓存数据库配置
			session('db_config', $DB);

			//创建数据库
			$dbname = $DB['DB_NAME'];
			unset($DB['DB_NAME']);
			$db  = Db::getInstance($DB);
			$sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8";
			$db->execute($sql) || $this->error($db->getError());

			//跳转到数据库安装页面
			$this->redirect('step3');
		} else {
			session('error') && $this->error('环境检测没有通过，请调整环境后重试！');

			$step = session('step');
			if($step != 1 && $step != 2){
				$this->redirect('step1');
			}

			session('step', 2);
			$this->display();
		}
	}

	//安装第三步，安装数据表，创建配置文件
	public function step3(){
		if(session('step') != 2){
			$this->redirect('step2');
		}

		$this->display();

		//连接数据库
		$dbconfig = session('db_config');
		$db = Db::getInstance($dbconfig);

		//创建数据表
		create_tables($db, $dbconfig['DB_PREFIX']);

		//注册创始人帐号
        $auth  = C('DATA_AUTH_KEY');
		$admin = session('admin_info');
		register_administrator($db, $dbconfig['DB_PREFIX'], $admin, $auth);

		//创建配置文件
		$conf 	=	write_config($dbconfig, $auth);
        session('config_file',$conf);
		if(session('error')){
			show_msg();
		} else {
			session('step', 3);
			$this->redirect('Index/complete');
		}
	}
}
