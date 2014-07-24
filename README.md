#sae-weiphp

基于SAE平台weiphp CMS系统

##sae-weiphp

###简介
sae-weiphp是基于weiphp遵循Apache Licence2协议移植到SAE上，移植属于个人爱好，无任何商业性能，以此难免存在一定的问题，如有问题请留言微博[@ytf606](http://weibo.com/2135696647)或者邮件<a href="mailto:ytf606@gmail.com">ytf606@gmail.com</a>，非常感谢您的支持和帮助。

*   sae-weiphp v1.0.0版本是基于weiphp官方2.0进行移植，所有插件均未作改动

*   sae-weiphp支持一键安装，如果您不是通过应用仓库或应用导入创建的方式的话，请保证您的应用已经开启了mysql、storage(创建一个名为weiphp的domain)、memcache(至少1M)

*   sae-weiphp现在版本可以正常的跑在SAE上但暂不保证同时兼容了本地(稍后要做的)

###Install
可以通过以下两种方法安装

1、直接通过[应用仓库](http://sae.sina.com.cn/?m=apps&a=detail&aid=163)安装，这是最简单的方法

2、通过用户首页=》控制台=》导入应用，在“基于URL安装”中输入<http://ytf606-ytf606.stor.sinaapp.com/SDK/sae-weiphp.zip>或者自己下载源码打成zip(zip内不要有多余的文件夹),点击安装到以上位置即可

3、已经创建好了应用，需要手动开启memcache(1M以上)、storage(domain为weiphp)以及mysql服务,然后通过svn导入代码即可

PS:导入安装和仓库安装，有可能由于多种网络原因可能会报mysql错误，这种情况只要到后台确定mysql服务是否开启。如果开启，就直接无视此错误，否者手动开启msyql，访问首页即可。


###常量
1.  默认storage的domain：weiphp

2.  数据库默认前缀：sae_

3.  前后台默认登陆用户名：admin

4.  前后台默认登陆密码：admin

5.  数据库主库：SAE_MYSQL_HOST_M

6.  数据库从库：SAE_MYSQL_HOST_S

7.  数据库用户名：SAE_MYSQL_USER

8.  数据库密码：SAE_MYSQL_PASS

9.  数据库名：SAE_MYSQL_DB

10. 数据库端口：SAE_MYSQL_PORT

11. 缓存路径：SAE_TMP_PATH

###已经完成的移植
1、一键安装移除不必要提示，为了便于安装暂时从代码级别固定部分参数

2、添加SAE的整体配置到惯例配置、修改应用配置配置SAE相关内容
   
3、移除原weiphp中sae模式的storage，改成SAE平台中storage相关接口
   
4、修改文件上传功能，上传文件到storage中，同时修改读取文件的从storage中读取
   
5、数据库的备份操作，数据备份到storage中恢复也从storage中恢复
   
6、修改原始安装程序sql多余部分数据导致左侧菜单栏无法显示问题

###TODO
1、添加SAE自由的K-V数据库服务
   
2、修改部分安装参数到执行数据库，提高安全性和便捷性
   
3、添加其他weiphp所需要的服务
   
4、发现并修复weiphp在SAE上兼容问题

##SAE
[Sina App Engine](http://sae.sina.com.cn)（简称SAE）是新浪研发中心于2009年8月开始内部开发，并在2009年11月3日正式推出第一个Alpha版本的国内首个公有App Engine，SAE是新浪云计算战略的核心组成部分。

SAE是一个公有的PAAS平台，平台为了安全和分布式部署禁止本地IO的写操作，因此一般需要向本地写文件的框架、CMS等系统不能直接在SAE上运行，需要经过移植才能正常运行，通用的移植方法可以参考[这里](http://blog.sina.com.cn/s/blog_73b89cd30101230u.html)

SAE提供了丰富的服务，包括storage(存储)、mysql(关系数据库)、kvdb(K-V数据库)、fetchurl(抓取)、cron(定时任务)等，具有高可靠、高扩展、高性能的PAAS云平台

##weiphp
[weiphp](http://www.weiphp.cn)是一款简洁而强大的开源微信公众平台开发框架，遵循Apache Licence2开源协议，并且免费使用（但不包括其衍生产品、插件或者服务）。weiphp提供了丰富的插件功能，包括：智能聊天、微信会员、宣传卡、投票、刮刮卡、微测试等等，可以帮助您快速的搭建自己的微信公共运营平台

weiphp底层基于Thinkphp框架开发以及onethink代码、插件以及维护有众多的开发者提供和校验，功能日趋完善。
  
##反馈
*  新浪微博：[@ytf606](http://weibo.com/2135696647)

*  邮箱：<a href="mailto:ytf606@gmail.com">ytf606@gmail.com</a>

*  您也可以直接在此建立issue

##Code
*  github [sae-weiphp](https://github.com/ytf606/sae-weiphp)
