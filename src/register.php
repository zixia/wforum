<?php
	@$action=$_REQUEST['action'];
	$refill = (($action == "refill") || ($action == "refillsave"));
	if (!$refill) {
    	$needlogin=0;
	    session_start();
	}
	require("inc/funcs.php");
	if ($refill) {
	    require("inc/user.inc.php");
	    requireLoginok("请登录后使用这个功能");
	}
	if ($action=='apply') {
		setStat("填写资料");
	    show_nav($refill);
		head_var("新用户注册",'',1);
		do_apply();
	} elseif ($action=='save') {
		setStat("提交注册");
	    show_nav($refill);
		head_var("新用户注册",'',1);
		do_save();
	} elseif ($action=='refill') {
	    setStat("重新填写注册单");
	    show_nav($refill);
	    showUserMailbox();	    
	    head_var("重新填写注册单",'register.php?action=refill');
		do_apply(true);
	} elseif ($action=='refillsave') {
	    setStat("递交重新填写注册单");
	    show_nav($refill);
	    showUserMailbox();
	    head_var("重新填写注册单",'register.php?action=refill');
		do_save(true);
	} else {
		setStat("注册协议");
	    show_nav($refill);
		head_var("新用户注册",'',1);
		do_show();
	}

show_footer();

function do_show() {
	global $SiteName;
?>
<table cellpadding=3 cellspacing=1 align=center class=TableBorder1>
    <tr><th align=center><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method=post>服务条款和声明</td></tr>
	<input type="hidden" name="action" value="apply">
    <tr><td class=TableBody1 align=left>
<?php	require("inc/reg_txt.php") ; ?>
	</td></tr>
    <tr><td align=center class=TableBody2><input type=submit value=我同意></td></form></tr>
</table>
<?php


}

