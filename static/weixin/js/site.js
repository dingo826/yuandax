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

//全局确认对话框
function J_checkdialog(msg,fun){
	if(J_checkdialog._init!=1){
		J_checkdialog._init=1;
		J_checkdialog.result=false;
		
		var html='';
		$("body").append(html);
		
		$(".dialog-check>.right").bind("click",function(){
			
		});
	}
	
	J_checkdialog.callback=fun;
	$(".dialog-check>.msg").html(msg);
	
}

//删除提示
function J_delete(a){
  var requestUrl = $(a).attr('data-url');
  
  if(window.confirm("确定删除吗?")){
    location.href = requestUrl;
  }
}

//全局发送预览对话框
function J_dialog_viewnew(){
	if(J_dialog_viewnew._init!=1){
		J_dialog_viewnew._init=1;
		var html='<div id="j-dialog-viewnew" class="j-dialog-viewnew j-hide"><div class="container"><div class="headtitle">信息群发预览</div><div class="content-a"><ul></ul></div><div class="j-w90 j-pct"><input type="submit" class="j-button-green j-fr j-ml10" value="发送预览"><input type="button" class="j-button-white j-fr" value="关闭"><div class="j-m j-h20"></div></div></div></div>';
		$("body").append(html);
		
		//显示预览框
		J_dialog_viewnew.show=function(){
			$("#j-dialog-viewnew").fadeIn();
			$("#j-dialog-viewnew>.container").slideDown();
		}
		
		//隐藏预览框
		J_dialog_viewnew.hide=function(e){
			$("#j-dialog-viewnew").fadeOut();
			$("#j-dialog-viewnew>.container").slideUp();
		}
		
		//选择发送的人
		J_dialog_viewnew.selectradio=function(e){
			$(this).find("input:radio").attr("checked","true");
		}
		
		//发送预览消息
		J_dialog_viewnew.send=function(a){
			if($("input:radio:checked[name='optionsRadios']").length<=0){
				J_msgshow("请先选择发送对象");
				return false;
			}
      
      if($(".newslist input:checked").length<=0){
        J_msgshow("请选择文章");
        return false;
      }
      
			$("#ispreview").val(1);
      
      $("#previewwechatid").val($("input:radio:checked[name='optionsRadios']").val());
      $(a).prop("disabled","disabled").val("提交中");
      $("#s_form")[0].submit();
      
      /*var url=$("#s_form").prop("action");
      var data=$("#s_form").serialize();
      
      $(a).prop("disabled","disabled").val("提交中");
      $.post(url,data,function(json){
        $("#ispreview").val("");
        if(json.status==1){
          J_msgshow("发送成功");
          J_dialog_viewnew.hide();
        }else{
          $(a).prop("disabled",false).val("发送预览");
          alert(json.error);
        }
      },"json");*/
		}
		
		//数据生成html
		J_dialog_viewnew.createhtml=function(data){
			var html="";
			var c=data.length-1;
			for(var i=0; i<=c; i++){
				html=html+'<li><table width="100%" cellpadding="0" cellspacing="0"><tbody><tr><td class="j-w-40 j-txtct"><input type="radio" name="optionsRadios" value="'+data[i].wechatid+'"></td><td class="j-w-60"><img src="'+ data[i].headimgurl +'"></td><td class="j-bold j-fz-14">&nbsp;'+ data[i].name +'</td></tr></tbody></table></li>';
			}
			return html;
		}

		//请求用户
		J_dialog_viewnew.request=function(){
			$.post("/weixin/upreview",{//请求需要发送的内容
			},function(data){
        var html=J_dialog_viewnew.createhtml(data);
		    $("#j-dialog-viewnew ul").append(html);
			  $("#j-dialog-viewnew tr").bind("click",J_dialog_viewnew.selectradio);
			},"json");

			//var data=[{wechatid:'1',name:"名称1",headimgurl:''}];
			//var html=J_dialog_viewnew.createhtml(data);
			
		}
		
		$("#j-dialog-viewnew .j-button-white").bind("click",J_dialog_viewnew.hide);
		$("#j-dialog-viewnew .j-button-green").bind("click",function(){J_dialog_viewnew.send(this)});
		
		J_dialog_viewnew.request();
	}
	
	J_dialog_viewnew.show();
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