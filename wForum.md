
## <a id="maintitle"></a>wForum 文档

* * *


**目录**


1. [wForum 简介](#intro)

.1. [版权声明](#intro_copyright)

2. [wForum 安装说明](#install)


2.1. [smthbbs 安装](#install_smthbbs)

2.2. [安装 wForum](#install_wforum)

2.2.1. [default.php 内部分参数的附加说明](#feature_notes)

2.3. [自定义头像功能](#install_uploadface)

2.4. [加入 mysql 支持和小字报功能](#install_mysql)

2.5. [加入 itex2mathml 支持](#install_tex2mathml)

2.6. [附加说明](#install_append)

2.7. [smthbbs 系统升级安装 wForum 说明](#upgrade_smthbbs)

2.8. [wForum 和 smthbbs 的 web 界面配合](#bbs2www)


3. [技术细节](#tech_details)


3.1. [Javascript 方面的一些说明](#javascript)

3.2. [wForum 对文章列表部分的处理](#thread_notes)


3.2.1. [文章阅读界面跳转到上一主题/下一主题的实现](#prev_next_thread)

3.2.2. [关于同主题模式的利弊](#thread_notes_1)


3.3. [关于树形回复结构](#reply_tree)

3.4. [分区版面列表](#section_list)

3.5. [inc/funcs.php 代码分析](#funcs_php)

3.6. [itex2mathml 的一些技术细节](#itex2mathml_details)

3.7. [USEBROWSCAP 站点参数（这个功能目前关闭）](#browscap)

3.8. [wForum 框架结构支持](#bbsleft)

3.9. [一些杂事](#misc)


## <a name="intro"></a>1. wForum 简介

*   wForum 是基于高性能的水木清华BBS源码（一万五千人同时在线）的高性能论坛系统；
*   主页：[http://wforum.aka.cn/](http://wforum.aka.cn/)；
*   推荐使用 linux 系统 (redhat)；
*   讨论区：[http://bbs.zixia.net/board.php?name=wForum](http://bbs.zixia.net/board.php?name=wForum)
### 1.1. 版权声明

*   这份软件基于 GPL 版本发布。请参考 COPYING.GPL 文件。任何对本软件代码的修改需要同样遵循 GPL 许可。
*   版权归属 阿卡信息技术(北京)有限公司 和 水木清华 BBS 共同所有。
*   额外声明：未经北京阿卡公司书面许可，不允许将本软件作商业应用。
## <a name="install"></a>2. wForum 安装说明

首先到 [smthbbs 开发主页](http://dev.smth.org/) 下载最新的 smthbbs 和 wForum 代码。请一定注意 wForum 必须配合同一个时间点 smthbbs 的 CVS 版本或者 snapshot 版本使用，比如下载同一天的 snapshot 就不会有问题，如果最新 wForum snapshot 配合 smthbbs 1.2.1 使用就会有这样那样的小问题。另外，wForum 需要 PHP 支持 gd、dom。

### <a name="install_smthbbs"></a>2.1. smthbbs 安装

smthbbs 安装请参考其安装文档；必须打开 WWW 支持。站点文件请参考 site/zixia.h 和 site/zixia.c。

原先安装了 smthbbs 系统的站点需要升级 .PASSWDS 文件。请参考 [smthbbs 系统升级安装 wForum 说明](#upgrade_smthbbs)。


### <a name="install_wforum"></a>2.2. 安装 wForum

解压 wForum 到 web 根目录下面的 wForum 目录。给你的站点取个名字，比方叫 kcn。进入 wForum 目录，执行下面几个命令：

```bash
cp inc/sites/site-example.php inc/sites/kcn.php
ln -s inc/sites/kcn.php inc/site.php</pre>
```
				inc/site.php 就是你的站点定义文件。请注意，inc/site.php 必须是一个指向 inc/sites/ 目录下某个文件的符号连接，否则程序会找不到系统默认参数配置文件 default.php。修改 inc/site.php 里面的各类参数，特别是分类讨论区。另外，inc/reg_txt.php 是注册用户的时候看到的网站协议，可以换成站点站规。


#### <a name="feature_notes"></a>2.2.1. default.php 内部分参数的附加说明

inc/sites/default.php 列举了可以在 inc/site.php 里定义的参数。大多数参数说明已经足够明确，下面对个别参数作附加说明。


*   $SiteURL: 这个变量用于一些需要输出绝对路径的地方，应该定义为 wForum 安装的根的 URL，最后一个字符必须是 '/'。

*   MAINTITLE: 站点标题可以包含 html，打开 mathml 支持的站点千万注意 html 必须符合 xhtml 标准。

*   ONBOARD_USERS: 光打开这个参数只能让有 SYSOP 权限的用户看到版面在线名单，如果想让普通用户也看到，还需要在 src/site.h 内定义：

    <pre class="programlisting">#define ALLOW_PUBLIC_USERONBOARD 1</pre>

对于访问量大但没有安装 squid 的站点，不建议开启这个功能。

### <a name="install_uploadface"></a>2.3. 自定义头像功能

如果开启自定义头像功能，确认一下 wForum 主目录下面有 uploadFace/ 目录，如果没有的话就建立一下。注意这个目录必须是 httpd 运行身份的用户（一般建议用 bbs 用户）可写的。


### 自定义头像的更多细节

自定义头像的存放位置是 wForum 主目录下面的 uploadFace/ 目录。在备份和转移 BBS 数据的时候也要备份这个目录。有人可能会希望 BBS 的数据全部都在 ~bbs/ 目录下面以方便备份，这是可行的。下面提供一个方法：


1.  在 ~bbs/ 目录下建立 uploadFace 目录。

2.  在 wForum 主目录下面删除 uploadFace 目录，并且建立一个符号连接：

    <pre class="programlisting">ln -s ~bbs/uploadFace uploadFace</pre>

3.  确认在 httpd.conf 中，wForum主目录允许 FollowSymLinks，例如如下：

```bash
&lt;Directory &quot;/var/www/html/wForum&quot;&gt;
    Options FollowSymLinks
&lt;/Directory&gt;</pre>
```bash
    							具体的细节请参考 apache 的配置说明。注意：允许 FollowSymLinks 会有潜在的安全问题。

### <a name="install_mysql"></a>2.4. 加入 mysql 支持和小字报功能

目前 wForum 的 mysql 支持仅用于版面的小字报功能，默认是关闭的。正确加入 mysql 支持之后小字报功能就会自动打开。mysql 支持首先要求 PHP 支持 mysql。在 site.php 加入：

```php
define(&quot;DB_ENABLED&quot;, true);
```

以打开 mysql 支持，同时配置 mysql 用户、密码和数据库名。用如下语句在 mysql 中建立一个数据表来支持小字报功能：

```sql
create table smallpaper_tb(
 ID int    not null auto_increment primary key,
 boardID   int not null,
 Owner     varchar(14) not null,
 Title     varchar(100) not null,
 Content   mediumtext   not null,
 Hits      int not null,
 Addtime   datetime not null
);
```

			如果安装或者配置不正确，可能导致版面文章列表的页面出错。如果出错，可以使用 SYSOP 权限的用户登录之后打开 admin.check_mysql.php 页面判断可能的错误。


### <a name="install_tex2mathml"></a>2.5. 加入 itex2mathml 支持

现在 wForum 支持 itex（数学公式排版语言 tex 的一种衍生形式） 到 mathml （浏览器显示数学公式的推荐标准）的自动转化。这部分功能参考 ythtbbs 系统完成，部分使用 ythtbbs 代码的站点有这个功能的介绍可以参考，比如 [笔山书院 BBS](http://bbs.qxntc.edu.cn/) web 下的用户帮助里面就有。

wForum 支持 itex2mathml 对客户端和服务器端都有要求。客户端的要求是最新版的 Mozilla Firefox，Netscape 等支持 MathML 的浏览器。如果是 MSIE 浏览器的话，必须是 5.5 版以上，而且需要预装 [MathPlayer 2.0](http://www.dessci.com/en/products/mathplayer/download.htm) 插件，wForum 不会提示用户安装。服务器端要求 [itex2mml](http://pear.math.pitt.edu/mathzilla/itex2mml.html) 转换程序，请下载后将 itex2MML 可执行文件放在 ~bbs/bin/ 目录下面，并且在 site.php 里面加入：

```php
define(&quot;SUPPORT_TEX&quot;, true);
```

用户发表需要 itex2mathml 支持的帖子的时候要选择“使用 tex 发表”选项。关于 itex2mml 支持的 tex 格式可以在其主页上找到文档。目前这部分功能还有很多问题，但是基本功能已经可以使用。具体的技术细节可以参考下面的 [itex2mathml 的一些技术细节](#itex2mathml_details)。


### <a name="install_append"></a>2.6. 附加说明

1.  httpd.conf 最好设置 wForum 所在的目录 index.php 也是 DirectoryIndex。可以在 httpd.conf 里面设置

    ```bash
    DirectoryIndex index.html index.html.var index.php
    ```bash

2.  php.ini 要关掉自动给引号加前导反斜杠，并且禁止 PHP 代码开始标志的缩写形式：

    ```bash
	magic_quotes_gpc = off
	short_open_tag = off
	```bash

    					对于服务器前端没有 squid 加速的站点，建议在 php.ini 内设置：

    ```bash
    output_buffering = 65536
    ```

这个设置的作用是强制让 PHP 缓存页面输出，对于慢网速，大页面的输出，脚本执行可能会有很大一部分时间用于网络 I/O 的等待上，打开 output_buffering 可以有效地提高页面执行的效率，加强服务器的响应能力，但可能会稍稍增加一些服务器的负荷。

3.  要保证 wForum 下的未读记录正常工作，你的 site.h 必须设置

    ```php
	#define USE_TMPFS   1       /*使用内存文件系统加速*/
	#define TMPFSROOT   &quot;cache&quot; /*tmpfs的根在bbshome/cache */
	```

    					如果 USE_TMPFS 原来是 0，修改后重新编译安装。同时在 ~bbs/ 下建立 cache 目录，重启动服务。可以在 ~bbs/cache/ mount 一个 tmpfs 文件系统提高性能。推荐 USE_TMPFS 定义成 1，否则的话两个 telnet 登录之间的未读记录都不会同步。

4.  PHP 编译说明: wForum 需要 PHP 支持 gd、dom 及 mysql。如果你是自己编译 PHP，首先，确认你的 linux 系统已安装了 mysql、gd、libjpeg、libpng、freetype、libxml2 及各自相应的 devel 包，然后用下面的参数编译 PHP（假设 apache 1.3.x）：

    ```bash
	./configure  --prefix=/bbs/www --with-freetype-dir=/usr/lib --with-gd
 		--with-jpeg-dir --with-png-dir --with-config-file-path=/bbs/www/conf
 		--with-zlib-dir --with-mysql --with-apxs=/bbs/www/bin/apxs --with-dom
 	```

5.  如果使用 apache2 需要注意的地方：

    
        *   php configure 命令中 --with-apxs 改成 --with-apxs2

        *   httpd.conf 建议加入 AddDefaultCharset gb2312
6.  wForum 系统会自动显示每封信件的大小，但是如果是从 2004 年 8 月以前的系统升级，以前旧的信件的大小需要用 local_utl/sync_mailsize 程序同步一下。这个程序加 -a 参数就会同步所有的信件大小。

7.  如果 wForum 是从 2004 年 6 月以前的代码升级的，推荐升级之后删除 ~bbs/boards/ 目录下所有的 .WEBTHREAD 强制让系统重新生成索引。可以在 ~bbs/boards/ 下执行这样一个命令：

    ```bash
    ls -d1 * | awk '{system(sprintf(&quot;rm %s/.WEBTHREAD&quot;, $1));}'
    ```bash
### <a name="upgrade_smthbbs"></a>2.7. smthbbs 系统升级安装 wForum 说明

1.  原 smthbbs 系统要新加入 wForum 支持，首先需要在 site.h 中定义 HAVE_WFORUM：

    ```c
    #define HAVE_WFORUM 1
    ```

    					如果您的系统原来就已经定义了这个，那么，恭喜你，你的系统已经支持 wForum 了，不需要进行下面的操作。

2.  这里的升级说明比较简略，因为假设读者已经相当熟悉 smthbbs 系统。升级系统需要停掉所有的 BBS 服务（包括共享内存），重新编译安装所有的 BBS 程序。接下来，需要转换 ~bbs/.PASSWDS 文件：拷贝一份为 ~bbs/.PASSWDS.OLD，打开转换程序 contrib/smth2wforum/convpasswd.c 看老的 userec 结构是否正确，然后编译运行之（这个程序如果编译有困难可以覆盖掉 local_utl/ 下面某个无用程序来编译）。然后用 ~bbs/.PASSWDS.NEW 覆盖 .PASSWDS。这个转换程序除了可能的结构转换还会设置两个用户自定义选项的默认值，默认是允许显示详细用户数据，不允许显示真实用户数据。

    全部转换工作就此完成。此时按正常方式启动系统。
### <a name="bbs2www"></a>2.8. wForum 和 smthbbs 的 web 界面配合

wForum 和 smthbbs 源代码 bbs2www/html/ 下那个 web 界面可以配合使用，互为补充。特别说明的两点是：第一，bbs2www/html/bbslogin.php 和 wForum/logon.php 所接受的登录表单格式是完全兼容的，所以可以在 web 首页放一个 checkbox，用同一个用户名/密码输入表单就可以让用户选择是登录到哪一个 web 界面。第二，两个 web 界面允许共享登录 cookie，这样可以在两个界面之间跳转而不必重新登录（可以在两个界面适当的地方加跳转链接）。要实现这个功能需要将两个 web 界面装在同一个域名下，wForum 可以放在 web 主目录的 wForum/ 目录下，然后在 wForum 的 inc/site.php 中定义：

```php
define('COOKIE_PREFIX', '');
define('COOKIE_PATH', '/');
```

				如果两个 web 界面在同一个域名下的不同子域名，共享 cookie 还是可能的。wForum 留出了定义 COOKIE_DOMAIN 常量的接口，如果配置 COOKIE_DOMAIN 常量请一定完全搞明白这样做可能带来的安全问题。


## <a name="tech_details"></a>3. 技术细节

### <a name="javascript"></a>3.1. Javascript 方面的一些说明

1.  mozilla 对 class、ID 等属性是大小写敏感的；而 IE 是大小写不敏感的。在设置和应用样式单时要注意 class 的大小写。

2.  browser.js 文件主要函数、变量及功能:

变量: isIE4


    浏览器是否兼容 IE4
变量: isW3C


    浏览器是否兼容 W3C 标准（mozilla or IE5）。如果浏览器支持 W3C 标准，以下函数将会优先使用 W3C 标准函数以避免 bug。
函数：getRawObject(obj)


获取ID为obj的对象。例:

```javascript
.....
&lt;input type=&quot;button&quot; name=&quot;clickIt&quot; id=&quot;clickIt&quot; value=&quot;I'm button&quot;&gt;
.....
&lt;script language=&quot;javascript&quot;&gt;
...
oButton=getRawObject(&quot;clickIt&quot;);
oButton.value=&quot;I'm new button&quot;;
...
&lt;/script&gt;
.....
```

函数：getParentRawObject(obj)


    用于子frame;获取父窗口中ID为obj的对象。
函数：getRawObjectFrom(obj,oFrame)


    从oFrame中获取ID为obj的对象。
函数: getObjectCollection(obj)


获取ID/Name为obj的一组对象。例:

```javascript
.....
&lt;input type=&quot;checkbox&quot; name=&quot;checkMe&quot; id=&quot;checkMe&quot; value=&quot;0&quot;&gt;checkbox0
&lt;input type=&quot;checkbox&quot; name=&quot;checkMe&quot; id=&quot;checkMe&quot; value=&quot;1&quot;&gt;checkbox1
&lt;input type=&quot;checkbox&quot; name=&quot;checkMe&quot; id=&quot;checkMe&quot; value=&quot;2&quot;&gt;checkbox2
.....
&lt;script language=&quot;javascript&quot;&gt;
...
oCheckboxs=getObjectCollection(&quot;CheckMe&quot;);
for (i=0;i&lt;oCheckboxs.length;i++) {
	alert(oCheckbox[i].value);
}
...
&lt;/script&gt;
.....
```

    
函数: getParentObjectCollection(obj)


    获取父窗口ID/Name为obj的一组对象。

函数: getObjectCollectionFrom(obj,oFrame)


    从oFrame中获取ID/Name为obj的一组对象。

函数: getObjectStyle(obj)


获取ID为obj的对象的style。例:

```javascript
menuDivStyle=getObjectStyle(&quot;menuDiv&quot;);
menuDivStyle.visible=&quot;hidden&quot;;
```

    
函数: shiftTo(obj, x, y)


将ID为obj的对象移动到坐标(x,y)。例:

    <pre class="programlisting">shiftTo(&quot;menuDiv&quot;,0,0);</pre>

    
函数: shiftBy(obj, deltaX, deltaY)


    将ID为obj的对象移动(deltaX,deltaY)。

函数: setZIndex(obj, zOrder)


    将ID为obj的对象的zIndex值设为zOrder。

函数: hide(obj) show(obj)


    隐藏/显示ID为obj的对象。

函数: getObjectLeft(obj) getObjectTop(obj) getObjectWidth(obj) getObjectHeight(obj)


    获取ID为obj的对象的坐标和长宽等。


3.  目前 wForum 的 javascript 事件处理比较混乱，不同的 .js 文件都会设置全局的事件监听。比方，inc/floater.js 里面设置了 onmouseup onmousedown onmousemove 事件监听，inc/funcs.js 则会设置 onmouseover，而页面头也带有 &lt;body ... onmouseover=&quot;HideMenu(event);&quot;&gt;。这些在以后开发中如果不注意，很容易产生冲突。现在这部分标准尚未统一，所以只在 inc/funcs.js 写了一个变通处理多个监听 window.onload 事件的方法作示范，别的地方切莫再自己设置 window.onload 事件监听，只需调用下列函数。

    
函数: addOnLoadListener(listener)


    添加 window.onload 事件监听的语句，listener 是一个字符串，是具体的 javascript 代码，而不是函数指针。

函数: registerFocusOnLoad(ele)


    将 id 为 ele 的元素注册成页面载入完毕时设置的焦点元素。注意 ele 是字符串，不是具体控件的引用。


### <a name="thread_notes"></a>3.2. wForum 对文章列表部分的处理

现在的 wForum 文章列表是基于同主题的，列表时只列出同主题首篇文章；列表按照同主题最后一篇文章的发文时间降序排序，有分页功能。这部分处理主要由 phpbbslib.c 中的下面几个函数来完成：


bbs_getthreadnum(string boardName)


该函数求某版面下的主题数量。这个函数首先比较 .DIR 和 .WEBTHREAD 文件的时间戳，如果 .WEBTHREAD 旧于 .DIR 则调用 bbslib.c 中的www_generateOriginIndex() 函数重新生成 .WEBTHREAD 文件（似乎需要一个更好的判断方式来触发更新 .WEBTHREAD），然后直接由 .WEBTHREAD 的文件长度求记录数目。

其主要算法是：首先建立一标志数组，在其中存放已找到的groupID。这个标志数组有两个内部数据结构：第一个是链表，按照每个主题最后发文时间排序，最后可以直接顺序写入 .WEBTHREAD。另一个结构是平衡二叉树，目的是方便快速搜索 groupID。程序倒序遍历 .DIR 中的记录，如果其 groupID 不在标志数组中，则加入标志数组。在遍历过程中同时判断该帖子是否是原文，如果是，同时需要将原文信息复制进标志数组。最后生成 .WEBTHREAD 的时候是按照链表顺序写，判断该主题是否有原文，如果没有，作一些特殊处理再写入 .WEBTHREAD 以便到时候显示原文是否被删除。关于这里是否有原文的处理细节，参见代码。


bbs_getthreads(string boardName, int start, int num)


该函数按同主题最后一篇文章的发文时间降序排列，取从 start 开始的 num 篇主题信息。这个函数充分利用已经产生的 .WEBTHREAD 文件来操作。


#### <a name="prev_next_thread"></a>3.2.1. 文章阅读界面跳转到上一主题/下一主题的实现

基本思路：大部分文章阅读链接加入 pos 参数指示该主题在 .WEBTHREADS 索引中的位置，页面内上/下主题的链接同时带入 groupID 参数指示当前贴子的 groupID。处理上/下主题跳转的脚本用 groupID 验证 pos 位置的主题是否还仍旧是这个主题，如果不是的话说明版面索引发生变化，直接返回错误，并提示返回版面；否则就从位置 pos +/- 1 取出上/下主题 groupID。当主题索引发生变化时原则上可以在 .WEBTHREADS 中线性查找重新定位，但返回错误的原因是因为此时（按照最后回复顺序排列的）上/下主题的意义可能已经发生变化，

两个问题：1、php 脚本内多次调用 bbs 库函数，中间有时间差，原则上有漏洞，但，可能性不大；2、可能出现 pos 位置 groupID 没有变但版面索引 .WEBTHREADS 已经发生变化的情况，可能性不大。


#### <a name="thread_notes_1"></a>3.2.2. 关于同主题模式的利弊

同主题模式和公网论坛比较一致但是和传统 telnet 配合的时候可能会有不一致的地方。以下几个问题是特别需要注意的：


*   删除了原文的置顶不能阅读；

*   一个主题是否显示成精华，不可re，and/or 置顶仅仅是判断原文是否有这个属性。特别的，如果一个re文被g，这个主题不会自动成为精华贴。类似的，一片re文被设置为不可re，这个主题不会显示为不可re；但是如果原文被设置成不可re，这个主题会显示为不可re(当然这个标志只有版主能看见)不过所有已有的re文还都是可re的。这里还有不少细节问题。
					这个以后可能会改。


### <a name="reply_tree"></a>3.3. 关于树形回复结构

loadtree.php (版面文章列表点击 + 会显示回复结构) 和 disparticle.php (单篇文章阅读底部会显示回复结构) 已经加入了树形回复结构的支持。基础代码在 inc/treeview.inc.php 里面。产生回复树结构再递归显示其实是一件比较耗时间的操作，这部分代码以后可能还需要再优化。现在如果回复超过一定的数量(默认 51，可以在 site.php 里面配置)，就不显示回复树结构而直接显示平板式回复结构。另外，smthbbs 现在设置的一个主题可以返回的最大回复数量是 512。这里其实还是有文章可做，比方回复树里面显示每一个帖子是否已读，不过暂时先不考虑了。

default.php 里面定义了 SHOWREPLYTREE，如果为 1 就会支持树形回复结构。如果为 0 就是平级显示所有回复。这里也有文章可做，SHOWREPLYTREE 其实定义成用户自定义参数或者 cookie 参数可能更好。


### <a name="section_list"></a>3.4. 分区版面列表

分区版面列表采用 JS 写页面。展开列表可能需要隐藏 iframe 读数据，折叠列表直接使用已有的 js 变量重写页面。列表是否展开由一个 ShowSecBoards cookie 控制。这个 cookie 变量的最低一个 bit 表示收藏夹版面列表是否展开，第(i+1) bit 表示第 i 个分区是否展开。二级目录永远展开。

列表也可以完全隐藏。这个由 HideSecBoards cookie 控制。LSB 没有作用。第 (i+1) bit 表示第 i 个分区的版面是否完全隐藏。收藏夹不可以被隐藏。

这两个 cookie 全部由客户端设置，服务器端只读取（除了特殊情况设置初始值）。这两个 cookie 的默认值：ShowSecBoards 是 0，HideSecBoards 可配置为 0 或者 ~0。

JavaScript 变量


*   &quot;foldflag&quot; + i 表示第 i 个分区（或者 select == i 的收藏夹）的数据状态:

    
        *   0: 当前还没有任何版面数据。

        *   1: 当前有折叠版面列表所必需的所有数据。

        *   2: 当前已经有所有用作显示的版面数据了。
*   &quot;curfold&quot; + i 表示第 i 个分区（或者 select == i 的收藏夹）的折叠状态:

    
        *   0: 隐藏。

        *   1: 折叠版面列表。

        *   2: 详细版面列表。
### <a name="funcs_php"></a>3.5. inc/funcs.php 代码分析

1.  可引用的全局变量。这些变量在 include 之后生效

    
$loginok


    是否以正式用户登录了，guest 登录的话为 0。

$guestloginok


    当前是否以 guest 身份登录了。

$currentuser


    当前用户信息的数组，请注意 (!$loginok &amp;&amp; !$guestloginok) 情况下该变量无效。该数组的元素请参考 phpbbslib.c 内 assign_user() 函数。

$currentuinfo


    当前用户附加信息的数组，请注意 (!$loginok &amp;&amp; !$guestloginok) 情况下该变量无效。该数组的元素请参考 phpbbslib.c 内 assign_userinfo() 函数。


2.  可设置的全局变量。必须在 include 之前设置

    
$setboard


    赋值成 1 的时候将设置当前用户不在任何版面上。web 由于用户可以开多个窗口，所以如果任何和版面无关的页面都将这个设置成 1 反而会导致统计更不准确并且加重系统负担。目前只有首页和讨论区列表将其设置为 1。

$needlogin


    这个变量不建议设置。当设置为 0 时，如果当前用户没有登录（注意 guest 登录也算作用户登录），则不主动登录为 guest，同时也不设置登录信息的 cookie。这样做如果用户没有登录 (!$loginok &amp;&amp; !$guestloginok) 必须特别注意，该脚本内后续的操作必须和登录用户完全没有关系，而且绝不能假设当前登录用户是 guest，包括 php 库函数内，因为这种情况下 getCurrentUser() 可能是 NULL，也可能是上一次脚本运行时候的用户！例如，rss.php 设置 $needlogin = 0，这样直接调用 bbs_getarticles() 函数就是有问题的，因为这个函数内部要根据当前登录用户来产生帖子标记。现在 rss.php 采用的办法是如果当前没有用户登录就调用一下 bbs_setguest_nologin() 虚设当前用户为非登录状态的 guest。注意，即使调用了 bbs_setguest_nologin() 函数，PHP 变量 $currentuser 仍旧是无效的！一句话，除非你完全确定你在干什么（特别安全方面已经考虑周全），否则不要将 $needlogin 设置为 0，也不要去调用 bbs_setguest_nologin()。


3.  有用的函数说明

    
cache_header($scope,$modifytime=0,$expiretime=300)


    检查/设置页面 cache。$scope 一般设置成 &quot;public&quot; 以让客户端缓存。如果客户端 cache 内本文件修改时间不早于 $modifytime，则输出 Not Modified HTTP-状态码，并返回 TRUE；否则输出 cache 控制头，并返回 FALSE。这里 $modifytime 一般取相应文件的修改时间，比如附件输出的页面调用这个函数，$modifytime 就是附件所属文件的修改时间。

update_cache_header($updatetime = 10,$expiretime = 300)


检查/设置页面 cache。如果客户端 cache 内文件修改时间是 $updatetime 分钟内的，则输出 Not Modified HTTP-状态码，并返回 TRUE；否则输出 cache 控制头，并返回 FALSE。这个函数一般用于强制客户端缓存一些系统状态信息，以免过多刷新加重服务器负担。


### 注

    这两个 cache 函数对于调试页面是不方便的。在调试阶段可以在 cache_process() 函数的开头写上 return FALSE; 直接返回。

    
setStat($stat)


    设置页面标题，应该先于 html_init() 或 show_nav() 调用。

html_init($charset=&quot;&quot;,$otherheader=&quot;&quot;,$is_mathml=false)


初始化 html 页面，一般不需要直接调用，除非是小页面。

    
$charset


    页面编码，默认 gb2312。

$otherheader


    附加放入 &lt;head&gt; 段的内容。

$is_mathml


    本页面是否采用 xhtml+mathml 发送。


    
show_nav($boardName='',$is_mathml=false,$other_headers=&quot;&quot;)


    调用 html_init(&quot;&quot;,$other_headers,$is_mathml) 并显示页面头。如果 $boardName 不为空，搜索的链接将会自动选中此版面。如果 $boardName 为 false，不显示菜单条。所有 POST 递交生成的页面原则上都应该设置 $boardName=false，否则用户重新登录，调整页面风格等可能会造成返回后的 URL 不正确。

head_var($Title='', $URL='',$showWelcome=0)


    显示导航条。

setSucMsg($msg)


    添加成功操作信息 $msg。

html_success_quit($Desc='',$URL='')


    显示成功操作信息。

requireLoginok($msg = false, $directexit = true)


声明本页面必须正式注册用户才能访问。如果当前用户是正式用户，函数直接返回。否则直接调用 foundErr(错误信息, $directexit, false)，其中错误信息由 $msg 控制：

    
$msg


    错误信息。如果为 false 就是用默认的错误信息。


    
这个函数还有一个作用是，调用它之后调用 show_nav() 显示的页面头里面的注销 link 将会带到首页，否则注销仍旧会回到当前页面。页面调用这几个函数的顺序应该是：setStat, requireLoginok, show_nav。

    
foundErr($msg, $directexit = true, $showmsg = true)


添加操作错误信息 $msg。$directexit 为 true 时，调用 show_footer($showmsg, true) 并结束页面，如果之前还没有显示过页面头，这个函数会自动调用 show_nav() 显示页面头。


### 警告

    当 $directexit 为 true (默认) 的时候，requireLoginok() 和 foundErr() 两个函数如果碰到错误会结束页面！

    
isErrFounded()


    是否有错误发生。

html_error_quit()


    显示错误信息。如果曾经调用过 requireLoginok() 而当前用户又是 guest，将会显示登录框。这个函数一般不需要直接调用。

show_footer($showmsg = true, $showerr = true)


显示页面最后的版权信息等。参数：

    
$showmsg


    指示本页面是否有自动刷新消息接受页面的功能。除非是小页面，一般都设置成 true。

$showerr


    如果页面有错误，是否调用 html_error_quit() 输出错误信息。


jumpReferer()


    如果 Referer 有设置，跳到 Referer，否则跳到首页。

get_secname_index($secnum)


    在 inc/site.php 里面用 $section_nums 数组定义了分类讨论区代号。这个函数中的 $secnum 参数是某个分类讨论区的代号（可能会是字母），该函数的返回值是这个分类讨论区在 $section_nums 数组中的位置。在 wForum 和 phpbbslib.c 程序中，secNum 是一个被混用的变量名，一般从 phpbbslib.c 中出来的 secNum 表示分类讨论区代号；而在 wForum 页面之间传递的 secNum 参数，又一般是 $section_nums 数组元素的下标。在 wForum 的后续开发中，这个问题需要特别注意。


### <a name="itex2mathml_details"></a>3.6. itex2mathml 的一些技术细节

Mozilla 等通过服务器发送的 Content-Type 来判断文档类型，只有 xml 才会得到 Mathml 支持。而 IE 的 MathPlayer 插件也可以依赖于 Content-Type 来判断是否需要 MathPlayer 的预处理，所以碰到带有 tex 的帖子，服务器会自动发送 application/xhtml+xml 的 Content-Type。另外，如果 IE 没有装 MathPlayer 插件，用这个 Content-Type 会造成 xml parse 错误，所以如果 User-Agent 表明客户端是一个没有安装 MathPlayer 的 IE，还是会使用普通的 text/html Content-Type。这是唯一需要判断浏览器类型的地方，具体的代码可以在 inc/funcs.php 的 html_init() 函数内找到。请注意，程序假设客户端如果不是 IE 就必定支持 Mathml，如果客户端是比较古老的浏览器是有问题的，请站点管理员对最终用户说明。

由于 Mathml 必须使用 xml 文档标准，而 xml 对页面 tag 的要求极为严格，所以需要使用 application/xhtml+xml Content-Type 的文件，html 的书写必须按照 xhtml 的标准非常规范的书写。目前可能使用 application/xhtml+xml Content-Type 的页面只有 disparticle.php 和 preview.php，所有与这两个页面相关的 html 已经按照 xhtml 标准改写，但是其它页面还远远没有达到 xhtml 的规范，wForum 今后的开发必须要避免不规范的 xhtml 写法。

不规范的 xml 文档在 Mozilla 里面会出 parse 错误，所以这必须严格避免。为此，一旦帖子使用 tex 发表，就禁用除 [upload=1][/upload] 上传文件 ubb 以外的所有 ubb 功能。另外，在两个 $ 之间或者 \[ 和 \] 之间是 itex 公式，这个公式区域内所有的 ansi 颜色格式等（包括引文变色）也失效。目前这部分可能还有一些问题，可能还可以通过一些办法使浏览器出错导致不能阅读帖子，请发现问题提交 bug 报告。


### <a name="browscap"></a>3.7. USEBROWSCAP 站点参数（这个功能目前关闭）

开启 USEBROWSCAP 站点参数就会使用 browscap() 函数来更准确地判断浏览器和操作系统类型，需要配置 PHP 的 browscap.ini。默认关闭这个功能。开启这个功能虽然会准确判断出非 IE 浏览器，但是会大大降低出首页的速度，参考：[get_browser() 函数文档](http://www.php.net/manual/en/function.get-browser.php) 中 verx at implix dot com 添加的用户注释。


### <a name="bbsleft"></a>3.8. wForum 框架结构支持

wForum 支持类似 smthbbs 系统 web 界面的框架结构，也就是左边有一个树状功能目录。要实现这个只要从 wForum/frames.php 进入即可。目前这个支持只是实现功能，左边的功能目录并不漂亮。另外，有一个 bug 是当用户收藏版面发生变化的时候左边的功能目录不会自动刷新，没有加这个的原因是希望在这种情况下不要刷新整个页面，而只是刷新收藏夹那棵树，这个日后会完善。


### <a name="misc"></a>3.9. 一些杂事

*   一个主题，如果最后一个回复是未读的，自动认为这个主题未读。

*   版面搜索功能只搜索楼主，时间范围指定的是最后回复时间范围。

*   queryresult.php 使用 javascript 来写页面。如果搜索结果过多，可能会提示是否取消 javascript 运行。另外搜索结果全都放在一 table 里面，如果页面没有下完，就什么都不会显示。这些暂时不管了吧，以后搜索做成分页的就好了。

*   目前使用 php session 的只有注册页的图片识别。register.php 和 img_rand/img_rand.php 自己调用 session_start(), inc/funcs.php 不调用 session_start()。这个以后有必要的话可以改。比方浏览器 cookie 标准是有个数上限的，可以考虑把 cookie 全都放到 session 里面。当然那样的话 php session_tmp 那个目录要弄成 tmpfs。另外需要注意新的 php 版本，session_start() 不能被调用两次。
