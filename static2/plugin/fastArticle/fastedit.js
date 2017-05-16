J_FastEdit={};

J_FastEdit.init=function(a,opt){
  opt.manage = opt.manage!=null ? opt.manage : true;
  opt.edit   = opt.edit!=null ? opt.edit : true;
  
  $(a).find("[fast-type]").each(function(i,e){
    var type=$(this).attr("fast-type");
    switch(type){
      case 'left':
        $(this).wrap(J_FastEdit.defaultHTML.container);
        $(this).parent().addClass('left');
        if(opt.manage){
          J_FastEdit.addmanager($(this).parent().parent()[0]);
        }
        $(this).prop("contenteditable",true);
        break;
      
      case 'right':
        $(this).wrap(J_FastEdit.defaultHTML.container);
        $(this).parent().addClass('right');
        if(opt.manage){
          J_FastEdit.addmanager($(this).parent().parent()[0]);
        }
        $(this).prop("contenteditable",true);
        break;
        
      case "date":
        $(this).wrap(J_FastEdit.defaultHTML.container);
        $(this).parent().addClass('right');
        if(opt.manage){
          J_FastEdit.addmanager($(this).parent().parent()[0]);
        }
        $(this).prop("contenteditable",true);
        break;
        
      case 'p':
        $(this).wrap(J_FastEdit.defaultHTML.container);
        if(opt.manage){
          J_FastEdit.addmanager($(this).parent().parent()[0]);
        }
        $(this).prop("contenteditable",true);
        break;
        
      case 'img':
        var src=$(this).find(".aim-img").prop("src");
        $(this).before(J_FastEdit.defaultHTML.img);
        $(this).prev(".fast-item").find(".aim-img").prop("src",src);
        
        if($(this).find("div").length>0){
          var html=$(this).find("div").first().html();
          J_FastEdit.addimg_desc($(this).prev(".fast-item")[0]);
          $(this).prev(".fast-item").find('[contenteditable]').html(html);
        }
        
        if(opt.manage){
          J_FastEdit.add_imgmanager($(this).prev(".fast-item")[0]);
        }else{
          J_FastEdit.add_imgmanager2($(this).prev(".fast-item")[0]);
        }
        $(this).remove();
        break;
    }
  });
  
  if(opt.edit){
    $(a).append('<div class="fast-btns"><span class="fast-addbtn" onclick="J_FastEdit.addtext(this)">插入内容</span><span class="fast-addbtn" onclick="J_FastEdit.addimg(this)">插入图片</span></div>');
  }
}

//默认html
J_FastEdit.defaultHTML={
  p:'<div class="fast-item"><div class="fast-content"><div fast-type="p" contenteditable style="text-indent: 2em">请编辑内容</div></div></div>',
  
  date:function(){
    var d=new Date();
    var dl=d.getFullYear()+'年'+(d.getMonth()+1)+'月'+d.getDate()+'日';
    return dl;
  },
  
  img:'<div class="fast-item"><div class="fast-content img"><div fast-type="img"><label><img class="aim-img" src="/static2/image/head_pic.jpg"><div class="addicon"><table><tbody><tr><td><img src="/static2/image/u1037.png"></td></tr></tbody></table></div><input type="file" onchange="J_FastEdit.upload(this)"></label></div> </div></div>',
  
  img_desc:'<div style="text-align:center; width:80%; margin:0 auto" contenteditable>插入图片描述</div>',
  
  container:'<div class="fast-item"><div class="fast-content"></div></div>'
};

//类型更换
J_FastEdit.changelist={
  left:function(a){
    $(a).find(".fast-content").addClass("left").find("[fast-type]").css("text-align","left").attr("fast-type","left").html('请编辑内容');
  },
  
  //落款
  right:function(a){
    $(a).find(".fast-content").addClass("right").find("[fast-type]").css("text-align","right").attr("fast-type","right").html('请编辑内容');
  },
  
  //文章
  p:function(a){
    $(a).find("[fast-type]").attr("fast-type","p").css('text-indent','2em').html('请编辑内容');
  },
  
  date:function(a){
    $(a).find("[fast-type]").html(J_FastEdit.defaultHTML.date()).attr("fast-type","date");
  }
};

J_FastEdit.typechange=function(a,type){
  if($(a).parent().parent().find("[fast-type]").attr("fast-type")==type){
    return false;
  }
  
  $(a).parent().parent().find(".fast-content").removeClass('left').removeClass("right").find("[fast-type]").prop("style","");
  var aim=$(a).parent().parent()[0];
  
  switch(type){
    case "left":
      J_FastEdit.changelist.left(aim);
      break;
    
    case "right":
      J_FastEdit.changelist.right(aim);
      break;
    
    case "date":
      J_FastEdit.changelist.right(aim);
      J_FastEdit.changelist.date(aim);
      break;
      
    case "p":
      J_FastEdit.changelist.p(aim);
      break;
  }
}

