var accessid = '',
accesskey = '',
host = '',
policyBase64 = '',
signature = '',
callbackbody = '',
filename = '',
key = '',
g_object_name = '';

function random_string(len) {
	len = len || 22;
	var chars = 'abcdefghijklmnopqrstuvwxyz0123456789';   
	var maxPos = chars.length;
	var pwd = '';
	for (i = 0; i < len; i++) {
		pwd += chars.charAt(Math.floor(Math.random() * maxPos));
	}
	return pwd;
}

function set_upload_param(up, filename, type){
	bineoo_jq.ajax({
		url:'plugin.php?id=bineoo_storage:misc&action=access&type='+type+'&fid='+fid,
		dataType:'json',
		async: false,
		success:function(obj){
			if (filename) {
				pos = filename.lastIndexOf('.')
				suffix = ''
				if (pos != -1) {
					suffix = filename.substring(pos)
				}
				obj['dir'] = obj['dir'] + random_string(22) +suffix
			}
			up.setOption({
				'url': obj['host'],
				'multipart_params': {
					'key' : obj['dir'],
					'policy': obj['policy'],
					'Content-Disposition': 'attachment;filename="'+filename+'"',
					'OSSAccessKeyId': obj['accessid'], 
					'success_action_status' : '200', //让服务端返回200,不然，默认会返回204
					'callback' : obj['callback'],
					'signature': obj['signature'],
				}
			});
		}
	});
}

var upload_attach = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'upload_attach', 
	flash_swf_url : 'source/plugin/bineoo_storage/static/Moxie.swf',
	silverlight_xap_url : 'source/plugin/bineoo_storage/static/Moxie.xap',
    url : 'http://oss.aliyuncs.com',
    filters: {
        mime_types : attach_mime_types,
        max_file_size : upload_file_maxsize,
    },
	init: {
		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				bineoo_jq('.attach-box').append(
					'<div class="progressWrapper" id="' + file.id + '">'
						+ '	<div class="progressContainer">'
							+ '	<a class="progressCancel" href="#" onclick="cancel_upload(\'attach\',\''+file.id+'\');return false;"> </a>'
							+ '	<div class="progressName">' + file.name + '</div>'
							+ '	<div class="progressBarStatus">\u7b49\u5f85\u4e0a\u4f20...</div>'
							+ '	<div class="progress"><div class="progress-bar"></div></div>'
						+ '	</div>'
					+ '</div>');
			});
			up.start();
		},

		BeforeUpload: function(up, file) {
			set_upload_param(up, file.name,'');
        },

		UploadProgress: function(up, file) {
			bineoo_jq('#'+file.id).find('.progressBarStatus').html('\u5df2\u4e0a\u4f20:<span>' + file.percent + '%</span>');
			bineoo_jq('#'+file.id).find('.progress-bar').css({width:file.percent+'%'}).attr('aria-valuenow', file.percent);
		},

		FileUploaded: function(up, file, info) {
			if (info.status == 200){
				var result = eval('(' + info.response + ')');
				bineoo_jq('#'+file.id).html(result.html);
			}else{
				showError(info.response);
			} 
		},

		Error: function(up, err) {
			switch(err.code){
				case -600:
					showError('\u6587\u4ef6\u8fc7\u5927');
					break;
				case -601:
					showError('\u6587\u4ef6\u540e\u7f00\u540d\u4e0d\u88ab\u5141\u8bb8');
					break;
				case -602:
					showError('\u6587\u4ef6\u5df2\u4e0a\u4f20\u8fc7');
					break;
				default:
					showError(err.response);
			}
		}
	}
});

var upload_image = new plupload.Uploader({
	runtimes : 'html5,flash,silverlight,html4',
	browse_button : 'upload_image',
	flash_swf_url : 'source/plugin/bineoo_storage/static/Moxie.swf',
	silverlight_xap_url : 'source/plugin/bineoo_storage/static/Moxie.xap',
    url : 'http://oss.aliyuncs.com',
    filters: {
        mime_types : image_mime_types,
        max_file_size : upload_file_maxsize,
    },
	init: {
		FilesAdded: function(up, files) {
			plupload.each(files, function(file) {
				bineoo_jq('#imgattachlist').append(
					'<div class="progressWrapper" id="' + file.id + '">'
						+ '	<div class="progressContainer">'
							+ '	<a class="progressCancel" href="#" onclick="cancel_upload(\'image\',\''+file.id+'\');return false;"> </a>'
							+ '	<div class="progressName">' + file.name + '</div>'
							+ '	<div class="progressBarStatus">\u7b49\u5f85\u4e0a\u4f20...</div>'
							+ '	<div class="progress"><div class="progress-bar"></div></div>'
						+ '	</div>'
					+ '</div>');
			});
			up.start();
		},

		BeforeUpload: function(up, file) {
			set_upload_param(up, file.name,'image');
        },

		UploadProgress: function(up, file) {
			bineoo_jq('#'+file.id).find('.progressBarStatus').html('\u5df2\u4e0a\u4f20:<span>' + file.percent + '%</span>');
			bineoo_jq('#'+file.id).find('.progress-bar').css({width:file.percent+'%'}).attr('aria-valuenow', file.percent);
		},

		FileUploaded: function(up, file, info) {
			if (info.status == 200){
				var result = eval('(' + info.response + ')');
				var inserted = false;
				bineoo_jq('#imgattachlist td').each(function(){
					if(!inserted && !bineoo_jq(this).attr('id')){
						bineoo_jq(this).attr('id','image_td_'+result.aid).html(result.html);
						inserted = true;
					}
				})
				if(!inserted){
					var create_tr = bineoo_jq('<tr>').appendTo('#imgattachlist tbody');
					for (var i = 1; i < 5; i++) {
						var create_td = bineoo_jq('<td>').attr({width:'25%','valign':'bottom'});
						if(!inserted){
							create_td.attr('id','image_td_'+result.aid).html(result.html);
							inserted = true;
						}
						create_td.appendTo(create_tr)
					}
				}
				bineoo_jq('#'+file.id).animate({opacity:0},500,function(){
					bineoo_jq(this).remove();
				});
			}else{
				showError(info.response);
			} 
		},

		Error: function(up, err) {
			switch(err.code){
				case -600:
					showError('\u6587\u4ef6\u8fc7\u5927');
					break;
				case -601:
					showError('\u6587\u4ef6\u540e\u7f00\u540d\u4e0d\u88ab\u5141\u8bb8');
					break;
				case -602:
					showError('\u6587\u4ef6\u5df2\u4e0a\u4f20\u8fc7');
					break;
				default:
					showError(err.response);
			}
		}
	}
});

upload_attach.init();
upload_image.init();

function cancel_upload(type,id){
	if(type=='attach'){
		upload_attach.removeFile(id);
	}else{
		upload_image.removeFile(id);
	}
	bineoo_jq('#'+id).animate({opacity:0},500,function(){
		bineoo_jq(this).remove();
	});
}