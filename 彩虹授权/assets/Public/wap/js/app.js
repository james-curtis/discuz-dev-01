$(document).ready(function(){layer.closeAll();});function login(url){var user=$("#user").val(),pass=$("#pass").val();if(!user||user==""){xk.msg('请先输入账号');return false;}else if(!pass||pass==""){xk.msg('请先输入密码');return false;}
var hasChk=$('#remember').is(':checked')?1:0,loadid=xk.loading('正在登陆'),apiurl="/logina/login?r="+Math.random(1),adata={user:user,pass:pass,remember:hasChk,url:url,loadid:loadid};xk.postData(apiurl,adata);}
function reg(websig){var user=$("#user").val(),pass=$("#pass").val(),qq=$("#qq").val(),code=$("#code").val();if(!user||user==""){xk.msg('请先设置账号');return false;}else if(!pass||pass==""){xk.msg('请先设置密码');return false;}else if(!qq||qq==""){xk.msg('请先填写QQ号码');return false;}else if(!code||code==""){xk.msg('请先填写验证码');return false;}
var loadid=xk.loading(),apiurl="/logina/reg?r="+Math.random(1),adata={user:user,pass:pass,qq:qq,code:code,websig:websig,loadid:loadid};xk.postData(apiurl,adata);}
function newpass(token){var pass=$("#pass").val(),pass2=$("#pass2").val();if(!pass||pass==""){xk.msg('请先输入新密码');return false;}else if(!pass2||pass2==""){xk.msg('请再输入一次新密码');return false;}else if(pass!=pass2){xk.msg('两次密码不一致,请重新输入');return false;}
var loadid=xk.loading(),apiurl="/logina/newpass?r="+Math.random(1),adata={pass:pass,pass2:pass2,token:token,loadid:loadid};xk.postData(apiurl,adata);}
function setpass(){var pass=$("#pass").val(),newpass=$("#newpass").val();newpass2=$("#newpass2").val();code=$("#code").val();if(!pass||pass==""){xk.msg('请输入旧密码');return false;}else if(!newpass||newpass==""){xk.msg('请输入新密码');return false;}else if(!newpass2||newpass2==""){xk.msg('请再输入一遍新密码');return false;}else if(newpass!=newpass2){xk.msg('两次新密码不一致,请重新输入');return false;}else if(!code||code==""){xk.msg('请输入验证码');return false;}
var loadid=xk.loading(),apiurl="/Usera/setpass?r="+Math.random(1),adata={pass:pass,newpass:newpass,newpass2:newpass2,code:code,loadid:loadid};xk.postData(apiurl,adata);}
function setname(){var name=$("#name").val();if(!name||name==""){xk.msg('请填写一个新昵称');return false;}
var loadid=xk.loading(),apiurl="/Usera/setname?r="+Math.random(1),adata={name:name,loadid:loadid};xk.postData(apiurl,adata);}
function setuser(){var user=$("#user").val();if(!user||user==""){xk.msg('请先填写一个登陆账号');return false;}
var loadid=xk.loading(),apiurl="/Usera/setuser?r="+Math.random(1),adata={user:user,loadid:loadid};xk.postData(apiurl,adata);}
function smsbtn(smssig,url){var phone=$("#phone").val(),code=$("#code").val();if(!phone||phone==""){xk.msg('请先填写手机号码');return false;}else if(!code||code==""){xk.msg('请先填写验证码');return false;}
var loadid=xk.loading(),apiurl=url+"?r="+Math.random(1),adata={phone:phone,code:code,smssig:smssig,loadid:loadid};xk.postData(apiurl,adata);}
function smscode(phone,sign,smssig,url){var code=$("#code").val();if(!code||code==""){xk.msg('请先填写短信验证码');return false;}
var loadid=xk.loading(),apiurl=url+"?r="+Math.random(1),adata={phone:phone,sign:sign,code:code,smssig:smssig,loadid:loadid};xk.postData(apiurl,adata);}
function pidbtn(pid,key,dourl){var name=$("#name").val(),url=$("#url").val();if(!name||name==""){xk.msg('请先填写网站名称');return false;}else if(!url||url==""){xk.msg('请先填写网站域名');return false;}
var loadid=xk.loading(),apiurl=dourl+"?r="+Math.random(1),adata={pid:pid,key:key,name:name,url:url,loadid:loadid};xk.postData(apiurl,adata);}
function loginbtn(url){var loadid=xk.loading(),apiurl=url+"?r="+Math.random(1),adata={loadid:loadid};xk.postData(apiurl,adata);}
var xk={postData:function(url,parameter,dataType,dotype){dataType=dataType||'script';dotype=dotype||"POST";$.ajax({type:dotype,url:url,async:true,dataType:dataType,timeout:60000,data:parameter,success:function(data){},error:function(error){xk.close();xk.btn('<h3><b>操作失败，请重试！</b></h3>');}});},msg:function(msg){return layer.open({content:msg,skin:'msg',time:3});},loading:function(msg,mod){msg=msg||'';mod=mod?true:false;return layer.open({type:2,content:msg,shadeClose:mod,shade:'background-color: rgba(0,0,0,0)'});},btn:function(msg,btn){btn=btn||'我知道了';return layer.open({content:msg,btn:btn,shadeClose:false});},url:function(url,msg){xk.loading(msg,true);window.location.href=url;},yesbtn:function(msg,mod,con){return layer.open({shadeClose:false,content:msg,btn:"好的",yes:function(index){if(mod=='url'){if(con==1){window.history.go(-1);}else if(con){window.location.href=con;}else{location.reload();}}else if(mod=='click'){$("#"+con).click();$("#code").val("");}
layer.close(index);}});},close:function(index){if(typeof(index)==='number'){layer.close(index);}else{layer.closeAll();}}}
console.log("\n一张网页，要经历怎样的过程，才能抵达用户面前？\n一位新人，要经历怎样的成长，才能站在技术之巅？\n小柯网络全新网页风格即将来临，敬请期待。\n "),console.log("\n如果你对本页面有更好的改进意见，欢迎提交给我们\n建议收集邮箱 %c xiaokewl@qq.com（请尽量详细描述建议内容，附带本网页地址）\n ","color:red");