var Newsarticle={};

Newsarticle.list=[];
Newsarticle.previewdata=[];
Newsarticle.selectednewsdata=[];

Newsarticle.requesturl={
  news:'/weixin/mass/newspapers',
  news2:'/weixin/mass/newspapers2',
  search:'/weixin/mass/searchart'
}

Newsarticle.defaults={
  title:'点击右侧选择文章！',
  src:"/static/weixin/image/moren.jpg",
  content:"点击右侧选择文章！"
};

//初始化
Newsarticle.init=function(data){
  if(data==""){
    return;
  }
  
  Newsarticle.list=data.split(',');
  var news=0;
  var article=0;
  
  for(var i=0;i<Newsarticle.list.length;i++){
    var test=/news_/;
    if(test.test(Newsarticle.list[i])==false){
      article++;
      $(".newslist input[value='"+Newsarticle.list[i]+"']").prop("checked",true);
    }else{
      news++;
    }
    
    if(i==0){
      $(".newslist :radio[name='cover_id'][value='"+Newsarticle.list[i]+"']").prop("checked",true);
    }
  }
  
  $("#article-count").html(article);
  $("#article-news-count").html(news);
  Newsarticle.preview(Newsarticle.list);
  console.log(Newsarticle.list);
}

//添加选择的文章数据
Newsarticle.addnewsdata=function(data){
  for(var i=0;i<data.length;i++){
    var item=data[i];
    var c=Newsarticle.selectednewsdata.length;
    Newsarticle.selectednewsdata[c]=item;
  }
}

//获取文章数据
Newsarticle.getnewsdata=function(id){
  var item=false;
  for(var i=0;i<Newsarticle.selectednewsdata.length;i++){
    if(Newsarticle.selectednewsdata[i].id==id){
      item=Newsarticle.selectednewsdata[i];
      //return;
    }
  }
  
  return item;
}

//选择图文
Newsarticle.select=function(a){
  if($(a).is(":checked")){
    if(Newsarticle.list.length>=5){
      $(a).prop("checked",false);
      J_msgshow('最多可选择五条内容');
      return;
    }
    
    //添加数据到预览数据
    var aim=$(a);
    Newsarticle.addnewsdata([
      {
        id:aim.val(),
        title:aim.attr("title"),
        content:aim.attr("content"),
        src:aim.prop("src"),
        name:aim.prop("name")
      }
    ]);
    
    Newsarticle.list=Newsarticle.add($(a).val(),Newsarticle.list);
    Newsarticle.preview(Newsarticle.list);
    $("#ids").val(Newsarticle.getSelected());
    //console.log(Newsarticle.getSelected());
    
    //第一条 设为封面
    if(Newsarticle.list.length==0){
      $(a).parent().parent().find(":radio").prop("checked");
    }
    
    var count=parseInt($("#article-count").html());
    count++;
    $("#article-count").html(count);
    return;
  }
  
  $(a).removeAttr("checked");
  Newsarticle.list=Newsarticle.del($(a).val(),Newsarticle.list);
  
  if($(a).parent().parent().find(":radio:checked").length>0){
    $(a).parent().parent().find(":radio:checked").removeAttr("checked");
    
    $(".newslist :radio[name='cover_id'][value='"+ Newsarticle.list[0] +"']").prop("checked",true);
  }
  
  var count=parseInt($("#article-count").html());
  count--;
  $("#article-count").html(count);
  Newsarticle.preview(Newsarticle.list);
}

//获取时间戳
Newsarticle.gettime=function(t,f){
  var now=new Date();
  if(t!=""){
    now.setTime(parseInt(t)*1000);
  }
  var year=now.getFullYear();
  var month=now.getMonth()+1;
  var date=now.getDate();
  var hour=now.getHours();
  var minute=now.getMinutes();
  var second=now.getSeconds();
  var str=f.replace("y",year);
  str=str.replace("m",month);
  str=str.replace("d",date);
  str=str.replace("h",hour);
  str=str.replace("i",minute);
  str=str.replace("s",second);
  return str;
}