function do_apply($refill = false){
	global $SiteName, $currentuser;
	require "inc/userdatadefine.inc.php";

?>
<script language="javascript">
<!--
	function af(form, fieldID, msg) {
		alert(msg);
		form[fieldID].focus();
		return false;
	}
	function checkEmpty(form, fieldID, fieldName) {
		if (form[fieldID].value == "") {
			return af(form, fieldID, '请输入您的' + fieldName + '!');
		}
		return true;
	}
	function CheckDataValid(form) 
	{
<?php
    if ($refill) {
?>
		var fID = new Array("realname", "dept", "address");
		var fName = new Array("真实姓名", "学校系级或工作单位", "详细通讯地址");
		var len = fID.length;
		for (i = 0; i < len; i++) {
			if (!checkEmpty(form, fID[i], fName[i])) return false;
		}
<?php
    } else {
?>
		var fID = new Array("userid", "pass1", "validCode", "realname", "dept", "address");
		var fName = new Array("用户名", "密码", "验证码", "真实姓名", "学校系级或工作单位", "详细通讯地址");
		var len = fID.length;
		for (i = 0; i < len; i++) {
			if (!checkEmpty(form, fID[i], fName[i])) return false;
		}
		if (form["pass1"].value != form["pass2"].value) {
			return af(form, "pass1", "你两次输入的密码不一样：（"); 
		}
		if (form["pass1"].value == form["userid"].value) {
			return af(form, "pass1", "用户名不能与密码相同"); 
		}
<?php
    }
?>
		return true;
	}
//-->
</script> 
<form method=post action="register.php" onsubmit="return CheckDataValid(this);" name="theForm">
<input type="hidden" name="action" value="<?php echo $refill?"refillsave":"save"; ?>">
<table cellpadding=3 cellspacing=1 align=center class=TableBorder1>
<thead>
<Th colSpan=2 height=24><?php echo $SiteName; ?> -- <?php echo $refill?"重新填写注册单":"新用户注册"; ?></Th>
</thead>
<TBODY> 
<?php if (!$refill) { ?>
<TR> 
<TD width=40% class=TableBody1><B>代号</B>：<BR>2-12字符，可用英文字母或数字，首字符必须是字母</TD>
<TD width=60%  class=TableBody1> 
<input name=userid size=12 maxlength=12>&nbsp; <input type=button value='检测帐号' name=checkid onclick="gopreview();"> </TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>密码</B>：<BR>请输入密码，5-39字符，区分大小写。<BR>
请不要使用任何类似 '*'、' ' 或 HTML 字符</TD>
<TD width=60%  class=TableBody1> 
<input type=password name=pass1 size=12 maxlength=12></TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>密码</B>：<BR>请再输一遍确认</TD>
<TD width=60%  class=TableBody1> 
<input type=password name=pass2 size=12 maxlength=12></TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>验证码</B>：请输入下面图片中的四个数字<br>
<IMG src="img_rand/img_rand.php"></TD>
<TD width=60%  class=TableBody1> 
<input type=text name=validCode size=12 maxlength=12></TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>昵称</B>：<BR>您在BBS上的昵称，2-39字符，中英文不限</TD>
<TD width=60%  class=TableBody1> 
<input name=username size=20 maxlength=32></TD>
</TR>
<?php } //$refill ?>
<TR> 
<TD width=40% class=TableBody1><B>真实姓名</B>：<BR>请用中文, 至少2个汉字</TD>
<TD width=60%  class=TableBody1> 
<input name=realname size=20<?php if ($refill) echo " value=\"".htmlEncode($currentuser['realname'])." \""; ?>></TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>性别</B>：<BR>请选择您的性别</TD>
<?php
    if ($refill) {
        $male = (chr($currentuser['gender'])=='M');
    } else {
        $male = true;
    }
?>
<TD width=60%  class=TableBody1> <INPUT type=radio <?php if ($male) echo "checked"; ?> value=1 name=gender>
<IMG  src=pic/Male.gif align=absMiddle>男孩 &nbsp;&nbsp;&nbsp;&nbsp; 
<INPUT type=radio <?php if (!$male) echo "checked"; ?> value=2 name=gender>
<IMG  src=pic/Female.gif align=absMiddle>女孩</font></TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>Email</B>：<BR>您的有效电子邮件地址</TD>
<TD width=60%  class=TableBody1> 
<input name=email size=40 <?php if ($refill) echo " value=\"".htmlEncode($currentuser['reg_email'])." \""; ?>></TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>学校系级或工作单位</B>：<BR>请用中文，至少6个字符</TD>
<TD width=60%  class=TableBody1> 
<input name=dept size=40></TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>详细通讯地址</B>：<BR>请用中文，至少6个字符</TD>
<TD width=60%  class=TableBody1> 
<input name=address size=40 <?php if ($refill) echo " value=\"".htmlEncode($currentuser['address'])." \""; ?>></TD>
</TR>
<tr>    
<td width=40%  class=TableBody1><B>生日</B><BR>如不想填写，请全部留空</td>   
<td width=60%  class=TableBody1 valign=center>
<input maxlength="4" size="4" name="year" value="<?php if ($refill) echo '19'.$currentuser['birthyear']; ?>" /> 年 <input maxlength="2" size="2" name="month" value="<?php if ($refill) echo $currentuser['birthmonth']; ?>"/> 月 <input size="2" maxlength="2" name="day" value="<?php if ($refill) echo $currentuser['birthday']; ?>"/> 日
</td>   
</tr>
<TR> 
<TD width=40% class=TableBody1><B>联络电话</B>：<BR>您的联络电话，请写明区号</TD>
<TD width=60%  class=TableBody1> 
<input name=phone size=40 <?php if ($refill) echo " value=\"".htmlEncode($currentuser['telephone'])." \""; ?>> </TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>手机</B>：<BR>您的手机号码（如果没有可以不填）</TD>
<TD width=60%  class=TableBody1> 
<input name=mobile size=40 <?php if ($refill) echo " value=\"".htmlEncode($currentuser['mobile_telephone'])." \""; ?>></TD>
</TR>
</table>
<?php if (!$refill) { ?>
<table cellpadding=3 cellspacing=1 align=center class=TableBorder1 id=adv style="DISPLAY: none">
<TBODY> 
<TR align=middle> 
<Th colSpan=2 height=24 align=left>填写详细资料</TD>
</TR>
<TR> 
<TD width=40%  class=TableBody1><B>头像</B>：<BR>选择的头像将出现在您的资料和发表的帖子中
<?php
	if (USER_FACE) echo "<br>在注册之后您可以在基本资料修改中上传自定义头像";
?>
</TD>
<TD width=60%  class=TableBody1> 
<select name=face id=face size=1 onChange="document.images['imgmyface'].src='userface/image'+options[selectedIndex].value+'.gif';" style="BACKGROUND-COLOR: #99CCFF; BORDER-BOTTOM: 1px double; BORDER-LEFT: 1px double; BORDER-RIGHT: 1px double; BORDER-TOP: 1px double; COLOR: #000000">
<?php 
	for ($i=1;$i<=USERFACE_IMG_NUMS;$i++) {
		echo "<option value=\"".$i."\">image".$i.".gif</option>";
}
?>
</select>
<img id=imgmyface src=userface/image1.gif>&nbsp;<a href="javascript:openScript('showallfaces.php',500,400)">查看所有头像</a>
</TR>
<tr> 
<td width=40%  class=TableBody1><B>回复提示</B>：<BR>当您发表的帖子有人回复时，使用论坛信息通知您。</td>
<td width=60%  class=TableBody1>
<input type=radio name=showRe value=1 checked>
提示我
<input type=radio name=showRe value=0>
不提示
</tr>

<TR> 
<TD width=40% class=TableBody1><B>门派</B>：<BR>您可以自由选择要加入的门派</TD>
<TD width=60% class=TableBody1> 
<select name=groupname>
<?php 
	for($i=0;$i<count($groups);$i++) {
		echo "<option value=\"".$i."\">".$groups[$i]."</option>";
	}
?>
</select>
</TD>
</TR>

<TR> 
<TD width=40%  class=TableBody1><B>OICQ号码</B>：<BR>填写您的QQ地址，方便与他人的联系</TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=20 size=44 name=OICQ>
</TD>
</TR>
<TR> 
<TD width=40%  class=TableBody1><B>ICQ号码</B>：<BR>填写您的ICQ地址，方便与他人的联系</font></TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=20 size=44 name=ICQ>
</TD>
</TR>
<TR > 
<TD width=40%  class=TableBody1><B>MSN</B>：<BR>填写您的MSN地址，方便与他人的联系</TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=70 size=44 name=MSN>
</TD>
</TR>
<TR > 
<TD width=40%  class=TableBody1><B>主页</B>：<BR>填写您的个人主页地址，展示您的网上风采</TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=70 size=44 name=homepage>
</TD>
</TR>
<TR> 
<TD width=40%  class=TableBody1><B>签名档</B>：<BR>最多300字节<BR>
文字将出现在您发表的文章的结尾处。体现您的个性。 </TD>
<TD width=60%  class=TableBody1> 
<TEXTAREA name=Signature rows=5 wrap=PHYSICAL cols=60></TEXTAREA>
</TD>
</TR>
<tr>
<th height=25 align=left valign=middle colspan=2><b>&nbsp;个人真实信息</b>（以下内容建议填写）</th>
</tr>
<tr>
<td valign=top  class=TableBody1 width=40% >　<b>国　　家：</b>
<b>
<input type=text name=country size=18>
</b> </td>
<td height=71 align=left valign=top  class=TableBody1 rowspan=14 width=60% >
<table width=100% border=0 cellspacing=0 cellpadding=5>
<tr>
<td class=TableBody1><b>性　格： &nbsp; </b>
<br>
<?php
	for ($i=1;$i<count($character);$i++) {
		echo "<input type=\"checkbox\" name=\"character\" value=\"".$i."\" >".$character[$i];
		if ($i % 5 ==0) {
			echo "<br>";
		}

	}
?>
 </td>
</tr>
<tr>
<td class=TableBody1><b>个人简介： &nbsp;</b><br>
<textarea name=personal rows=6 cols=90% ></textarea>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td valign=top  class=TableBody1 width=40% >　<b>省　　份：</b>
<input type=text name=province size=18>
</td>
</tr>
<tr>
<td valign=top  class=TableBody1 width=40% >　<b>城　　市：
</b>
<input type=text name=city size=18>
</td>
</tr>
<tr>
<td valign=top  class=TableBody1 width=40% >　<b>生　　肖：
</b>
<select size=1 name=shengxiao>
<?php
	for ($i=0;$i<count($shengxiao);$i++) {
		echo "<option value=\"".$i."\">".$shengxiao[$i]."</option>";
	}
?>
</select>
</td>
</tr>
<tr>
<td valign=top  class=TableBody1 width=40% >　<b>血　　型：</b>
<select size=1 name=blood>
<?php
	for($i=0;$i<count($bloodtype);$i++){
		echo "<option value=\"".$i."\">".$bloodtype[$i]."</option>";
	}
?>
</select>
</td>
</tr>
<tr>
<td valign=top  class=TableBody1 width=40% >　<b>信　　仰：</b>
<select size=1 name=belief>
<?php
	for($i=0;$i<count($religion);$i++){
		echo "<option value=\"".$i."\">".$religion[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top class=TableBody1 width=40% >　<b>职　　业： </b>
<select name=occupation>
<?php
	for($i=0;$i<count($profession);$i++){
		echo "<option value=\"".$i."\">".$profession[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top class=TableBody1 width=40% >　<b>婚姻状况：</b>
<select size=1 name=marital>
<?php
	for($i=0;$i<count($married);$i++){
		echo "<option value=\"".$i."\">".$married[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top class=TableBody1 width=40% >　<b>最高学历：</b>
<select size=1 name=education>
<?php
	for($i=0;$i<count($graduate);$i++){
		echo "<option value=\"".$i."\">".$graduate[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top class=TableBody1 width=40% >　<b>毕业院校：</b>
<input type=text name=college size=18></td>
</tr>
</TBODY> 
</TABLE>
</td></tr></table>
<table cellpadding=0 cellspacing=0 border=0 width=97% align=center>
<tr>
<td width=50% height=24>
	<INPUT id=advshow name=advshow type=checkbox value=1 onclick=showadv()><span name=advance id=advance>显示高级用户设置选项</span>
</td>
<td width=50% ><input type=submit value=提交表格></td>
</tr></table>
<?php } else { //$refill ?>
<center><br><input type=submit value=提交表格></center>
<?php } ?>
</form>
<form name=preview action=checkid.php method=post target=preview_page>
<input type=hidden name=id value=>
</form>
<script language="javascript">
function gopreview()
{
document.preview.id.value=document.theForm.userid.value;
var popupWin = window.open('', 'preview_page', 'scrollbars=yes,width=500,height=300');
document.preview.submit();
}
function showadv(){
if (document.theForm.advshow.checked == true) {
	getRawObject("adv").style.display = "";
	//getRawObject("advance").innerHTML="关闭高级用户设置选项"; //这两句话其实能运行但是好像没什么用反而增加误解，先去掉把。- atppp
}else{
	getRawObject("adv").style.display = "none";
	//getRawObject("advance").innerHTML="显示高级用户设置选项";
}
}
</script>


<?php
}

function do_save($refill = false){
	global $SiteName,$currentuser;
	@$userid=$_POST["userid"];
	@$pass1=$_POST["pass1"];
	@$pass2=$_POST["pass2"];
	@$nickname=$_POST["username"];

	@$realname=$_POST["realname"];
	@$dept=$_POST["dept"];
	@$address=$_POST["address"];
	@$year=$_POST["year"];
	@$month=$_POST["month"];
	@$day=$_POST["day"];
	@$email=$_POST["email"];
	@$phone=$_POST["phone"];
	@$mobile_phone=$_POST["mobile"];
	@$gender=$_POST["gender"];

    if (!$refill) {
        if(!isset($_SESSION['num_auth']))
    		foundErr("请等待验证码图片显示完毕！");
        if(strcasecmp($_SESSION['num_auth'],$_POST['validCode']))
            foundErr("您输入的验证码错误！");
    	if(strcmp($pass1,$pass2))
    		foundErr("两次密码输入不一样");
    	else if(strlen($pass1) < 5 || !strcmp($pass1,$userid))
           	foundErr("密码长度太短或者和用户名相同!");
    	$ret=bbs_createnewid($userid,$pass1,$nickname);
    	switch($ret)
    	{
    	case 0:
    			break;
    	case 1:
    			foundErr("用户名有非数字字母字符或者首字符不是字母!");
    			break;
    	case 2:
    			foundErr("用户名至少为两个字母!");
    			break;
    	case 3:
    			foundErr("系统用字或不雅用字!");
    			break;
    	case 4:
    			foundErr("该用户名已经被使用!");
    			break;
    	case 5:
    			foundErr("用户名太长,最长12个字符!");
    			break;
    	case 6:
    			foundErr("密码太长,最长39个字符!");
    			break;
    	case 10:
    			foundErr("系统错误,请与系统管理员SYSOP联系.");
    			break;
    	default:
    			foundErr("注册ID时发生未知的错误!");
    			break;
    	}
    }
	if($gender!='1')$gender=2;
    settype($year,"integer");
	settype($month,"integer");
	settype($day,"integer");

    if ($refill) {
        $ret=bbs_createregform($currentuser["userid"],$realname,$dept,$address,$gender,$year,$month,$day,$email,$phone,$mobile_phone, FALSE);//自动生成注册单
    } else {
        $ret=bbs_createregform($userid,$realname,$dept,$address,$gender,$year,$month,$day,$email,$phone,$mobile_phone, $_POST['OICQ'], $_POST['ICQ'], $_POST['MSN'],  $_POST['homepage'], intval($_POST['face']), '', 0, 0, intval($_POST['groupname']), $_POST['country'],  $_POST['province'], $_POST['city'], intval($_POST['shengxiao']), intval($_POST['blood']), intval($_POST['belief']), intval($_POST['occupation']), intval($_POST['marital']), intval($_POST['education']), $_POST['college'], intval($_POST['character']), FALSE);//自动生成注册单
    }

    if ($refill) {
    	switch($ret)
    	{
    	case 0:
    		break;
    	case 1:
    		foundErr("您的注册单还没有处理，请耐心等候");
    		break;
    	case 2:
    		foundErr("该用户不存在!");
    		break;
    	case 3:
    		foundErr("参数错误");
    		break;
    	case 4:
    		foundErr("你已经通过注册了!");
    		break;
    	default:
    		foundErr("未知的错误!");
    		break;
    	}
    } else {
    	switch($ret)
    	{
    	case 0:
    		break;
    	case -1:
    		foundErr("用户自定义图像宽度错误");
    		break;
    	case -2:
    		foundErr("用户自定义图像高度错误");
    		break;
    	case 2:
    		foundErr("该用户不存在!");
    		break;
    	case 3:
    		foundErr("参数错误");
    		break;
    	default:
    		foundErr("未知的错误!");
    		break;
    	}
    }
	if (!$refill) {
    	$signature=trim($_POST["Signature"]);
    	if ($signature!='') {
    		$filename=bbs_sethomefile($userid,"signatures");
    		$fp=@fopen($filename,"w+");
    		if ($fp!=false) {
    			fwrite($fp,str_replace("\r\n", "\n", $signature));
    			fclose($fp);
    			bbs_recalc_sig();
    		}
    	}
    	$personal=trim($_POST["personal"]);
    	if ($signature!='') {
    		$filename=bbs_sethomefile($userid,"plans");
    		$fp=@fopen($filename,"w+");
    		if ($fp!=false) {
    			fwrite($fp,str_replace("\r\n", "\n", $personal));
    			fclose($fp);
    		}
    	}
    }
?>
<table cellpadding=3 cellspacing=1 align=center class=TableBorder1>
<tr>
<th height=24>注册单已成功：<?php echo $SiteName; ?>欢迎您的到来</th>
</tr>
<tr><td class=TableBody1><br>
<ul>
<li>你现在还没有通过身份认证，,只有最基本的权限，不能发文、发信、聊天等。</li>
<li>系统会自动生成注册单，待站长审核通过后,你将获得合法用户权限！</li>
<li><a href="index.php">进入讨论区</a></li></ul>
</td></tr>
</table>
<?php
}

?>
