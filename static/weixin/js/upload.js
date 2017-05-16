function J_Uploader(){
	if(J_Uploader._init!=1){
		J_Uploader._init=1;
		
		J_Uploader.list		    = [];		    //文件上传内容列表 {data:base64内容,type:'上传地址的do',filename:'',size:'文件尺寸'}
		J_Uploader.now		    = 0;			  //当前上传对象的id
		J_Uploader.isruning	  = false;		//是否正在上传内容
		J_Uploader.count	    = 0;			  //上传列表项目数
		J_Uploader.file		    = null;		  //当前获取文件的对象 input:file
		J_Uploader.nowtype	  = "";		    //当前上传类型
		J_Uploader.reader	    = 0;			  //文件读取对象表指针
		J_Uploader.filecheck  = /(jpg|jpeg|png)/;
		J_Uploader.filesize	  = 1024000;  //尺寸检测
		J_Uploader.callback	  = false;		//是否有回调函数 传回所有ajax信息
		J_Uploader.uploading  = false;		//正在上传时 是否执行函数
		J_Uploader.upload_url = '';		    //上传地址
    J_Uploader.quality    = 0.5;
		
		J_Uploader.multiupload_databack=false; //批量上传时 全部完成后返回每个上传后返回的信息
		J_Uploader.multiupload_data=[];
		
		//文件类型检测
		J_Uploader.checkfiletype=function(){
			var list=J_Uploader.list[J_Uploader.now].filename.split(".");
			var n=list.length-1;
			if(list[n]==undefined){
				return false;
			}
			return J_Uploader.filecheck.test(list[n].toLowerCase());
		}
		
		//上传函数
		J_Uploader.upload=function(){
			J_Uploader.isruning=true;
			
			//2097152 检测尺寸
			if(J_Uploader.checkfiletype()==false){
        //文件类型不符
        J_Uploader.finish({statu:-2});
			}else{
				//此处添加上传效果或者事件
				if(J_Uploader.uploading!=false){
					J_Uploader.uploading();
				}
        
        //如果大于1MB 压缩图片文件
        if(J_Uploader.list[J_Uploader.now].filezise > J_Uploader.filesize){
          J_Uploader.list[J_Uploader.now].data=J_Uploader.compress(J_Uploader.list[J_Uploader.now].data);
          J_Uploader.list[J_Uploader.now].filetype='jpg';
        }
				
				//上传核心
				$.post(J_Uploader.upload_url,{
					data:J_Uploader.list[J_Uploader.now].data,
					filename:J_Uploader.list[J_Uploader.now].filename,
					filetype:J_Uploader.list[J_Uploader.now].filetype
					//此处可继续添加向服务器的内容
				},function(r){
					J_Uploader.finish(r);
				},'json');
			}
		}
		
		//上传完毕处理下一上传内容
		J_Uploader.finish=function(data){
			//alert(data.statu);
			
			//存储回访信息
			J_Uploader.multiupload_data.push(data);
			
			if(J_Uploader.callback!=false){
				J_Uploader.callback(data);
			}
			
			if(J_Uploader.count-1<=J_Uploader.now){
				J_Uploader.isruning=false;
				J_Uploader.now++;
				
				//回调全部信息
				if(J_Uploader.multiupload_databack!=false){
					J_Uploader.multiupload_databack(J_Uploader.multiupload_data);
					J_Uploader.multiupload_data=[];
				}
			}else{
				J_Uploader.now++;
				setTimeout(J_Uploader.upload,2000);
			}
		}
		
		//添加上传列表
		J_Uploader.addlist=function(){
			var Count=J_Uploader.file.length-1;
			J_Uploader.reader=0;
			
			for(var i=0;i<=Count;i++){
				var reader = new FileReader();
				
				reader.onload=function(evt){
					if(evt.target.result!=""){
						var list=J_Uploader.file[J_Uploader.reader].name.split(".");
						var lastone=list.length-1;
						//console.log(evt.target);
            
						//alert(list[lastone]);
						J_Uploader.list[J_Uploader.count]={
							data:evt.target.result,
							type:J_Uploader.nowtype,
							filename:J_Uploader.file[J_Uploader.reader].name,
							filezise:J_Uploader.file[J_Uploader.reader].size,
							filetype: list[lastone]
						};
						J_Uploader.reader++;
						J_Uploader.count++;
					
						//是否已经在上传
						if(J_Uploader.isruning==false){
							J_Uploader.upload();
						}
					}
				}
				
				reader.readAsDataURL(J_Uploader.file[i]);
			}
			$(J_Uploader.file).val("");
		}
	}
}

//压缩图片
J_Uploader.compress = function(data) {
    var canvas = document.createElement("canvas");
    var ctx = canvas.getContext('2d');
    var moreCanvas = document.createElement("canvas");
    var morectx = moreCanvas.getContext("2d");
    var maxsize = 100 * 1024;
    var img=new Image();
    img.src=data;
    var width = img.width;
    var height = img.height;
    var ratio;
    if ((ratio = width * height / 4000000) > 1) {
        ratio = Math.sqrt(ratio);
        width /= ratio;
        height /= ratio;
    } else {
        ratio = 1;
    }
    canvas.width = width;
    canvas.height = height;
    ctx.fillStyle = "#fff";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    var count;
    if ((count = width * height / 1000000) > 1) {
        count = ~~(Math.sqrt(count) + 1);
        var nw = ~~(width / count);
        var nh = ~~(height / count);
        moreCanvas.width = nw;
        moreCanvas.height = nh;
        for (var i = 0; i < count; i++) {
            for (var j = 0; j < count; j++) {
                morectx.drawImage(img, i * nw * ratio, j * nh * ratio, nw * ratio, nh * ratio, 0, 0, nw, nh);
                ctx.drawImage(moreCanvas, i * nw, j * nh, nw, nh);
            }
        }
    } else {
      ctx.drawImage(img, 0, 0, width, height);
    }
    var ndata = canvas.toDataURL('image/jpeg', J_Uploader.quality);
    moreCanvas.width = moreCanvas.height = canvas.width = canvas.height = 0;
    return ndata;
};
J_Uploader();