//添加编辑按钮
J_FastEdit.addmanager=function(a){
  var html='<div class="fast-del" onclick="J_FastEdit.deleteItem(this)">删除</div><div class="fast-manager"><a>更换</a><a href="javascript:;" onclick="J_FastEdit.typechange(this,'+"'left'"+')">称谓</a><a href="javascript:;" onclick="J_FastEdit.typechange(this,'+"'p'"+')">段落</a><a href="javascript:;" onclick="J_FastEdit.typechange(this,'+"'right'"+')">落款</a><a href="javascript:;" onclick="J_FastEdit.typechange(this,'+"'date'"+')">日期</a></div>';
  $(a).append(html);
}

J_FastEdit.add_imgmanager=function(a){
  var html='<div class="fast-del" onclick="J_FastEdit.deleteItem(this)">删除</div><div class="fast-manager"><a href="javascript:;" onclick="J_FastEdit.addimg_desc_menu(this)">图片描述</a></div>';
  $(a).append(html);
}

J_FastEdit.add_imgmanager2=function(a){
  var html='<div class="fast-manager"><a href="javascript:;" onclick="J_FastEdit.addimg_desc_menu(this)">图片描述</a></div>';
  $(a).append(html);
}

//获取编辑器内HTML
J_FastEdit.getcode=function(a){
  $("body").append('<div id="FastEdit-htmldeal" style="display:none"></div>');
  $(a).find(".fast-content").each(function(i,e){
    if($(this).is(".img")){
      var src=$(this).find(".aim-img").prop("src");
      var desc="";
      if($(this).find("[contenteditable]").length>0){
        desc='<div style="text-align:center; width:80%; margin:0 auto">'+$(this).find("[contenteditable]").html()+'</div>';
      }
      var html='<div fast-type="img"><img class="aim-img" src="'+ src +'">'+desc+'</div>';
      $("#FastEdit-htmldeal").append(html);
    }else{
      $("#FastEdit-htmldeal").append($(this).html());
    }
  });
  
  $("#FastEdit-htmldeal [contenteditable]").removeAttr('contenteditable');
  var html=$("#FastEdit-htmldeal").html();
  $("#FastEdit-htmldeal").remove();
  
  //console.log(html);
  return html;
}

//添加图片
J_FastEdit.addimg=function(a){
  $(a).parent().parent().find(".fast-editor").append(J_FastEdit.defaultHTML.img);
  J_FastEdit.add_imgmanager($(a).parent().parent().find(".fast-item").last()[0]);
}

//添加和去除图片描述
J_FastEdit.addimg_desc=function(a){
  if($(a).find('[contenteditable]').length>0){
    $(a).find('[contenteditable]').remove();
    return false;
  }
  
  $(a).find("[fast-type]").append(J_FastEdit.defaultHTML.img_desc);
}

J_FastEdit.addimg_desc_menu=function(a){
  J_FastEdit.addimg_desc($(a).parent().parent()[0]);
}

//添加内容
J_FastEdit.addtext=function(a){
  $(a).parent().parent().find(".fast-editor").append(J_FastEdit.defaultHTML.p);
  J_FastEdit.addmanager($(a).parent().parent().find(".fast-item").last()[0]);
}

//删除
J_FastEdit.deleteItem=function(a){
  $(a).parent().remove();
}

//图片上传
J_FastEdit.upload=function(a){
  if(J_Uploader._init!=1){
    console.log('缺少上传组建upload.js');
    return false;
  }
  
  if(J_Uploader.isruning==true){
    J_msgshow("正在上传请稍等");
    return false;
	}
  
  upload.process=$(a).parent();
  J_Uploader.reset();
  J_Uploader.upload_url='/weixin/image/up.html'; //上传地址
  
  J_Uploader.process=function(p){
    //console.log(p);
    upload.process.find("td").html(p+"%");
  }
  
  J_Uploader.callback	=function(data){
    if(data.statu!=1){
      upload.process.find("td").html(J_Uploader.error[data.statu]);
      return false;
    }
    
    upload.process.find("td").html('<img src="/static2/image/u1037.png">');
    upload.process.find('.aim-img').prop("src",data.path+'?t='+Math.random());
  };
  
  J_Uploader.uploading=function(){
    
  }

  J_Uploader.file=a.files;
  J_Uploader.nowtype="image";
  J_Uploader.addlist();
}