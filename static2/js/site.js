//全局消息提醒
function J_msgshow(msg){
	if(J_msgshow._init!=1){
		J_msgshow._init=1;
		var html='<div class="j-msgdialog"><div class="dialog"></div></div>';
		$("body").append(html);
	}
	
	$(".j-msgdialog>.dialog").html(msg);
	$(".j-msgdialog").fadeIn();
	setTimeout(function(){
		$(".j-msgdialog").fadeOut();
	},2000);
}

//删除提示
function J_delete(a){
  var requestUrl = $(a).attr('data-url');
  
  if(window.confirm("确定删除吗?")){
    location.href = requestUrl;
  }
}

//刷新页面
function J_refresh(){
	location.reload(true);
}

function J_goback(){
	window.history.back();
}

function J_hide(id){
	$("#"+id).hide();
}

function J_show(id){
	$("#"+id).show();
}