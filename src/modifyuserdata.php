<?php
require("inc/funcs.php");
require("inc/usermanage.inc.php");
require("inc/user.inc.php");
require_once("inc/myface.inc.php");

setStat("基本资料修改");

requireLoginok();

show_nav();

showUserMailbox();
head_var($userid."的控制面板","usermanagemenu.php",0);
showUserManageMenu();
main();

show_footer();

function main(){
	global $currentuser;
	require("inc/userdatadefine.inc.php");

?>
<br>
<script language="JavaScript">
	function setimg(i) {
		o = document.images['imgmyface'];
		o.src = 'userface/image' + (i + 1) + '.gif';
		o.width = 32;
		o.height = 32;
		getRawObject('myface').value = '';
	}
</script>
<form action="saveuserdata.php" method=POST name="theForm">
<table cellpadding=3 cellspacing=1 border=0 align=center class=TableBorder1>
<tr> 
      <th colspan="2" width="100%">基本设置选项
      </th>
    </tr> 
<TR> 
<TD width=40% class=TableBody1><B>性&nbsp;&nbsp;&nbsp;&nbsp;别</B>：<BR>请选择您的性别</TD>
<TD width=60%  class=TableBody1> <INPUT type=radio <?php if (chr($currentuser['gender'])=='M') echo "checked"; ?> value=1 name=gender>
<IMG  src=pic/Male.gif align=absMiddle>男孩 &nbsp;&nbsp;&nbsp;&nbsp; 
<INPUT type=radio value=2 <?php if (chr($currentuser['gender'])=='F') echo "checked"; ?> name=gender>
<IMG  src=pic/Female.gif align=absMiddle>女孩</font></TD>
</tr>
<tr>    
<td width=40%  class=TableBody1><B>生日</B><BR>如不想填写，请全部留空</td>   
<td width=60%  class=TableBody1 valign=center>
<input maxlength="4" size="4" name="year" value="<?php echo '19'.$currentuser['birthyear']; ?>" /> 年 <input maxlength="2" size="2" name="month" value="<?php echo $currentuser['birthmonth']; ?>"/> 月 <input size="2" maxlength="2" name="day" value="<?php echo $currentuser['birthday']; ?>"/> 日
</td>   
</tr>

 <TR> 
<TD width=40%  class=TableBody1><B>头像</B>：<BR>选择的头像将出现在您的资料和发表的帖子中，您也可以选择下面的自定义头像</TD>
<TD width=60%  class=TableBody1> 
<select name=face id=face size=1 onChange="setimg(selectedIndex)" style="BACKGROUND-COLOR: #99CCFF; BORDER-BOTTOM: 1px double; BORDER-LEFT: 1px double; BORDER-RIGHT: 1px double; BORDER-TOP: 1px double; COLOR: #000000">
<?php 
	for ($i=1;$i<=USERFACE_IMG_NUMS;$i++) {
		echo "<option value=\"".$i."\"";
		if ($i==$currentuser['userface_img']) {
			echo " selected ";
		}
		echo ">image".$i.".gif</option>";
	}
?>
</select>
&nbsp;<a href="javascript:openScript('showallfaces.php',500,400)">查看所有头像</a>
</tr>

<tr> 
<td width="40%" valign="top" class="TableBody1"><b>自定义头像</b>：<br/>如果图像位置中有连接图片将以自定义的为主</td>
<td width="60%" class="TableBody1">
<?php
	if (USER_FACE) {
?>
<iframe name="ad" frameborder="0" width="100%" height="24" scrolling="no" src="postface.php"></iframe>
<?php
	}
?>
<table width="100%"><tr><td>
图像位置： 
<input type="text" name="myface" id="myface" size="60" maxlength="100" value="<?php echo htmlEncode($currentuser['userface_url']); ?>" />
&nbsp;完整Url地址<br>
宽&nbsp;&nbsp;&nbsp;&nbsp;度： 
<input type="text" name="width" id="width" size="3" value="<?php echo $currentuser['userface_width'];  ?>" />
0---120的整数<br>
高&nbsp;&nbsp;&nbsp;&nbsp;度： 
<input type="text" name="height" id="height" size="3" value="<?php echo $currentuser['userface_height'];  ?>" />
0---120的整数<br>
</td><td align="right"><?php echo get_myface($currentuser, "id=\"imgmyface\""); ?></td></tr></table>
</td></tr>
<tr>    
<td width="40%" class="TableBody1"><b>个人照片</b>：<br/>如果您有照片在网上，请输入网页地址。此项可选</td>   
<td width="60%" class="TableBody1">    
<input type="text" name="userphoto" value="<?php echo $currentuser['photo_url']; ?>" size="30" maxlength="100"/>   
</td>   
</tr>   
<tr> 
<td width="40%" class="TableBody1"><b>门派</b>：<br/>您可以自由选择要加入的门派</td>
<td width="60%" class="TableBody1"> 
<select name="groupname">
<?php 
	for($i=0;$i<count($groups);$i++) {
		echo "<option value=\"".$i."\"";
		if ($currentuser['groups']==$i){
			echo " selected ";
		}
		echo ">".$groups[$i]."</option>";
	}
?>
</select>
</td>
</tr>
<TR> 
<TD width=40%  class=TableBody1><B>OICQ号码</B>：<BR>填写您的QQ地址，方便与他人的联系</TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=20 size=44 name=OICQ value="<?php echo htmlEncode($currentuser['OICQ']); ?>">
</TD>
</TR>
<TR> 
<TD width=40%  class=TableBody1><B>ICQ号码</B>：<BR>填写您的ICQ地址，方便与他人的联系</font></TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=20 size=44 name=ICQ value="<?php echo htmlEncode($currentuser['ICQ']); ?>">
</TD>
</TR>
<TR > 
<TD width=40%  class=TableBody1><B>MSN</B>：<BR>填写您的MSN地址，方便与他人的联系</TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=70 size=44 name=MSN value="<?php echo htmlEncode($currentuser['MSN']); ?>">
</TD>
</TR>
<TR > 
<TD width=40%  class=TableBody1><B>主页</B>：<BR>填写您的个人主页地址，展示您的网上风采</TD>
<TD width=60%  class=TableBody1> 
<INPUT maxLength=70 size=44 name=homepage value="<?php echo htmlEncode($currentuser['homepage']); ?>">
</TD>
</TR>
<TR> 
<TD width=40% class=TableBody1><B>Email</B>：<BR>您的有效电子邮件地址</TD>
<TD width=60%  class=TableBody1> 
<input name=email size=40 value="<?php echo htmlEncode($currentuser['reg_email']); ?>"></TD>
</TR>
      <tr>    
        <td valign=top width="40%" class=TableBody1><B>签&nbsp;&nbsp;&nbsp;&nbsp;名</B>：<BR>不能超过 250 个字符   
          <br>   
          文字将出现在您发表的文章的结尾处。</td>   
        <td width="60%" class=TableBody1>    
          <textarea name="Signature" rows=5 cols=60 wrap=PHYSICAL>
<?php
	$filename=bbs_sethomefile($currentuser["userid"],"signatures");
    $fp = @fopen ($filename, "r"); //ToDo: 这个完全可以用一个 PHP 函数来做，下面还有一个
    if ($fp!=false) {
		while (!feof ($fp)) {
			$buffer = fgets($fp, 300);
			echo htmlspecialchars($buffer);
		}
		fclose ($fp);
    }
?></textarea>   
        </td>   
      </tr>
<tr>
<th height=25 align=left valign=middle colspan=2 align="center">&nbsp;个人真实信息（以下内容建议填写）</th>
</tr>
<tr>
<td valign=top width=40% class=TableBody1> 　<b>真实姓名：</b>
<input type=text name=realname size=18 value="<?php echo htmlEncode($currentuser['realname']); ?>">
</td>
<td height=71 align=left valign=top  class=TableBody1 rowspan=14 width=60% >
<table width=100% border=0 cellspacing=0 cellpadding=5>
<tr>
<td class=TableBody1><b>性　格： &nbsp; </b>
<br>
<?php
	for ($i=1;$i<count($character);$i++) {
		echo "<input type=\"checkbox\" name=\"character\" value=\"".$i."\" ";
		if ($i==$currentuser['character']) {
			echo " checked ";
		}
		echo " >".$character[$i];
		if ($i % 5 ==0) {
			echo "<br>";
		}

	}
?>
 </td>
</tr>
<tr>
<td class=TableBody1><b>个人简介： &nbsp;</b><br>
<textarea name=personal rows=6 cols=90% >
<?php
	$filename=bbs_sethomefile($currentuser["userid"],"plans");
    $fp = @fopen ($filename, "r");
    if ($fp!=false) {
		while (!feof ($fp)) {
			$buffer = fgets($fp, 300);
			echo htmlspecialchars($buffer);
		}
		fclose ($fp);
    }

?></textarea>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td valign=top width=40%  class=TableBody1>　<b>国　　家：</b>
<b>
<input type=text name=country size=18 value="<?php echo htmlEncode($currentuser['country']); ?>">
</b> </td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>省　　份：</b>
<input type=text name=province size=18 value="<?php echo htmlEncode($currentuser['province']); ?>">
</td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>城　　市：</b>
<input type=text name=city size=18 value="<?php echo htmlEncode($currentuser['city']); ?>">
</td>
</tr>
<tr>
<td valign=top width=40%  class=TableBody1>　<b>联系电话：</b>
<b>
<input type=text name=userphone size=18 value="<?php echo htmlEncode($currentuser['telephone']); ?>">
</b> </td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>通信地址：</b>
<b>
<input type=text name=address size=18 value="<?php echo htmlEncode($currentuser['address']); ?>">
</b> </td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>生　　肖：
</b>
<select size=1 name=shengxiao>
<?php
	for ($i=0;$i<count($shengxiao);$i++) {
		echo "<option value=\"".$i."\"";
		if ($i==$currentuser['shengxiao']) {
			echo " selected ";
		}
		echo ">".$shengxiao[$i]."</option>";
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
		echo "<option value=\"".$i."\"";
		if ($i==$currentuser['bloodtype']) {
			echo " selected ";
		}
		echo ">".$bloodtype[$i]."</option>";
	}
?>
</select>
</td>
</td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>信　　仰：</b>
<select size=1 name=belief>
<?php
	for($i=0;$i<count($religion);$i++){
		echo "<option value=\"".$i."\"";
		if ($i==$currentuser['religion']) {
			echo " selected ";
		}
		echo ">".$religion[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>职　　业： </b>
<select name=occupation>
<?php
	for($i=0;$i<count($profession);$i++){
		echo "<option value=\"".$i."\"";
		if ($i==$currentuser['profession']) {
			echo " selected ";
		}
		echo ">".$profession[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>婚姻状况：</b>
<select size=1 name=marital>
<?php
	for($i=0;$i<count($married);$i++){
		echo "<option value=\"".$i."\"";
		if ($i==$currentuser['married']) {
			echo " selected ";
		}
		echo ">".$married[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>最高学历：</b>
<select size=1 name=education>
<?php
	for($i=0;$i<count($graduate);$i++){
		echo "<option value=\"".$i."\"";
		if ($i==$currentuser['education']) {
			echo " selected ";
		}
		echo ">".$graduate[$i]."</option>";
	}
?>
</select></td>
</tr>
<tr>
<td valign=top width=40% class=TableBody1>　<b>毕业院校：</b>
<input type=text name=college size=18 value="<?php echo htmlEncode($currentuser['graduateschool']); ?>"></td>
</tr>
<tr align="center"> 
<td colspan="2" width="100%" class=TableBody2>
<input type=Submit value="更 新" name="Submit" id="oSubmit"> &nbsp; <input type="reset" name="Submit2" value="清 除" id="oSubmit2">
</td></tr>  
</table></form>
<?php
}
?>
