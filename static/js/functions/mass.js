var Mass={};

//公共函数
Mass.G={};

//获取今日当前时间的小时
Mass.G.getAbbandontime=function(){
  return new Date().getHours();
}


//设置发送时间初始化
Mass.setTime=function(defaults){
  if(Mass.setTime.init!=1){
    Mass.G.defaultTime=defaults.sendingtime;
    Mass.setTimeHTML($(".timetable").eq(0)[0]);
    Mass.setTimeHTML($(".timetable").eq(1)[0]);
    
    $(".timetable td a").on("click",function(){
      Mass.setTime_a_select(this);
    });

    Mass.setTime.init=1;
  }
  
  //禁用过期时间
  var abbandon=Mass.G.getAbbandontime();
  Mass.abbandontime(abbandon,'#timesetting');
  
  if(defaults.sendingtime>=0 && defaults.sendingtime<=24){
    $("#default-timesetting td a").eq(defaults.sendingtime).addClass("active");
    if(defaults.newsendtime>=0 && defaults.newsendtime<=24){
      //临时设置了新时间
      $("#today-timeshow").html(defaults.newsendtime+':00');
      $("#timesetting td a").eq(defaults.newsendtime).addClass("active");
      if($(".masslist .item").eq(0).find(".timesetting").length>0){
        $(".masslist .item .timesetting .control").html('已设置到 今日'+defaults.newsendtime+':00发布');
      }else{
        var html='<div class="timesetting" onclick="J_show('+"'"+'timesetting'+"'"+')"><div class="control">已设置到 今日'+defaults.newsendtime+':00发布</div></div>';
        $(".masslist .item").eq(0).find(".hotchange .commondbtns").before(html);
        //console.log(1);
      }
    }else{
      $("#today-timeshow").html(defaults.sendingtime+':00');
      $("#timesetting td a").eq(defaults.sendingtime).addClass("active");
    }
  }
}

//刷新禁用时间
Mass.abbandon_refresh=function(){
  
}

//禁用时间
Mass.abbandontime=function(t,a){
  $(a).find("td a").removeClass("lock");
  $(a).find("td a").each(function(i,e){
    if(i<=t){
      $(e).addClass("lock");
    }
  });
}

//时间被选择
Mass.setTime_a_select=function(a){
  if($(a).is(".active")){
    $(a).removeClass("active");
    return;
  }
  $(a).parent().parent().parent().find("a").removeClass("active");
  $(a).addClass("active");
}

//前移
Mass.move_pre=function(a){
  if(Mass.movelock){
    return false;
  }
  
  var aim=$(a).parent().parent().parent().find(".articles");
  var c=$(a).parent().parent().parent().parent();
  
  var aim2=c.prev().find(".articles");
  var c2=c.prev();
  
  if(c2.is(".issended")){
    return false;
  }
  
  Mass.movelock=true;
  $.post("/weixinv3/sendqueue/dataexchange",{
    pid:aim.attr("qid"),
    nid:aim2.attr("qid"),
    step:-1
  },function(status){
    Mass.movelock=false;
    if(status==1){
      c.find(".time").after(aim2.clone());
      c2.find(".time").after(aim.clone());
      
      aim2.remove();
      aim.remove();
    }else{
      J_msgshow("网络错误，请重试");
    }
  },"html");
  
}

//后移
Mass.move_after=function(a){
  if(Mass.movelock){
    return false;
  }
  
  var aim=$(a).parent().parent().parent().find(".articles");
  var c=$(a).parent().parent().parent().parent();
  var aim2=c.next().find(".articles");
  var c2=c.next();
  
  Mass.movelock=true;
  $.post("/weixinv3/sendqueue/dataexchange",{
    pid:aim.attr("qid"),
    nid:aim2.attr("qid"),
    step:1
  },function(status){
    Mass.movelock=false;
    if(status==1){
      c.find(".time").after(aim2.clone());
      c2.find(".time").after(aim.clone());
      
      aim2.remove();
      aim.remove();
    }else{
      J_msgshow("网络错误，请重试");
    }
  },"html");
}

//删除
Mass.del=function(a){
  var aim=$(a).parent().parent().parent();
  
  $.post("/weixinv3/sendqueue/del",{
    id:aim.find(".articles").attr("qid")
  },function(status){
    if(status==1){
      aim.find(".articles").remove();
    }else{
      J_msgshow("网络错误，请重试");
    }
  },"html");
  //aim.find(".commands").addClass("hide");
}

