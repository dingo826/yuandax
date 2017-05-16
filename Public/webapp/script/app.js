function msgshow(msg){
  if(msgshow.init!=1){
    msgshow.init=1;
    
    var html='<div class="weui_dialog_alert"><div class="weui_mask"></div><div class="weui_dialog"><div class="weui_dialog_hd"><strong class="weui_dialog_title"></strong></div><div class="weui_dialog_bd" id="msgshow-msg">' + msg + '</div><div class="weui_dialog_ft"><a href="javascript:;" id="msgshow-btn" class="weui_btn_dialog primary">确定</a></div></div></div>';
    msgshow.div=document.createElement("div");
    msgshow.div.innerHTML=html;
    document.body.appendChild(msgshow.div);
    
    document.getElementById('msgshow-btn').onclick=function(){
      msgshow.div.style.display="none";
    };
  }
  
  document.getElementById("msgshow-msg").innerHTML=msg;
  msgshow.div.style.display="block";
}

//全局确认
function jconfirm(msg,callback){
	if(jconfirm._init!=1){
		jconfirm._init=1;
		var html = [
			'<style>#jconfirm_c{position:fixed;top:0;right:0;bottom:0;left:0;z-index:9999;width:100%;height:100%;background:rgba(0,0,0,.5);display:flex; display:-webkit-flex;display:-webkit-box;-webkit-box-align:center; -webkit-align-items:center;align-items: center}#jconfirm_content{max-width:400px;width:85%;margin:0 auto;background:#fff;border-radius:3px;overflow:hidden;}#jconfirm_info{padding:15px}#jconfirm_line{height:1px;background:#dcdcdc}.jconfirm_btn{display:block;padding:10px 0px;width:50%;float:left;text-align:center;line-height:25px;font-size:14px;color:#333;box-sizing: border-box}.jconfirm_btn:active{background:#f3f3f3}#jconfirm_btn{border-right:1px solid #f3f3f3}</style>',
      '<div id="jconfirm">',
			'<div id="jconfirm_c">',
			'<div id="jconfirm_content">',
			'<div id="jconfirm_info"></div>',
			'<div id="jconfirm_line"></div>',
			'<a class="jconfirm_btn" id="jconfirm_btn" href="javascript:;">确定</a>',
      '<a class="jconfirm_btn" id="jconfirm_cancelbtn" href="javascript:;">取消</a>',
			'</div>',
			'</div>',
      '</div>'
		].join('');

		var div = document.createElement('div');
		div.innerHTML = html;
		document.body.appendChild(div);

		jconfirm.a=document.getElementById("jconfirm");
		jconfirm.b=document.getElementById("jconfirm_info");
		jconfirm.c=document.getElementById("jconfirm_btn");
    jconfirm.d=function(){}
    
		jconfirm.c.onclick=function(){
			jconfirm.a.style.display="none";
      jconfirm.d();
		}
    
    document.getElementById("jconfirm_cancelbtn").onclick=function(){
      jconfirm.a.style.display="none";
    };
	}

	jconfirm.b.innerHTML=msg;
	jconfirm.a.style.display="block";
  jconfirm.d=callback;
}

//滚动加载
function sLoad(a,callback){
  var h   = parseInt($(a).attr("h"));
  
  var t = a.scrollTop;
  var d =	a.scrollHeight-a.scrollTop-a.offsetHeight;
  if(d>h){
    //console.log(d);
    return false;
  }
  
  
  //检测是否已经全部加载
  var off = $(a).attr("off");
  if(off==1){
    //console.log('over');
    return false;
  }
  
  var status = $(a).attr("status");
  if(status==1){
    //console.log('running');
    return false;
  }
  
  
  if($(a).find(".loading").length<1){
    $(a).append('<div class="loading">加载中</div>');
  }else{
    $(a).find('.loading').html("加载中");
  }
  $(a).attr("status","1");
  
  var requestUrl  = $(a).attr("data-url");
  var pagesize    = $(a).attr("pagesize");
  var page        = parseInt($(a).attr("page"))+1;
  
  console.log('post');
  $.post(requestUrl,{p:page},function(data){
    $(a).attr("page",page);
    $(a).attr("status","0");

    if(data!=null) {
		callback(data);
	}
    $(a).find('.loading').remove();
    
    if(data==null || data.length<pagesize){
      $(a).attr("off","1");
      $(a).append('<div class="loading">加载完毕</div>');
    }
  },"json");
}

function refresh(){
  location.reload(true);
}