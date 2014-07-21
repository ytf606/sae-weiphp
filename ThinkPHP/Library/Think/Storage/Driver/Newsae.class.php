<?php

/**
 * SAE Storage class 
 * 
 * @desc
 * 
 * @author 			ytf606 <ytf606@gmail.com,http://weibo.com/ytf606>
 * @package 		None
 * @version			$Id$
 */
namespace Think\Storage\Driver;
use Think\Storage;

// SAE环境文件写入存储类
class Newsae extends Storage{

    /**
     * 架构函数
     * @access public
     */
    private $contents   =   array();
    private $domain = '';
    private $storage = '';
    private $appname = '';
    private $_domain_list = array();
    public function __construct($options=array()) {
        $this->domain = isset($options['domain']) ? $options['domain'] : C('SAE_STORAGE');
        $this->storage = new \SaeStorage();
        if (!$this->storage || $this->storage->errno() != 0) {
            header('Content-Type:text/html;charset=utf-8');
            exit('您尚未开通Storage服务或Storage服务初始化异常');
        }
    }

    /**
     * 文件内容读取
     * @access public
     * @param string $filename  文件名
     * @return string
     */
    public function read($filename, $type='')
    {
        return $this->get($filename, 'content', $type);    
    }


    /**
     * 文件写入
     * @access public
     * @param string $filename  文件名
     * @param string $content  文件内容
     * @return boolean
     */
    public function put($filename, $content, $type='')
    {
        $content = time() . $content;
        $this->contents[$filename] = $content;
        return $this->fileWrite($filename, $content);
    }
    
    
    /**
     * 文件追加写入
     * @access public
     * @param string $filename  文件名
     * @param string $content  追加的文件内容
     * @return boolean
     */
    public function append($filename, $content, $type='')
    {
        if ($old_content = $this->read($filename, $type)) {
            $content = $old_content . $content;
        }
        return $this->put($filename, $content, $type);
    }

    /**
     * 加载文件
     * @access public
     * @param string $filename  文件名
     * @param array $vars  传入变量
     * @return void
     */
    public function load($filename, $vars=null)
    {
        !is_null($vars) and extract($vars, EXTR_OVERWRITE);
        eval('?>' . $this->read($filename));
    }