//选择新闻
Newsarticle.selectnews=function(a){
  var container=$(a).parent().parent().parent();
  var name=$(a).prop("name");
  
  if(Newsarticle.listcheck($(a).val())){
    $(a).removeAttr("checked");
    J_msgshow('该文章已被选择');
    
    if(Newsarticle.list.length==1){
      $(a).parent().parent().find(":radio").removeAttr("checked");
    }
    return;
  }
  
  if(Newsarticle.list.length>=5){
    $(a).prop("checked",false);
    J_msgshow('最多可选择五条内容');
    
    //防止该分类已选时 勾选被去除
    for(var i=0;i<Newsarticle.list.length;i++){
      container.find(":radio[value="+Newsarticle.list[i]+"]").prop("checked",true);
    }
    return;
  }
  
  if(Newsarticle.listcheck($(a).val())){
    Newsarticle.list=Newsarticle.del($(a).val(),Newsarticle.list);
    Newsarticle.preview(Newsarticle.list);
    container.find(":radio").prop("checked",false);
    
    var count=$("#newsarticle-list").find(":checked").length;
    if($("#newsarticle-list").find(":radio[name='cover_id']:checked").length>0){
      count--;
    }
    $("#article-news-count").html(count);
    return;
  }
  
  container.find(":radio").each(function(i,e){
    Newsarticle.list=Newsarticle.del($(e).val(),Newsarticle.list);
    $("#newsarticle-list").find("input[value="+$(e).val()+"]").prop("checked",false);;
    //$(e).prop("checked",false);
  });
  
  $(a).prop("checked",true);
  
  //添加数据到预览数据
  var aim=$(a);
  Newsarticle.addnewsdata([
    {
      id:aim.val(),
      title:aim.attr("title"),
      content:aim.attr("content"),
      src:aim.prop("src"),
      name:aim.prop("name")
    }
  ]);
  
  Newsarticle.list=Newsarticle.add($(a).val(),Newsarticle.list);
  Newsarticle.preview(Newsarticle.list);
  
  var count=$("#newsarticle-list").find(":checked").length;
  if($("#newsarticle-list").find(":radio[name='cover_id']:checked").length>0){
    count--;
  }
  $("#article-news-count").html(count);
}

//设置图文为封面
Newsarticle.setcover=function(a){
  if($(a).parent().parent().find(":checkbox:checked").length<=0){
    $(a).removeAttr("checked");
    $(".newslist :radio[name=cover_id][value="+ Newsarticle.list[0] +"]").prop("checked",true);
    J_msgshow("该图文未被勾选");
    return;
  }
  
  Newsarticle.list=Newsarticle.movetotop($(a).val(),Newsarticle.list);
  Newsarticle.preview(Newsarticle.list);
}

//设置新闻为封面
Newsarticle.setcover2=function(a){
  if($(a).parent().parent().find(":checked").length<=1){
    $(a).removeAttr("checked");
    
    J_msgshow("该图文未被勾选");
    return;
  }
  
  
  Newsarticle.list=Newsarticle.movetotop($(a).val(),Newsarticle.list);
  Newsarticle.preview(Newsarticle.list);
}

Newsarticle.add=function(a,arr){
  var array=[];
  var c=arr.length;
  array=arr;
  array[c]=a;
  return array;
}

Newsarticle.listcheck=function(a){
  var r=false;
  for(var i=0;i<Newsarticle.list.length;i++){
    if(Newsarticle.list[i]==a){
      r=true;
    }
  }
  
  return r;
}

Newsarticle.del=function(a,arr){
  var array=[];
  var c=0;
  
  for(var i=0;i<arr.length;i++){
    if(arr[i]!=a){
      array[c]=arr[i];
      c++;
    }
  }
  
  return array;
}

Newsarticle.addtotop=function(a,arr){
  var array=[];
  array[0]=a;
  var c=1;
  
  for(var i=0;i<arr.length;i++){
    array[c]=arr[i];
    c++;
  }
  
  return array;
}

Newsarticle.movetotop=function(a,arr){
  var array=[];
  array[0]=a;
  var c=1;
  
  for(var i=0;i<arr.length;i++){
    if(arr[i]!=a){
      array[c]=arr[i];
      c++;
    }
  }
  
  return array;
}