//重新排版
Mass.rebuild=function(a){
  var aim=$(a).parent().parent().parent();
  aim.find(".articles").remove();
}

//时间设置 生成时间
Mass.setTimeHTML=function(a){
  var table=document.createElement("table");
  var tbody=document.createElement("tbody");
  
  var t=0;
  for(var i=0;i<3;i++){
    var tr=document.createElement("tr");
    for(var j=0;j<8;j++){
      var td=document.createElement("td");
      td.innerHTML='<a t="'+t+'">'+t+':00</a>';
      tr.appendChild(td);
      t++;
    }
    tbody.appendChild(tr);
  }
  
  table.appendChild(tbody);
  a.appendChild(table);
}

//立即发送
Mass.sendnow=function(a){
  if(Mass.sendnow.lock){
    return;
  }
  
  $(a).html("提交中");
  Mass.sendnow.lock=true;
  $.post("/weixinv3/sendqueue/nowsend",{
    id:$(".masslist .item").eq(0).find(".articles").attr("qid")
  },function(status){
    Mass.sendnow.lock=false;
    
    if(status==1){
      J_hide("confirm-send");
      $(a).html("确定");
      $(".masslist .item").eq(0).addClass("issended").find(".nodata").html("已发布");
    }else{
      J_msgshow("网络错误，请重试");
    }
  });
}

//设置默认发送时间
Mass.setDefaulttime=function(a){
  $(a).addClass("lock").html("提交中");
  var time=$("#default-timesetting .active").attr("t");
  
  $.post("/weixinv3/worker/setsendtime",{
    time:time
  },function(status){
    $(a).removeClass("lock").html("确定");
    
    if(status==1){
      $("#default-timesetting").hide();
      $("#default-time-txt").html("每天默认发布时间 "+time+":00");
      Mass.G.defaultTime=time;
    }else{
      J_msgshow("网络错误，请重试");
    }
  },"html");
}

//取消临时更改发送时间
Mass.cancelSendtime=function(){
  $.post("/weixinv3/worker/setonesendtime",{
    time:-1,
    id:$(".masslist .item").eq(0).find(".articles").attr("qid")
  },function(status){
    if(status==1){
      $("#today-timeshow").html(Mass.G.defaultTime);
      $("#timesetting td a").removeClass("active");
      $(".masslist .item").eq(0).find(".hotchange .timesetting").remove();
    }else{
      J_msgshow("网络错误，请重试");
    }
  },"html");
}

//设置临时更改发送时间
Mass.setSendtime=function(a){
  if($("#timesetting td .active").length<=0){
    J_msgshow("请选择发送时间");
    return false;
  }
  
  $(a).addClass("loading");
  
  $.post("/weixinv3/worker/setonesendtime",{
    time:$("#timesetting td .active").attr("t"),
    id:$(".masslist .item").eq(0).find(".articles").attr("qid")
  },function(status){
    $(a).removeClass("loading");
    if(status==1){
      $("#timesetting").hide();
      var strtime=$("#timesetting .active").html()
      var statement='已设置到 今日'+strtime+'发布';
      $("#today-timeshow").html(strtime);
      if($(".masslist .item").eq(0).find(".timesetting").length>0){
        $(".masslist .item .timesetting .control").html(statement);
      }else{
        var html='<div class="timesetting" onclick="J_show('+"'"+'timesetting'+"'"+')"><div class="control">'+statement+'</div></div>';
        $(".masslist .item").eq(0).find(".hotchange .commondbtns").before(html);
      }
      return;
    }else{
      J_msgshow("网络错误，请重试");
    }
  },"html");
  
  /*setTimeout(function(){
    $(a).removeClass("loading");
    $("#timesetting").hide();
    var statement='已设置到 今日'+$("#timesetting .active").html()+'发布';
    
    if($(".masslist .item").eq(0).find(".timesetting").length>0){
      $(".masslist .item .timesetting .control").html(statement);
    }else{
      var html='<div class="timesetting" onclick="J_show('+"'"+'timesetting'+"'"+')"><div class="control">'+statement+'</div></div>';
      $(".masslist .item").eq(0).find(".hotchange .commondbtns").before(html);
    }
  },2000);*/
}