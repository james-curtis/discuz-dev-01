	function showinfo(id, message, color) {
		var val = $("#" + id);
		if (color == 1) {
			val.html(message).css("color", "#ff5656");
		} else if (color == 2) {
			val.html(message).css("color", "#66a211");
		} else {
			val.html(message).css("color", "#666666");
		}
	}
	 $("#username").focus(function(){
		 $(this).css("background-image","url(images/userName_press.png)");
		 $(this).next(".placeholder").css("display","none");
		 $("#info_username").html("");
	 });
	 $("#username").blur(function(){
		 $(this).css("background-image","url(images/userName_bg.png)");
		 if ($(this).val() == ""){
			$(this).next(".placeholder").css("display","block");
		}
	 })
	 
	  $("#pwd").focus(function(){
		 $("#login_btn").css("background-image","url(images/login_btn_down.png)");
		 $(this).css("background-image","url(images/userName_press.png)");
		 $(this).next(".placeholder").css("display","none");
		 $("#info_password").html("");
	 });
	 $("#pwd").blur(function(){
		 $(this).css("background-image","url(images/userName_bg.png)");
		 if ($(this).val() == ""){
			$("#login_btn").css("background-image","url(images/login_btn.png)");
			$(this).next(".placeholder").css("display","block");
		}
	 })
	 $("#vercode").focus(function(){
		 $(this).css("background-image","url(images/yz_press.png)");
		 $(this).next(".placeholder").css("display","none");
		 $("#info_username").html("");
	 });
	 $("#vercode").blur(function(){
		 $(this).css("background-image","url(images/yz_bg.png)");
		 if ($(this).val() == ""){
			$(this).next(".placeholder").css("display","block");
		}
		check_code();
	 })
	

	function get_code() {
		$("#auth_code").attr("src", "codeImage?vertion="+ Math.random());			 
	}

	$("#auth_code").live("click", function() {
		get_code();
	});
  
  
$(document).ready(function(){
	if ($('#username').val() != '') {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
		$('#username').next(".placeholder").css("display","none");
	}else{		
		if ($.cookie('NEARME_USERNAME_COOKIE')!=null&&$.cookie('NEARME_USERNAME_COOKIE')!=''){
			$.base64.utf8encode = true;
			uu=decodeURIComponent($.cookie('NEARME_USERNAME_COOKIE'))
			$('#username').val($.base64.atob(uu, true));
			$('#username').next(".placeholder").css("display","none");
		}
	}
	if ($('#pwd').val() != '') {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
		$('#pwd').next(".placeholder").css("display","none");
	}
});