//解析文章
Newsarticle.html1=function(){
  var html='';
  return html;
}

//解析新闻
Newsarticle.html2=function(items,id){
  var html='';
  
  var pagesize=5;
  var max=6;
  
  if(items){
    items=Newsarticle.Repeat_remove(items);
    
    //console.log(items);
    for(var i=0; i<items.length;i++){
      var item=items[i];
      var isshow="";
      if(i>4){
        isshow=' style="display:none"';
      }
      var itemhtml='<tr'+isshow+'><td class="j-w-30 txtmid"><input type="radio" value="news_'+item.id+'" name="news-'+id+'" onchange="Newsarticle.selectnews(this)" title="'+item.title+'" content="'+item.desc+'" src="'+ item.thumb +'"></td><td class="img j-w-70"><img src="'+ item.thumb +'"></td><td><a href="javascript:;" onclick="article_contentview('+item.id  +')">'+ item.title +'</a></td><td class="j-w-70 txtmid">'+item.origin+'</td><td class="j-w-70 txtmid">'+Newsarticle.gettime(item.ctime,'m月d日')+'</td><td class="j-w-50 txtmid"><input name="cover_id" value="news_'+item.id+'" type="radio" onchange="Newsarticle.setcover2(this)"></td></tr>';
      html+=itemhtml;
    }
  }else{
    html='<tr><td><div class="j-txtct j-fcolor-2">暂无内容</div></td></tr>';
  }
  
  var page=(items.length/pagesize)>parseInt(items.length/pagesize) ? parseInt(items.length/pagesize)+1 :items.length/pagesize;
  page=page>max ? max :page;
  html= '<table class="type newstype-'+id+' table table-bordered table-hover table-striped j-table-vertival-mid" page="1" total="'+page+'"><tbody>'+html+'</tbody></table>';
  return html;
}

//去除相同的内容
Newsarticle.Repeat_remove=function(data){
  var newdata=[];
  var c=data.length;
  var repeatlist=[];
  var l=0;
  for(var i=0;i<c;i++){
    var item=data[i];
    var check=true;
    for(var j=0;j<c;j++){
      if(Newsarticle.removespace(item.title)==Newsarticle.removespace(data[j].title) && item.id!=data[j].id){
        check=false;
        
        if(Newsarticle.checkinArray(Newsarticle.removespace(item.title),repeatlist)){
          newdata[l]=item;
          l++;
        }
        repeatlist.push(Newsarticle.removespace(item.title));
      }
    }
    
    if(check){
      newdata[l]=item;
      l++;
    }
  }
  //console.log(repeatlist);
  return newdata;
}

Newsarticle.checkinArray=function(a,arr){
  var r=true;
  
  for(var i=0;i<arr.length;i++){
    if(a==arr[i]){
      r=false;
    }
  }
  
  return r;
}

Newsarticle.removespace=function(str){
  str=str.replace("　","");
  str=str.replace(" ","");
  return str;
}

//获取新闻
Newsarticle.loadnews=function(a){
  var id=$(a).attr("targetid");
  $(a).attr("loaded","1");
  
  /*var data=[
    {id:1,title:'标题1',content:'内容1',origin:'来源1'},
    {id:2,title:'标题2',content:'内容2',origin:'来源2'},
    {id:3,title:'标题3',content:'内容3',origin:'来源3'},
    {id:4,title:'标题4',content:'内容4',origin:'来源4'},
    {id:5,title:'标题5',content:'内容5',origin:'来源5'},
  ];
  
  var html=Newsarticle.html2(data,id);
  $("#newsarticle-list").append(html);*/
  
  
  $.post(Newsarticle.requesturl.news,{
    id:id
  },function(data){
    var html=Newsarticle.html2(data, id);
    $("#newsarticle-list").append(html);
    Newsarticle.page(id);
    
    //检测是否有被选择的新闻 *********需移至 post 内************
    for(var i=0;i<Newsarticle.list.length;i++){
      var test=/news_/;
      if(test.test(Newsarticle.list[i])){
        $(".newstype-"+id).find("input[value='"+Newsarticle.list[i]+"']").prop("checked",true);
      }
      
      if(i==0){
        $(".newstype-"+id).find("[name='cover_id'][value='"+Newsarticle.list[i]+"']").prop("checked",true);
      }
    }
  },"json");
}