    /**
     * 文件是否存在
     * @access public
     * @param string $filename  文件名
     * @return boolean
     */
    public function has($filename,$type=''){
        if($this->read($filename,$type)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 文件删除
     * @access public
     * @param string $filename  文件名
     * @return boolean
     */
    public function unlink($filename, $type='')
    {
        return $this->fileDelete($filename);
    }


    /**
     * 读取文件信息
     * @access public
     * @param string $filename  文件名
     * @param string $name  信息名 mtime或者content
     * @return boolean
     */
    public function get($filename, $name, $type='')
    {
        if (!isset($this->contents[$filename])) {
            $this->contents[$filename] = $this->getContent($filename);
        }
        if (false === $this->contents[$filename]) {
            return false;
        }
        $info = array(
            'mtime' => substr($this->contents[$filename], 0, 10),
            'content' => substr($this->contents[$filename], 10),
        );
        return $info[$name];
    }


   /**
    * storage 原生包装获取错误码 
    * 
    * @desc
    * 
    * @access public
    * @return void
    * @exception none
    */
    public function errno()
    {
        return $this->storage->errno();
    }


    /**
     * storage 原生包装获取错误信息 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function errmsg()
    {
        return $this->storage->errmsg();
    }


    /**
     * storage 原生包装获取当前应用名 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function getAppname()
    {
        return $this->storage->getAppname();
    }
   

    /**
     * storage 原生包装获取当前domain列表 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function domainList()
    {
        return $this->storage->listDomains();
    }


    /**
     * storage 原生包装是否存在指定domain 
     * 
     * @desc
     * 
     * @access public
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function domainExists($domain='')
    {
        $domain_list = $this->domainList();
        return in_array($domain ? $domain : $this->domain, $domain_list);
    }


    /**
     * storage 原生包装获取指定domain得容量 
     * 
     * @desc
     * 
     * @access public
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function getDomainCapacity($domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->getDomainCapacity($domain);
    }


    /**
     * storage 原生包装获取指定domain得属性 
     * 
     * @desc
     * 
     * @access public
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function getDomainAttr($domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->getDomainAttr($domain); 
    }


    /**
     * storage 原生包装设置domain 
     * 
     * @desc
     * 
     * @access public
     * @param $domain
     * @return void
     * @exception none
     */
    public function domainSet($domain)
    {
        $this->domain = $domain;
        return $this->domain;
    }
   

    /**
     * storage 原生包装获取指定domain得文件列表 
     * 
     * @desc
     * 
     * @access public
     * @param $prefix=''
     * @param $limit=10
     * @param $offset=0
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function fileList($prefix='', $limit=10, $offset=0, $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->getList($domain, $prefix, $limit, $offset);
    }


    /**
     * storage 原生包装获取指定路径得文件数 
     * 
     * @desc
     * 
     * @access public
     * @param $path=''
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function fileNum($path='', $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->getFilesNum($domain, $path);
    }


    /**
     * storage 原生包装获取指定路径文件列表 
     * 
     * @desc
     * 
     * @access public
     * @param $path=''
     * @param $limit=10
     * @param $offset=0
     * @param $fold=true
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function fileListByPath($path='', $limit=10, $offset=0, $fold=true, $domain='')
    {
        $domain or $domain = $this->domain; 
        return $this->storage->getListByPath($domain, $path, $limit, $offset, $fold);
    }


    /**
     * storage 原生包装是否存在指定得文件 
     * 
     * @desc
     * 
     * @access public
     * @param $filename
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function fileExists($filename, $domain='')
    {
        $domain or $domain = $this->domain; 
        return $this->storage->fileExists($filename, $domain);
    }
   

    /**
     * storage 原生包装删除指定文件 
     * 
     * @desc
     * 
     * @access public
     * @param $filename
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function fileDelete($filename, $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->delete($domain, $filename);
    }


    /**
     * storage 原生包装获取文件得属性 
     * 
     * @desc
     * 
     * @access public
     * @param $filename
     * @param $attrKey=array(
     * @return void
     * @exception none
     */
    public function getFileAttr($filename, $attrKey=array(), $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->getAttr($domain, $filename, $attrKey);
    }


    /**
     * storage 原生包装获取指定文件得访问路径 
     * 
     * @desc
     * 
     * @access public
     * @param $filename
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function getUrl($filename, $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->getUrl($domain, $filename);
    }
  

    /**
     * storage 原生包装获取指定文件得cdn路径 
     * 
     * @desc
     * 
     * @access public
     * @param $filename
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function getCDNUrl($filename, $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->getCDNUrl($filename, $domain);
    }
  

    /**
     * storage 原生包装获取指定文件得内容 
     * 
     * @desc
     * 
     * @access public
     * @param $filename
     * @param $domain=''
     * @return void
     * @exception none
     */
    public function getContent($filename, $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->read($domain, $filename);
    }


    /**
     * storage 原生包装指定文件上传 
     * 
     * @desc
     * 
     * @access public
     * @param $des
     * @param $src
     * @param $attr=array(
     * @return void
     * @exception none
     */
    public function fileUpload($des, $src, $attr=array(), $compress=false, $domain)
    {
        $domain or $domain = $this->domain;
        return $this->storage->upload($domain, $des, $src, $attr, $compress);
    }


    /**
     * storage 原生包装向指定文件写入内容 
     * 
     * @desc
     * 
     * @access public
     * @param $des
     * @param $content
     * @param $size=-1
     * @param $attr=array(
     * @return void
     * @exception none
     */
    public function fileWrite($des, $content, $size=-1, $attr=array(), $compress=false, $domain='')
    {
        $domain or $domain = $this->domain;
        return $this->storage->write($domain, $des, $content, $size, $attr, $compress);
    }
}
