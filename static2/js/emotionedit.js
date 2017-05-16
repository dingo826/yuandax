function edit_update(){
	var html=$("#editor_show").html();
	if(html==""){
		html="欢迎进入平台！";
	}
	$("#talkingtxt").html(html);
	if(html=="欢迎进入平台！"){
		$("#editor_hidden").val('');
	}else{
		$("#editor_hidden").val(Emotion.replaceInput(html));
	}
}

$(this).ready(function(e) {
    //输入框事件绑定
	$("#editor_show").bind("keyup",function(e){
		edit_update();
	});
	
	//QQ表情绑定
	$(".emotionlist .eitem").bind("click",function(e){
		var img='<img src="'+$(e.target).attr("data-gifurl")+'" alt="mo-'+$(e.target).attr("data-title")+'">';
		$("#editor_show").html($("#editor_show").html()+img);
		edit_update();
	});
});

//QQ表情显示隐藏
var _show_qqemotion=false;
function show_qqemotion(){
	if(_show_qqemotion){
		$("#emotionlist-1").slideUp();
		_show_qqemotion=false;
	}else{
		$("#emotionlist-1").slideDown();
		_show_qqemotion=true;
	}
}

//文本图文模式更改
function modechange(id){
	if(id==1){
		$(".div-editor").show();
		$(".newslist").hide();
		$("#txt_view").show();
		$("#news_view").hide();
	}else if(id==2){
		$(".newslist").show();
		$(".div-editor").hide();
		$("#txt_view").hide();
		$("#news_view").show();
	}
}

//图文选择 需要初始化
function newlist_select(a){
	if(newlist_select._init!=1){
		newlist_select._init=1;
		
		newlist_select.ids="";
		newlist_select.defaults={title:'加入会员会更精彩！',src:"static/image/moren.jpg",content:"加入会员会更精彩加入会员会更精彩加入会员会更精彩加入会员会更精彩"};
		newlist_select.cover=false;
		
		//如果传入参数初始化
		if(a.data){
			newlist_select.ids=a.ids;
			
			//选择初始数据
			var list=newlist_select.ids.slice(0,-1);
			//alert(list);
			var lists=list.split(',');
			for(var i=0;i<=lists.length-1; i++){
				$("input[name='news_id[]'][value='"+lists[i]+"'").attr("checked","true");
			}
			
			newlist_select.count = a.ids!="" ? lists.length : 0;
			
			//是否有选择封面功能
			if(a.cover){
				newlist_select.cover=true;
				$("input[name='cover_id'][value='"+lists[0]+"']").attr("checked","true");
				
				$(".newslist input:radio").bind("change",function(e){
					//检测该图文是否被选择
					if($(this).parent().parent().find("input:checkbox").is(":checked")){
						//选择该图文为封面
						var id=$(this).val();
						var reg=new RegExp(id+",");
						newlist_select.ids=newlist_select.ids.replace(reg,"");
						newlist_select.ids=id+","+newlist_select.ids;
						newlist_select.refreshdata();
					}else{
						$(this).removeAttr("checked");
						J_msgshow("该图文未被选择");
					}
					var id=$(this).val();
					
					newlist_select.refreshdata();
				});
			}
			a=false;
		}
		
		//刷新预览内容
		newlist_select.refreshdata=function(){
			if(newlist_select.count==1){
				var content=$(".newslist input:checkbox:checked").attr("content");
				var src=$(".newslist input:checkbox:checked").attr("src");
				var title=$(".newslist input:checkbox:checked").attr("title");
				var id=$(".newslist input:checkbox:checked").val();
        $(".newslist input:checkbox:checked").parent().parent().find(":radio").attr("checked",true);
				
				$("#box-zixun-1 .news_title").html(title);
				$("#box-zixun-1 .news_content").html(content);
				$("#box-zixun-1 p>.img").css("background-image","url("+src+")");
				
				$("#box-zixun-1").show();
				$("#box-zixun-2").hide();
			}else if(newlist_select.count>=2){
				var list=newlist_select.ids.slice(0,-1);
				//alert(list);
				var lists=list.split(',');
				var str, c=0, code="", _src, _title, html;
				
				//alert(lists.length);
				for(var i=0;i<=lists.length-1; i++){
					_src=$(".newslist input:checkbox[value='"+lists[i]+"']").attr("src");
					_title=$(".newslist input:checkbox[value='"+lists[i]+"']").attr("title");
					//alert(_title+","+str);
					if(c==0){
            $(".newslist input:checkbox[value='"+lists[i]+"']").parent().parent().find(":radio").attr("checked",true);
						c=1;
						html='<a style="background-image:url('+_src+')" class="img"><span class="news_title"><div class="j-w95 j-pct">'+_title+'</div></span></a><div class="j-m j-h5"></div>';
						$("#box-zixun-2 .first").html(html);
					}else{
						html='<a class="lista"><table class="j-vtcl-top" width="100%" cellpadding="0" cellspacing="0"><tbody><tr><td>'+_title+'</td><td class="j-w-50"><img src="'+_src+'"></td></tr></tbody></table></a>';
						code+=html;
					}
				}
				$("#box-zixun-2 .listas").html(code);
				
				$("#box-zixun-2").show();
				$("#box-zixun-1").hide();
			}else{
        
				//恢复默认样式
				$("#box-zixun-1 .news_title").html(newlist_select.defaults.title);
				$("#box-zixun-1 .news_content").html(newlist_select.defaults.content);
				$("#box-zixun-1 p>.img").css("background-image","url("+newlist_select.defaults.src+")");
				$(".newslist").find(":radio").removeAttr("checked");
        
				$("#box-zixun-1").show();
				$("#box-zixun-2").hide();
			}
      
			$("#ids").val(newlist_select.ids.slice(0,-1));
		}
	}
	
	if(a!=false){
		var id=$(a).val();
		
		if(newlist_select.count>=9){
			$(a).removeAttr("checked")
			J_msgshow('最多可选择九条图文');
			return false;
		}
		
		if($(a).is(":checked")){
			newlist_select.ids=newlist_select.ids+id+",";
			newlist_select.count++;
		}else{
			var reg=new RegExp(id+",");
			newlist_select.ids=newlist_select.ids.replace(reg,"");
			newlist_select.count--;
		}
	}
	
	newlist_select.refreshdata();
}