Newsarticle.loadnews2=function(a){
  var id=$(a).attr("targetid");
  $(a).attr("loaded","1");
  
  /*var data=[
    {id:1,title:'标题1',content:'内容1',origin:'来源1'},
    {id:2,title:'标题2',content:'内容2',origin:'来源2'},
    {id:3,title:'标题3',content:'内容3',origin:'来源3'},
    {id:4,title:'标题4',content:'内容4',origin:'来源4'},
    {id:5,title:'标题5',content:'内容5',origin:'来源5'},
  ];
  
  var html=Newsarticle.html2(data,id);
  $("#newsarticle-list").append(html);*/
  
  
  $.post(Newsarticle.requesturl.news2,{
    id:id
  },function(data){
    var html=Newsarticle.html2(data, id);
    $("#newsarticle-list").append(html);
    Newsarticle.page(id);
    
    //检测是否有被选择的新闻 *********需移至 post 内************
    for(var i=0;i<Newsarticle.list.length;i++){
      var test=/news_/;
      if(test.test(Newsarticle.list[i])){
        $(".newstype-"+id).find("input[value='"+Newsarticle.list[i]+"']").prop("checked",true);
      }
      
      if(i==0){
        $(".newstype-"+id).find("[name='cover_id'][value='"+Newsarticle.list[i]+"']").prop("checked",true);
      }
    }
  },"json");
}

//分页
Newsarticle.page=function(id){
  var aim=$(".newstype-"+id);
  
  var total=aim.attr("total");
  var page=parseInt(aim.attr("page"));
  
  $(".j-newsscrolllist .pages li").removeClass("active");
  $(".j-newsscrolllist .pages li").eq(page-1).addClass("active");
  $(".j-newsscrolllist .pages").attr("t",id);
  $(".j-newsscrolllist .pages li").each(function(i,e){
    if(i+1>total){
      $(e).hide();
    }else{
      $(e).show();
    }
  });
}

Newsarticle.pagebtn=function(page,a){
  //console.log(page);
  
  var id=$(".j-newsscrolllist .pages").attr("t");
  $(".j-newsscrolllist .pages li").removeClass("active");
  $(a).addClass("active");
  var aim =$(".newstype-"+id);
  aim.find("tr").hide();
  aim.attr("page",page);
  
  for(i=1;i<6;i++){
    var q=page*5-i;
    $(".newstype-"+id+" tr").eq(q).show();
  }
}
//预览 获取选择项
Newsarticle.getSelected=function(){
  return Newsarticle.list.join(',');
}

//截取新闻id
Newsarticle.getnewsid=function(str){
  str=str.replace("news_",'');
  return parseInt(str);
}

//搜索文章
Newsarticle.keywordsearch=function(a){
  if(Newsarticle.keywordsearch.init!=1){
    Newsarticle.keywordsearch.init=1;
    $("#newsarticle-list").append('<div class="type newstype-search"><table class="table table-bordered table-hover table-striped j-table-vertival-mid"><tbody></tbody></table></div>');
  }
  //$(a).prop("disabled","disabled").html("搜索中");
  $(".newstype-search tbody").html('<tr><td style="text-align:center; color:#CCC">搜索中...</td></tr>');
  
  var keyword=$(a).parent().find("input").val();

  $.post(Newsarticle.requesturl.search,{
    keyword:keyword,
  },function(json){
    if(json.length>0){
      json=Newsarticle.Repeat_remove(json);
      
      $(".newstype-search tbody").html("");
      for(var i=0;i<json.length;i++){
        var item=json[i];
        var html='<tr><td class="j-w-30 txtmid"><input type="checkbox" value="news_'+item.id+'" name="news-search" onchange="Newsarticle.search_select(this)" title="'+item.title+'" content="'+item.desc+'" src="'+ item.thumb +'"></td><td class="img j-w-70"><img src="'+ item.thumb +'"></td><td><a href="javascript:;" onclick="article_contentview('+item.id+')">'+ item.title +'</a></td><td class="j-w-70 txtmid">'+item.origin+'</td><td class="j-w-70 txtmid">'+Newsarticle.gettime(item.ctime,'m月d日')+'</td><td class="j-w-50 txtmid"><input name="cover_id" value="news_'+item.id+'" type="radio" onchange="Newsarticle.search_setCover(this)"></td></tr>';
        $(".newstype-search tbody").append(html);
      }
      $("#Newsarticle-search-count").html(json.length);
    }else{
      $(".newstype-search tbody").html('<tr><td style="text-align:center; color:#CCC">没有搜索到相关内容</td></tr>');
      $("#Newsarticle-search-count").html(0);
    }
  },"json");
}

