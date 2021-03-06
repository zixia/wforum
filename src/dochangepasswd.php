<?php

require("inc/funcs.php");
require("inc/usermanage.inc.php");
require("inc/user.inc.php");

setStat("修改昵称或密码");

requireLoginok();

show_nav(false);

showUserMailbox();
head_var($userid."的控制面板","usermanagemenu.php",0);
main();
showUserManageMenu();
html_success_quit();

show_footer();

function main() {
	global $currentuser;
	if (isset($_POST["nick"])) {
		if (bbs_modify_nick($_POST["nick"], intval($_POST["chkTmp"])) == 0) {
			setSucMsg("更新昵称成功！");
		} else {
			foundErr("昵称修改失败！");
		}
		return;
	}
	$pw1=trim($_POST['oldpsw']);
	$pw2=trim($_POST['psw']);
	$pw3=trim($_POST['psw2']);
    if (strcmp($pw2, $pw3)) {
		foundErr("两次密码不相同");
	}
    if (strlen($pw2) < 2) {
        foundErr("新密码太短");
	}
    if (bbs_checkuserpasswd($currentuser['userid'], $pw1)) {
        foundErr("旧密码不正确");
	}
	$ret=bbs_setuserpasswd($currentuser['userid'], $pw2);
	if ($ret!=0) {
		foundErr("更新密码失败！");
	}
	return setSucMsg("更新密码成功！如果您在登录的时候选择了记住密码，建议您现在重新登录一次，否则您下次上站的时候可能会被提示要求输入密码。");
}
?>