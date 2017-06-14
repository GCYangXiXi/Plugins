//XMLHttpRequest 
var xmlhttp = false;
try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
    try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (e2) {
        xmlhttp = false;
    }
}
if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    xmlhttp = new XMLHttpRequest();
}
function Ajax(data) {
    xmlhttp.open("GET", "user_ck.php?username=" + document.getElementById("username").value, true);
    xmlhttp.send(null);
    document.getElementById('username_notice').innerHTML = process_request;//显示状态
    xmlhttp.onreadystatechange = function () {
        if (4 == xmlhttp.readyState) {
            if (200 == xmlhttp.status) {
                var responseText = xmlhttp.responseText;
                if (responseText == "true") {
                    ck_user("true");
                } else {
                    ck_user("false");
                }
            } else {
                alert("发生错误!");
            }
        }
    }
}
function chkUserName(obj) {
    if (checks(obj.value) == false)
    {                                                                                                                                                                                       f
        obj.className = "FrameDivWarn";
        showInfo("username_notice", msg_un_format);
        change_submit("true");
    } else if (obj.value.length < 1) {
        obj.className = "FrameDivWarn";
        showInfo("username_notice", msg_un_blank);
        change_submit("true");
    } else if (obj.value.length < 3) {
        obj.className = "FrameDivWarn";
        showInfo("username_notice", username_shorter);
        change_submit("true");
    } else {
        //调用Ajax函数,向服务器端发送查询
        Ajax(obj.value);
    }

}
//--------------用户名检测---------------------//
function ck_user(result)
{
    if (result == "true")
    {
        document.getElementById('username').className = "FrameDivWarn";
        showInfo("username_notice", msg_un_registered);
        change_submit("true");//禁用提交按钮
    } else
    {
        document.getElementById('username').className = "FrameDivPass";
        showInfo("username_notice", msg_can_rg);
        change_submit("false");//可用提交按钮
    }
}

function checks(t) {
    szMsg = "[#%&'\",;:=!^@]";
    //alertStr="";
    for (i = 1; i < szMsg.length + 1; i++) {
        if (t.indexOf(szMsg.substring(i - 1, i)) > -1) {
            //alertStr="请勿包含非法字符如[#_%&'\",;:=!^]";
            return false;
        }
    }
    return true;
}
//-----------EMAIL检测--------------------------------//
function checkEmail(email)
{
    if (chekemail(email.value) == false)
    {
        email.className = "FrameDivWarn";
        showInfo("email_notice", msg_email_format);
        change_submit("true");
    } else
    {
        showInfo("email_notice", info_right);
        email.className = "FrameDivPass";
        change_submit("false");
    }
}

function chekemail(temail) {
    var pattern = /^[\w]{1}[\w\.\-_]*@[\w]{1}[\w\-_\.]*\.[\w]{2,4}$/i;
    if (pattern.test(temail)) {
        return true;
    } else {
        return false;
    }
}
//--------------------密码检测-----------------------------//
function check_password(password)
{
    if (password.value.length < 6)
    {
        showInfo("password_notice", password_shorter_s);
        password.className = "FrameDivWarn";
        change_submit("true");//禁用提交按钮
    } else if (password.value.length > 30) {
        showInfo("password_notice", password_shorter_m);
        password.className = "FrameDivWarn";
        change_submit("true");//禁用提交按钮
    } else
    {
        showInfo("password_notice", info_right);
        password.className = "FrameDivPass";
        change_submit("false");//允许提交按钮
    }
}

function check_conform_password(conform_password)
{
    password = document.getElementById('password').value;

    if (conform_password.value.length < 6)
    {
        showInfo("conform_password_notice", password_shorter_s);
        conform_password.className = "FrameDivWarn";
        change_submit("true");//禁用提交按
        return false;
    }
    if (conform_password.value != password)
    {
        showInfo("conform_password_notice", confirm_password_invalid);
        conform_password.className = "FrameDivWarn";
        change_submit("true");//禁用提交按
    } else
    {
        conform_password.className = "FrameDivPass";
        showInfo("conform_password_notice", info_right);
        change_submit("false");//允许提交按钮
    }
}
//* *--------------------检测密码强度-----------------------------* *//