Newsarticle.search_select=function(a){
  var container=$(a).parent().parent();
  
  if($(a).is(":checked")){
    if(Newsarticle.list.length>=5){
      $(a).removeAttr("checked");
      J_msgshow('最多可选择五条内容');
      if(Newsarticle.list.length==1){
        container.find(":radio").removeAttr("checked");
      }
      return;
    }
    
    
    Newsarticle.list=Newsarticle.del($(a).val(),Newsarticle.list);
    $(".newslist input[value="+$(a).val()+"]").removeAttr("checked");
    $(a).prop("checked",true);
    
    //添加数据到预览数据
    var aim=$(a);
    Newsarticle.addnewsdata([
      {
        id:aim.val(),
        title:aim.attr("title"),
        content:aim.attr("content"),
        src:aim.prop("src"),
        name:aim.prop("name")
      }
    ]);
    
    Newsarticle.list=Newsarticle.add($(a).val(),Newsarticle.list);
    Newsarticle.preview(Newsarticle.list);
    $("#ids").val(Newsarticle.getSelected());
    //console.log(Newsarticle.getSelected());
    
    //第一条 设为封面
    if(Newsarticle.list.length==0){
      container.find("[name=cover_id]").prop("checked",true);
    }
    
    var count=$("#newsarticle-list").find(":checked").length;
    if($("#newsarticle-list").find(":radio[name='cover_id']:checked").length>0){
      count--;
    }
    $("#article-news-count").html(count);
    return;
  }
  
  $(a).removeAttr("checked");
  Newsarticle.list=Newsarticle.del($(a).val(),Newsarticle.list);
  
  var count=$("#newsarticle-list").find(":checked").length;
  if($("#newsarticle-list").find(":radio[name='cover_id']:checked").length>0){
    count--;
  }
  
  $("#article-news-count").html(count);
  Newsarticle.preview(Newsarticle.list);
}

Newsarticle.search_setCover=function(a){
  if($(a).parent().parent().find(":checked").length<=1){
    $(a).removeAttr("checked");
    J_msgshow("该图文未被勾选");
    return;
  }
  
  $(".newslist :radio[name=cover_id]").removeAttr("checked");
  $(a).prop("checked",true);
  Newsarticle.list=Newsarticle.movetotop($(a).val(),Newsarticle.list);
  Newsarticle.preview(Newsarticle.list);
}