//微预约选择预览
function reservation_view(a){
	if(reservation_view._init!=1){
		reservation_view._init=1;
		
		reservation_view.count=$("#reservation_list input:checkbox:checked").length;
		reservation_view.ids="";
		reservation_view.defaults={title:'加入会员会更精彩！',src:"static/image/pic_2.jpg",content:"加入会员会更精彩加入会员会更精彩加入会员会更精彩加入会员会更精彩"};
		
		reservation_view.setDefault=function(){
			$("#business-zixun-1 .news_title").html(reservation_view.defaults.title);
			$("#business-zixun-1 .news_content").html(reservation_view.defaults.content);
			$("#business-zixun-1 p>.img").css("background-image","url("+reservation_view.defaults.src+")");

			$("#business-zixun-1").show();
			$("#business-zixun-2").hide();
		}
		
		if(a.data){
			reservation_view.ids=a.ids;
			
			//选择初始数据
			var list=reservation_view.ids.slice(0,-1);
			//alert(list);
			var lists=list.split(',');
			for(var i=0;i<=lists.length-1; i++){
				$("input[name='business_value[]'][value='"+lists[i]+"'").attr("checked","true");
			}
			
			reservation_view.count=lists.length ? lists.length : 0;
		}
		a=false;
		
		//刷新页面
		reservation_view.refreshdata=function(){
			//显示类型
			if(reservation_view.count==1){
				content=$("#reservation_list input:checkbox:checked").attr("content");
				src=$("#reservation_list input:checkbox:checked").attr("src");
				title=$("#reservation_list input:checkbox:checked").attr("title");
				id=$("#reservation_list input:checkbox:checked").val();
				
				$("#business-zixun-1 .news_title").html(title);
				$("#business-zixun-1 .news_content").html(content);
				$("#business-zixun-1 p>.img").css("background-image","url("+src+")");
				
				$("#business-zixun-1").show();
				$("#business-zixun-2").hide();
			}else if(reservation_view.count>=2){
				var list=reservation_view.ids.slice(0,-1);
				//alert(list);
				var lists=list.split(',');
				var str, c=0, code="", _src, _title, html;
				
				//alert(lists.length);
				for(var i=0;i<=lists.length-1; i++){
					_src=$("#reservation_list input:checkbox[value='"+lists[i]+"']").attr("src");
					_title=$("#reservation_list input:checkbox[value='"+lists[i]+"']").attr("title");
					
					if(c==0){
						c=1;
						html='<a style="background-image:url('+_src+')" class="img"><span class="news_title"><div class="j-w95 j-pct">'+_title+'</div></span></a><div class="j-m j-h5"></div>';
						$("#business-zixun-2 .first").html(html);
					}else{
						html='<a class="lista"><table class="j-vtcl-top" width="100%" cellpadding="0" cellspacing="0"><tbody><tr><td>'+_title+'</td><td class="j-w-50"><img src="'+_src+'"></td></tr></tbody></table></a>';
						code+=html;
					}
				}
				$("#business-zixun-2 .listas").html(code);
				
				$("#business-zixun-2").show();
				$("#business-zixun-1").hide();
			}else{
				//回复默认
				reservation_view.setDefault();
			}
			$("#reservation_ids").val(reservation_view.ids.slice(0,-1));
		}
		
	}

	if(a!=false){
		var id=$(a).val();
		
		if($(a).is(":checked")){
			reservation_view.ids=reservation_view.ids+id+",";
			reservation_view.count++;
		}else{
			var reg=new RegExp(id+",");
			reservation_view.ids=reservation_view.ids.replace(reg,"");
			reservation_view.count--;
		}
	}
	
	
	reservation_view.refreshdata();
}