function checkIntensity(pwd)
{
    var Mcolor = "#FFF", Lcolor = "#FFF", Hcolor = "#FFF";
    var m = 0;
    var Modes = 0;
    for (i = 0; i < pwd.length; i++)
    {
        var charType = 0;
        var t = pwd.charCodeAt(i);
        if (t >= 48 && t <= 57)
        {
            charType = 1;
        } else if (t >= 65 && t <= 90)
        {
            charType = 2;
        } else if (t >= 97 && t <= 122)
            charType = 4;
        else
            charType = 4;
        Modes |= charType;
    }

    for (i = 0; i < 4; i++)
    {
        if (Modes & 1)
            m++;
        Modes >>>= 1;
    }

    if (pwd.length <= 4)
    {
        m = 1;
    }

    switch (m)
    {
        case 1 :
            Lcolor = "2px solid red";
            Mcolor = Hcolor = "2px solid #DADADA";
            break;
        case 2 :
            Mcolor = "2px solid #f90";
            Lcolor = Hcolor = "2px solid #DADADA";
            break;
        case 3 :
            Hcolor = "2px solid #3c0";
            Lcolor = Mcolor = "2px solid #DADADA";
            break;
        case 4 :
            Hcolor = "2px solid #3c0";
            Lcolor = Mcolor = "2px solid #DADADA";
            break;
        default :
            Hcolor = Mcolor = Lcolor = "";
            break;
    }
    document.getElementById("pwd_lower").style.borderBottom = Lcolor;
    document.getElementById("pwd_middle").style.borderBottom = Mcolor;
    document.getElementById("pwd_high").style.borderBottom = Hcolor;

}
//--------------注册协议复选框状态检测---------------------//
function check_agreement() {
    if (document.formUser.agreement.checked == false)
    {
        showInfo("agreement_notice", agreement);
        change_submit("true");//允许提交
    } else
    {
        showInfo("agreement_notice", info_right);
        change_submit("false");//允许提交按
    }
}


//-------------处理注册程序-----------------------------//
function register() {
    if (document.formUser.username.value == "")
    {
        showclass("username", "FrameDivWarn");
        showInfo("username_notice", msg_un_blank);
        document.formUser.username.focus();
        return false;
    } else if (document.formUser.email.value == "")
    {
        showclass("email", "FrameDivWarn");
        showInfo("email_notice", msg_email_blank);
        document.formUser.email.focus();
        return false;
    } else if (document.formUser.password.value == "")
    {
        showclass("password", "FrameDivWarn");
        showInfo("password_notice", password_empty);
        document.formUser.password.focus();
        return false;
    } else if (document.formUser.confirm_password.value == "")
    {
        showclass("confirm_password", "FrameDivWarn");
        showInfo("conform_password_notice", confirm_password_invalid);
        document.formUser.password.focus();
        return false;
    } else if (document.formUser.agreement.checked == false)
    {
        //showclass("agreement","FrameDivWarn");
        showInfo("agreement_notice", agreement);
        document.formUser.agreement.focus();
        return false;
    }
}

//------------ 按钮状态设置-----------------------------//
function change_submit(zt)
{
    if (zt == "true")
    {
        document.forms['formUser'].elements['Submit1'].disabled = 'disabled';
    } else
    {
        document.forms['formUser'].elements['Submit1'].disabled = '';
    }
}
//------公用程序------------------------------------//
function showInfo(target, Infos) {
    document.getElementById(target).innerHTML = Infos;
}
function showclass(target, Infos) {
    document.getElementById(target).className = Infos;
}
var process_request = "<img src='loading.gif' width='16' height='16' border='0' align='absmiddle'>正在数据处理中...";
var username_empty = "<span style='COLOR:#ff0000'>  × 用户名不能为空!</span>";
var username_shorter = "<span style='COLOR:#ff0000'> × 用户名长度不能少于 3 个字符。</span>";
var username_invalid = "- 用户名只能是由字母数字以及下划线组成。";
var password_empty = "<span style='COLOR:#ff0000'> × 登录密码不能为空。</span>";
var password_shorter_s = "<span style='COLOR:#ff0000'> × 登录密码不能少于 6 个字符。</span>";
var password_shorter_m = "<span style='COLOR:#ff0000'> × 登录密码不能多于 30 个字符。</span>";
var confirm_password_invalid = "<span style='COLOR:#ff0000'> × 两次输入密码不一致!</span>";
var email_empty = "<span style='COLOR:#ff0000'> × Email 为空</span>";
var email_invalid = "- Email 不是合法的地址";
var agreement = "<span style='COLOR:#ff0000'> × 您没有接受协议</span>";
var msn_invalid = "- msn地址不是一个有效的邮件地址";
var qq_invalid = "- QQ号码不是一个有效的号码";
var home_phone_invalid = "- 家庭电话不是一个有效号码";
var office_phone_invalid = "- 办公电话不是一个有效号码";
var mobile_phone_invalid = "- 手机号码不是一个有效号码";
var msg_un_blank = "<span style='COLOR:#ff0000'> × 用户名不能为空!</span>";
var msg_un_length = "<span style='COLOR:#ff0000'> × 用户名最长不得超过15个字符</span>";
var msg_un_format = "<span style='COLOR:#ff0000'> × 用户名含有非法字符!</span>";
var msg_un_registered = "<span style='COLOR:#ff0000'> × 用户名已经存在,请重新输入!</span>";
var msg_can_rg = "<span style='COLOR:#006600'> √ 可以注册！</span>";
var msg_email_blank = "<span style='COLOR:#ff0000'> × 邮件地址不能为空!</span>";
var msg_email_registered = " × 邮箱已存在,请重新输入!";
var msg_email_format = "<span style='COLOR:#ff0000'> × 邮件地址不合法!</span>";
var username_exist = "用户名 %s 已经存在";
var info_can = "<span style='COLOR:#006600'> √ 可以注册!</span>";
var info_right = "<span style='COLOR:#006600'> √ 填写正确!</span>";