//生成预览
Newsarticle.preview=function(list){
  $("#ids").val(Newsarticle.getSelected());
  
  if(list.length==1 && $(".j-newsscrolllist input:checked").length>0){
    //var content=$(".j-newsscrolllist input:checked").attr("content");
    //var src=$(".j-newsscrolllist input:checked").attr("src");
    //var title=$(".j-newsscrolllist input:checked").attr("title");
    //var id=$(".j-newsscrolllist input:checked").val();
    //$(".j-newsscrolllist input:checked").parent().parent().find(":radio").attr("checked",true);
    //排除封面重复 跳转到另一个
    
    var item=Newsarticle.getnewsdata(list[0]);
    var content=item.content;
    var src=item.src;
    var title=item.title;
    var id=item.id;
    
    $(".newslist :radio[name=cover_id][value='"+ Newsarticle.list[0] +"']").each(function(i,e){
      if($(e).parent().parent().find("input").eq(0).is(":checked")){
        $(e).prop("checked",true);
      }
    });
        
    $("#box-zixun-1 .news_title").html(title);
    $("#box-zixun-1 .news_content").html(content);
    $("#box-zixun-1 .delete").attr("tid",id);
    $("#box-zixun-1 p>.img").css("background-image","url("+src+")");
    
    $("#box-zixun-1").show();
    $("#box-zixun-2").hide();
  }else if(list.length>=2){
    var str, c=0, code="", _src, _title, html;
    
    //alert(lists.length);
    for(var i=0;i<list.length; i++){
      /*if($(".newslist input[value='"+list[i]+"']").length>0){
        _src=$(".newslist input[value='"+list[i]+"']").attr("src");
        _title=$(".newslist input[value='"+list[i]+"']").attr("title");
      }else{
        _src=Newsarticle.defaults.src;
        _title='（新闻'+Newsarticle.getnewsid(list[i])+'）内容未获取';
      }*/
      
      var item=Newsarticle.getnewsdata(list[i]);
      var _src=item.src;
      var _title=item.title;
      var id=item.id;
      
      //alert(_title+","+str);
      if(c==0){
        //$(".newslist input[value='"+list[i]+"']").parent().parent().find(":radio").attr("checked",true);
        //排除封面重复 跳转到另一个
        $(".newslist :radio[name=cover_id][value="+ Newsarticle.list[0] +"]").each(function(i,e){
          if($(e).parent().parent().find("input").eq(0).is(":checked")){
            $(e).prop("checked",true);
          }
        });
        
        c=1;
        html='<a style="background-image:url('+_src+')" class="img"><div onclick="Newsarticle.preview_delete(this)" class="delete" tid="'+id+'"><table><tbody><tr><td style="vertical-align:middle">点击删除</td></tr></tbody></table></div><span class="news_title"><div class="j-w95 j-pct">'+_title+'</div></span></a><div class="j-m j-h5"></div>';
        $("#box-zixun-2 .first").html(html);
      }else{
        html='<a class="lista"><div onclick="Newsarticle.preview_delete(this)" class="delete" tid="'+id+'"><table><tbody><tr><td style="vertical-align:middle">点击删除</td></tr></tbody></table></div><table class="j-vtcl-top" width="100%" cellpadding="0" cellspacing="0"><tbody><tr><td>'+_title+'</td><td class="j-w-50"><img src="'+_src+'"></td></tr></tbody></table></a>';
        code+=html;
      }
    }
    $("#box-zixun-2 .listas").html(code);
    
    $("#box-zixun-2").show();
    $("#box-zixun-1").hide();
  }else{
    //恢复默认样式
    $("#box-zixun-1 .news_title").html(Newsarticle.defaults.title);
    $("#box-zixun-1 .news_content").html(Newsarticle.defaults.content);
    $("#box-zixun-1 p>.img").css("background-image","url("+Newsarticle.defaults.src+")");
    $(".newslist").find(":radio").removeAttr("checked");
    $("#box-zixun-1 .delete").attr("tid",0);
    
    $("#box-zixun-1").show();
    $("#box-zixun-2").hide();
  }
}

Newsarticle.preview_delete=function(a){
  var aim=$(a);
  var id=aim.attr("tid");
  
  if(id==0){
    return;
  }
  
  var aim2=$(".newslist input[value='"+id+"']");
  aim2.removeAttr("checked");
  aim2.parent().parent().find("[name='cover_id']").removeAttr("checked");
  
  Newsarticle.list=Newsarticle.del(id,Newsarticle.list);
  Newsarticle.preview(Newsarticle.list);
  
  //更新统计
  var count=$("#articlelist-select").find(":checked").length;
  if($("#articlelist-select").find("[name='cover_id']:checked").length>0){
    count--;
  }
  $("#article-count").html(count);
  
  var count=$("#newsarticle-list").find(":checked").length;
  if($("#newsarticle-list").find("[name='cover_id']:checked").length>0){
    count--;
  }
  $("#article-news-count").html(count);
  
}