/**
##
#	Project: PHPDISK File Storage Solution
#	This is NOT a freeware, use is subject to license terms.
#
#	Site: http://www.phpdisk.com
#
#	$Id: common.js 32 2014-10-17 06:57:36Z along $
#
#	Copyright (C) 2008-2014 PHPDisk Team. All Rights Reserved.
#
##
*/
String.prototype.strtrim = function(){
	return this.replace(/(^\s*)|(\s*$)/g, "");
}
String.prototype.toInt = function() {
	var s = parseInt(this);
	return isNaN(s) ? 0 : s;
}
function getId(id){
	return document.getElementById(id);	
}
function go(url){
	document.location.href = url;
}

function get_icon(ext){
    var icons =
	{
		'default':'file',
		'7z' : '7z',
		'asp' : 'asp',
		'aspx' : 'aspx',
		'bat' : 'bat',
		'bmp' : 'bmp',
		'chm' : 'chm',
		'css' : 'css',
		'db' : 'db',
		'dll' : 'dll',
		'doc' : 'doc',
		'exe' : 'exe',
		'file' : 'file',
		'fla' : 'fla',
		'gif' : 'gif',
		'htm' : 'htm',
		'html' : 'html',
		'images' : 'images',
		'ini' : 'ini',
		'jpeg' : 'jpeg',
		'jpg' : 'jpg',
		'js' : 'js',
		'jsp' : 'jsp',
		'lnk' : 'lnk',
		'mdb' : 'mdb',
		'mov' : 'mov',
		'mp3' : 'mp3',
		'pdf' : 'pdf',
		'php' : 'php',
		'png' : 'png',
		'ppt' : 'ppt',
		'psd' : 'psd',
		'qt' : 'qt',
		'quicktime' : 'quicktime',
		'rar' : 'rar',
		'reg' : 'reg',
		'rm' : 'rm',
		'rmvb' : 'rmvb',
		'shtml' : 'shtml',
		'swf' : 'swf',
		'tif' : 'tif',
		'torrent' : 'torrent',
		'txt' : 'txt',
		'vbs' : 'vbs',
		'video' : 'video',
		'video2' : 'video2',
		'video3' : 'video3',
		'vsd' : 'vsd',
		'wmv' : 'wmv',
		'xls' : 'xls',
		'xml' : 'xml',
		'xsl' : 'xsl',
		'zip' : 'zip'
	}
    return icons[ext] ? icons[ext] : icons['default'];
}
function get_extension(n){
	n = n.substr(n.lastIndexOf('.')+1);
	return n.toLowerCase();
}
function make_code(id,len){
   var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
   var tmp = "";
   var code = "";
   for(var i=0;i<len;i++){
	   code += chars.charAt(Math.ceil(Math.random()*100000000)%chars.length);
   }
   document.getElementById(id).value = code;
}
function chgFileBox(id){
	if(id == 1){
		parent.document.getElementById('p_upbox').className = 'current';
		parent.document.getElementById('p_filebox').className = '';
		parent.document.getElementById('p_upbox_div').style.display = 'block';
		parent.document.getElementById('p_filebox_div').style.display = 'none';	
	}else if(id == 2){
		parent.document.getElementById('p_filebox').className = 'current';
		parent.document.getElementById('p_upbox').className = '';
		parent.document.getElementById('p_filebox_div').style.display = 'block';	
		parent.document.getElementById('p_upbox_div').style.display = 'none';
	}
}
function pd_add2editor(txt,txt2){
	if(parent.wysiwyg){
		parent.insertText(txt,false);
	}else{
		parent.insertText(txt2, parent.strlen(txt2), 0);
	}
}
function resize_img(id,w,h){
	if(getId(id).width>w){
		getId(id).width = w;
	}
	if(getId(id).height>h){
		getId(id).height = h;
	}
}
