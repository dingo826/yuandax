J_Bmap = function(){
  J_Bmap.map;
  J_Bmap.config={};
  J_Bmap.local; //智能搜索对象
  J_Bmap.Myposition_icon = new BMap.Icon('data:image/png;base64,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',new BMap.Size(25,25));

  //清空覆盖物 不清空的话请设置不被清楚
  J_Bmap.clearOverlay = function(){
    J_Bmap.map.clearOverlays();
  }

  //放大
  J_Bmap.zoomin = function(){
    J_Bmap.map.zoomIn();
  }

  //缩小
  J_Bmap.zoomout = function(){
    J_Bmap.map.zoomOut();
  }

  //智能搜索完成
  J_Bmap.local_complete = function(){
    //alert("x");
    if(J_Bmap.local.getResults()){
      var p = J_Bmap.local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
      J_Bmap.map.centerAndZoom(p, 18);
      J_Bmap.map.addOverlay(new BMap.Marker(p));            //添加标注
    }
  }

  //搜索函数
  J_Bmap.search = function(keyword){
    J_Bmap.local = new BMap.LocalSearch(J_Bmap.map, {
      onSearchComplete: J_Bmap.local_complete
    });
    J_Bmap.clearOverlay();
    J_Bmap.local.search(keyword);
  }

  //解析点为地址 obj.province + ", " + obj.city + ", " + obj.district + ", " + obj.street + ", " + obj.streetNumber
  J_Bmap.analysisPoint = function(point,callback){
    var geoc = new BMap.Geocoder();
    geoc.getLocation(point,function(rs){
      callback(rs.addressComponents);
    }); 
  }
  
  //定位
  J_Bmap.location =  new BMap.Geolocation();

  //定位 成功执行回调函数
  J_Bmap.locate =function(callback){
    J_Bmap.location.getCurrentPosition(function(r){
      if(this.getStatus() == BMAP_STATUS_SUCCESS){
        callback(r);
      }
      else {
        alert('定位失败:'+this.getStatus());
      }
    },{enableHighAccuracy: true});
  };

  //添加右键菜单 items=[{text:'',callback:funtion(){}}];
  J_Bmap.addrightmenu = function(items){
    var menu = new BMap.ContextMenu();
    for(var i in items){
      menu.addItem(new BMap.MenuItem(items[i].text,items[i].callback,100));
    }
    J_Bmap.map.addContextMenu(menu);
  }

  //定位用户位置
  J_Bmap.getMyposition = function(){
    J_Bmap.locate(function(r){
      //J_Bmap.map.panTo(r.point);
      J_Bmap.clearOverlay();
      J_Bmap.map.centerAndZoom(r.point,18);
      var marker = new BMap.Marker(r.point,{icon:J_Bmap.Myposition_icon});
      J_Bmap.map.addOverlay(marker);
    });
  }

  J_Bmap.tool={
    search:function(){
      var div = document.createElement("div");
      div.className = "j-map-searchbox";
      div.innerHTML = '<table><tbody><tr><td><span class="icon-direct-left" style="margin-left:15px" onclick="mapback()"></span></td><td><input type="search" id="j-map-searchbox-input" placeholder="请输入搜索地点"></td><td width="90"><a id="J-Bmap-searchbtn">搜索</a></td></tr></tbody></table>';
      document.body.appendChild(div);
      document.getElementById("J-Bmap-searchbtn").onclick = function(){
        J_Bmap.search(J_Bmap.getbyid("j-map-searchbox-input").value);
      }

      //搜索框绑定 建立一个自动完成的对象
      var ac = new BMap.Autocomplete(    
        {"input" : "j-map-searchbox-input"
        ,"location" : J_Bmap.map
      });

      ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
        var str = "";
          var _value = e.fromitem.value;
          var value = "";
          if (e.fromitem.index > -1) {
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
          }    
          str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
          
          value = "";
          if (e.toitem.index > -1) {
            _value = e.toitem.value;
            value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
          }    
          str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
          //J_Bmap.getbyid("searchResultPanel").innerHTML = str;
        });

        ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
          var _value = e.item.value;
          var myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
          //J_Bmap.getbyid("searchResultPanel").innerHTML = "onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
          alert("鼠标点击下拉列表后的事件");
          J_Bmap.search(myValue);
        });
    },
    menu:function(){
      var div = document.createElement("div");
      div.className = "j-map-menu";
      div.innerHTML = '<div class="j-map-menu-item" id="j-map-f-dingwei"><a class="j-map-icon j-map-icon-1"></a></div><div class="j-map-menu-item" id="j-map-f-zoomin"><a class="j-map-icon-plu">＋</a></div><div class="j-map-menu-item" id="j-map-f-zoomout"><a class="j-map-icon-min">－</a></div>';
      document.getElementById(J_Bmap.config.id).appendChild(div);
      document.getElementById('j-map-f-dingwei').onclick = function(){
        J_Bmap.getMyposition();
      }

      document.getElementById('j-map-f-zoomin').onclick=function(){
        J_Bmap.zoomin();
      }

      document.getElementById('j-map-f-zoomout').onclick=function(){
        J_Bmap.zoomout();
      }
    }
  };

  //搜索徒步路线
  J_Bmap.searchWalk = function(p1,p2){
    var walking = new BMap.WalkingRoute(map, {renderOptions:{map: map, autoViewport: true}});
	  walking.search(p1, p2);
  }

  //绘制不规则形状
  J_Bmap.drawshap = function(pos,opt){
    if(pos.length<1){
      return false;
    }
    opt = opt ? opt : {};
    var border_color    = opt.border_color ? opt.border_color : "#0ae";
    var opitical        = opt.opitical ? opt.opitical : 0.5;
    var border_weight   = opt.weight ? opt.weight : 2;

    var polygon = new BMap.Polygon(pos, {strokeColor:border_color, strokeWeight:border_weight, strokeOpacity:opitical});
    J_Bmap.map.addOverlay(polygon);
    if(opt.edit){
      polygon.enableEditing();
    }
  }

  //添加标签
  J_Bmap.marker = function(point){
     var marker = new BMap.Marker(point);
     J_Bmap.map.addOverlay(marker);
     return marker;
  }
  
  //添加自定义标签
  J_Bmap.markerDIY = function(opt,point){
    var marker = new BMap.Marker(point,{icon:new BMap.Icon(opt.url,new BMap.Size(opt.w,opt.h))});
    if(opt.undelete){
      marker.disableMassClear();
    }
    J_Bmap.map.addOverlay(marker);
    return marker;
  }

  J_Bmap.getbyid = function(id){
    return document.getElementById(id);
  };

  J_Bmap.init = function(id,opt){
    J_Bmap.map = new BMap.Map(id);    // 创建Map实例
    J_Bmap.config.id=id;
    if(opt.center){
      J_Bmap.map.centerAndZoom(opt.center,18);
      J_Bmap.map.setCurrentCity("");
    }else{
      J_Bmap.location.getCurrentPosition(function(r){
      if(this.getStatus() == BMAP_STATUS_SUCCESS){
          J_Bmap.map.setCurrentCity(r["address"].city);
          J_Bmap.map.centerAndZoom(r.point, 18);
          var marker = new BMap.Marker(r.point,{icon:J_Bmap.Myposition_icon});
          J_Bmap.map.addOverlay(marker);
        }
        else {
          J_Bmap.map.centerAndZoom(new BMap.Point(116.404, 39.915), 18);
          J_Bmap.map.setCurrentCity("");
        }        
      },{enableHighAccuracy: true});
    }

    if(opt.tool){
      for(var i in opt.tool){
        //alert(i);
        J_Bmap.tool[opt.tool[i]]();
      }
    }

    //是否开启鼠标滚动
    if(opt.mousewheel != false){
      J_Bmap.map.enableScrollWheelZoom(true);
    }

  };


};(function(){J_Bmap();})();


