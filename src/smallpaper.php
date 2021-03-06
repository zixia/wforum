<?php
require("inc/funcs.php");
require("inc/board.inc.php");
require("inc/user.inc.php");
require("inc/conn.php");

global $boardArr;
global $boardID;
global $boardName;

setStat("发布小字报");

requireLoginok("游客不能发表小字报。");

preprocess();

show_nav($boardName);

showUserMailBoxOrBR();
board_head_var($boardArr['DESC'],$boardName,$boardArr['SECNUM']);
main($boardID,$boardName);

show_footer();

function preprocess(){
	global $boardID;
	global $boardName;
	global $currentuser;
	global $boardArr;
	global $conn;

	if ($conn === false) {
		foundErr("数据库故障。");
	}
	if (!isset($_GET['board'])) {
		foundErr("未指定版面。");
	}
	$boardName=$_GET['board'];
	$brdArr=array();
	$boardID= bbs_getboard($boardName,$brdArr);
	$boardArr=$brdArr;
	$boardName=$brdArr['NAME'];
	if ($boardID==0) {
		foundErr("指定的版面不存在。");
	}
	$usernum = $currentuser["index"];
	if (bbs_checkreadperm($usernum, $boardID) == 0) {
		foundErr("您无权阅读本版！");
	}
	if (bbs_is_readonly_board($boardArr)) {
		foundErr("本版为只读讨论区！");
	}
	if (bbs_checkpostperm($usernum, $boardID) == 0) {
		foundErr("您无权在本版发表小字报！");
	}
	return true;
}

function main($boardID,$boardName) {
	global $conn;

	$conn->query("delete from smallpaper_tb where Addtime<subdate(Now(),interval 2 day)");
?>
<form action="savesmallpaper.php" method="post"> 
<input type="hidden" name="action" value="savepaper">
    <table cellpadding=6 cellspacing=1 align=center class=TableBorder1>
    <tr>
    <th valign=middle colspan=2>
    发布小字报</th></tr>
    <td class=TableBody1 valign=middle><b>标 题</b>(最多30字)</td>
    <td class=TableBody1 valign=middle><INPUT name="title" type=text size=60></td></tr>
    <tr>
    <td class=TableBody1 valign=top width=30%>
<b>内 容</b><BR>
<!--
在本版发布小字报将您将付<font color="<?php   echo $Forum_body[8]; ?>"><b><?php   echo $GroupSetting[46]; ?></b></font>元费用<br>
-->
<font color=#ff0000><b>48</b></font>小时内发表的小字报将随机抽取<font color="#ff0000"><b>5</b></font>条滚动显示于论坛上<br>
<ul>
<li>HTML标签：不可用</li>
<li>UBB 标签：允许</li>
<li>内容不得超过500字</li>
</ul>
</td>
<td class=TableBody1 valign=middle>
<textarea class="smallarea" cols="60" name="Content" rows="8" wrap="VIRTUAL"></textarea>
<INPUT name="board" type=hidden value="<?php   echo $boardName; ?>">
                </td></tr>
    <tr>
    <td class=TableBody2 valign=middle colspan=2 align=center><input type=submit name="submit" value="发 布"></td></tr></table>
</form>
<?php   
} 

?>