//QQ表情核心函数
Emotion = {
    url: "static/image/face/",	//地址	/static/weixin/image/face/
    data: {
        0: "微笑",
        1: "撇嘴",
        2: "色",
        3: "发呆",
        4: "得意",
        5: "流泪",
        6: "害羞",
        7: "闭嘴",
        8: "睡",
        9: "大哭",
        10: "尴尬",
        11: "发怒",
        12: "调皮",
        13: "呲牙",
        14: "惊讶",
        15: "难过",
        16: "酷",
        17: "冷汗",
        18: "抓狂",
        19: "吐",
        20: "偷笑",
        21: "可爱",
        22: "白眼",
        23: "傲慢",
        24: "饥饿",
        25: "困",
        26: "惊恐",
        27: "流汗",
        28: "憨笑",
        29: "大兵",
        30: "奋斗",
        31: "咒骂",
        32: "疑问",
        33: "嘘",
        34: "晕",
        35: "折磨",
        36: "衰",
        37: "骷髅",
        38: "敲打",
        39: "再见",
        40: "擦汗",
        41: "抠鼻",
        42: "鼓掌",
        43: "糗大了",
        44: "坏笑",
        45: "左哼哼",
        46: "右哼哼",
        47: "哈欠",
        48: "鄙视",
        49: "委屈",
        50: "快哭了",
        51: "阴险",
        52: "亲亲",
        53: "吓",
        54: "可怜",
        55: "菜刀",
        56: "西瓜",
        57: "啤酒",
        58: "篮球",
        59: "乒乓",
        60: "咖啡",
        61: "饭",
        62: "猪头",
        63: "玫瑰",
        64: "凋谢",
        65: "示爱",
        66: "爱心",
        67: "心碎",
        68: "蛋糕",
        69: "闪电",
        70: "炸弹",
        71: "刀",
        72: "足球",
        73: "瓢虫",
        74: "便便",
        75: "月亮",
        76: "太阳",
        77: "礼物",
        78: "拥抱",
        79: "强",
        80: "弱",
        81: "握手",
        82: "胜利",
        83: "抱拳",
        84: "勾引",
        85: "拳头",
        86: "差劲",
        87: "爱你",
        88: "NO",
        89: "OK",
        90: "爱情",
        91: "飞吻",
        92: "跳跳",
        93: "发抖",
        94: "怄火",
        95: "转圈",
        96: "磕头",
        97: "回头",
        98: "跳绳",
        99: "挥手",
        100: "激动",
        101: "街舞",
        102: "献吻",
        103: "左太极",
        104: "右太极"
    },
    ext: ".gif",
    replaceEmoji: function (f) {
        var b, h, d = Emotion,
			a = d.url,
			c = d.ext,
			g = d.data;
        for (b in g) {
            h = new RegExp("/" + g[b], "g"), f = f.replace(h, '<img src="' + a + b + c + '" alt="mo-' + g[b] + '"/>').replace(/\n/g, "<br />")
        }
        return f
    },
    replaceInput: function (a) {
        return a.replace(/<img.*?alt=["]{0,1}mo-([^"\s]*).*?>/ig, "/$1").replace(/<br.*?>/ig, "\n").replace(/<.*?>/g, "").replace(/&amp;/gi, "&").replace(/&quot;/gi, '"').replace(/&nbsp;/gi, " ").replace(/&copy;/gi, "©").replace(/&reg;/gi, "®")
    },
    getSelection: function () {
        return document.selection ? document.selection : window.getSelection()
    },
    getRange: function (c) {
        var d = Emotion;
        var a = d.getSelection();
        if (!a) {
            return null
        }
        var b = a.getRangeAt ? a.rangeCount ? a.getRangeAt(0) : null : a.createRange();
        return b ? c ? d.containsRange(c, b) ? b : null : b : null
    },
    contains: function (c, a, d) {
        if (!d && c === a) {
            return !1
        }
        if (c.compareDocumentPosition) {
            var b = c.compareDocumentPosition(a);
            if (b == 20 || b == 0) {
                return !0
            }
        } else {
            if (c.contains(a)) {
                return !0
            }
        }
        return !1
    },
    containsRange: function (c, a) {
        var d = Emotion;
        var b = a.commonAncestorContainer || a.parentElement && a.parentElement() || null;
        return b ? d.contains(c, b, !0) : !1
    },
    saveRange: function () {
        Emotion._lastRange = Emotion.getRange()
    },
    resotreRange: function () {
        var c = Emotion._lastRange;
        var b = Emotion;
        if (c) {
            var a = b.getSelection();
            if (a.addRange) {
                a.removeAllRanges(), a.addRange(c)
            } else {
                var d = b.getRange();
                d.setEndPoint("EndToEnd", c), d.setEndPoint("StartToStart", c), d.select()
            }
        }
        return this
    },
    focus: function (c) {
        $(".editArea div").focus();
        var b;
        if (c && (b = Emotion._lastRange)) {
            var d = Emotion.getSelection();
            if (d.addRange) {
                d.removeAllRanges();
                d.addRange(b)
            } else {
                var a = Emotion.getRange();
                a.setEndPoint("EndToEnd", b);
                a.setEndPoint("StartToStart", b);
                a.select()
            }
        }
        return Emotion.resotreRange()
    },
    insertHTML: function (d) {
        Emotion.focus(1);
        var b = Emotion.getRange();
        if (b.createContextualFragment) {
            d += '<img style="width:1px;height:1px;">';
            var f = b.createContextualFragment(d),
				a = f.lastChild;
            b.deleteContents(), b.insertNode(f), b.setEndAfter(a), b.setStartAfter(a);
            var c = Emotion.getSelection();
            c.removeAllRanges(), c.addRange(b), document.execCommand("Delete", !1, null)
        } else {
            b.pasteHTML(d);
            b.collapse(!1);
            b.select()
        }
        Emotion.saveRange();
        return this
    }
};