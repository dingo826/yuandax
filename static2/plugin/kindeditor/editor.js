var Editor; //编辑器对象

//腾讯视频插件
KindEditor.plugin('txvideo', function(K) {
  var editor = this, name = 'txvideo';
  // 点击图标时执行
  editor.clickToolbar(name, function() {
    var dialog = K.dialog({
      width : 500,
      title : '添加腾讯视频',
      body  : '<div style="margin:10px;"><textarea style="width:98%; padding:1%; margin:10px auto; resize: none" placeholder="请输入腾讯视频地址,可以是网页地址、flash地址、HTML代码和通用代码" class="txvideo-input" rows="5"></textarea></div>',
      closeBtn : {
        name : '关闭',
        click : function(e) {
          dialog.remove();
        }
      },
      yesBtn : {
        name : '确定',
        click : function(e) {
          var regx=/vid\=/g;
          var orginal=$(".txvideo-input").val();
          var vid="";
          
          if(regx.test(orginal)){
            var cut=orginal.split('vid=')[1];
            vid=cut.split('&')[0];
          }else{
            var cuts=orginal.split('/');
            var vidstr=cuts[cuts.length-1];
            vid=vidstr.split('.')[0];
          }
          
          if(vid==""){
            return false;
          }
          
          var html='<iframe class="video_iframe" style="z-index:1;width:100%!important;height:300px;overflow:hidden;" frameborder="0" data-src="https://v.qq.com/iframe/preview.html?vid='+ vid +'&auto=0" allowfullscreen="" src="https://v.qq.com/iframe/player.html?vid='+ vid +'&auto=0" scrolling="no"></iframe>';
          editor.insertHtml(html);
          dialog.remove();
        }
      },
      noBtn : {
        name : '取消',
        click : function(e) {
          dialog.remove();
        }
      }
    });
  });
});
KindEditor.lang({
  txvideo : '添加腾讯视频'
});

KindEditor.ready(function(K) {
	Editor = K.create('#editor', {
		allowFileManager : true,
		langType: 'zh-CN',
		resizeType:1,
		width:"100%",
		height:250,
    filterMode : false,
	wellFormatMode:false,
    items:[
        'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
        'flash', 'media', 'txvideo', '|', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
        'anchor', 'link', 'unlink'
          ],
	});
});

function WeixinEdit(){
  if(WeixinEdit._init!=1){
    WeixinEdit._init      = 1;
    WeixinEdit.editor;
    WeixinEdit.imgurl     = "static/image/weixineditor/";
    
    WeixinEdit.data={
      care:[
        'care',
        '关注',
        [
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/1.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/2.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/3.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/4.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/5.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/6.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/7.png"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/5.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/8.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/9.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/10.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/11.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/12.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/13.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/14.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/15.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/16.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/17.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/18.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/19.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/20.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/21.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/22.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/23.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/24.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/25.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/26.png"/></p></section>',
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);">'
            +'<section style="position: static; box-sizing: border-box;"><section style="margin: 8px 0% 0px 30%; line-height: 1em; transform: translate3d(0px, 0px, 0px);' 
            +'-webkit-transform: translate3d(0px, 0px, 0px); -moz-transform: translate3d(0px, 0px, 0px);'
            +' -o-transform: translate3d(0px, 0px, 0px); text-align: center; position: static; box-sizing: border-box;">'
            +'<section style="width: 0px; display: inline-block; vertical-align: bottom; border-bottom-width: 13px;'
            +' border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); border-left-width: 13px !important;'
            +' border-left-style: solid !important; border-left-color: transparent !important;'
            +' border-right-width: 13px !important; border-right-style: solid !important;'
            +' border-right-color: transparent !important; box-sizing: border-box;"></section></section>'
            +'</section><section style="position: static; box-sizing: border-box;">'
            +'<section style="margin: 0px 0%; position: static; box-sizing: border-box;">'
            +'<section style="display: inline-block; width: 100%; vertical-align: top;'
            +' box-sizing: border-box; background-image: url('+ WeixinEdit.imgurl +'care/27.gif); background-attachment: '
            +'scroll; background-color: rgb(254, 255, 255); background-size: 103.933%; '
            +'background-position: 0% 0%; background-repeat: repeat;"><section style="position: static;'
            +' box-sizing: border-box;"><section style="position: static; box-sizing: border-box;">'
            +'<section style="display: inline-block; vertical-align: top; width: 80%; box-sizing: border-box;">'
            +'<section style="box-sizing: border-box;"><section style="margin: 5px 0%; '
            +'position: static; box-sizing: border-box;"><section style="color: rgb(255, 255, 255); '
            +'line-height: 1.6; padding: 0px 10px; box-sizing: border-box;"><section style="box-sizing: '
            +'border-box;">关注我，你的眼睛会怀孕</section></section></section></section></section>'
            +'<section style="display: inline-block; vertical-align: top; width: 20%; box-sizing: border-box;">'
            +'<sectionstyle="position: static; box-sizing: border-box;"><section '
            +'style="text-align: center; margin: 5px 0% 0px; transform: translate3d(0px, 0px, 0px); '
            +'-webkit-transform: translate3d(0px, 0px, 0px); -moz-transform: translate3d(0px, 0px, 0px); '
            +'-o-transform: translate3d(0px, 0px, 0px); font-size: 15.2px; position: static; box-sizing: '
            +'border-box;"><img style="width: 90%; box-sizing: border-box; vertical-align: middle;" '
            +' src="'+ WeixinEdit.imgurl +'care/27.png"></section></section></section></section></section>'
            +'</section></section></section></section></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/28.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/29.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/30.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/31.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/32.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/33.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/34.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/35.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/36.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/37.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/38.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/39.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/40.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/41.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/42.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/43.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/44.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/45.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/46.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/47.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/48.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/49.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/50.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/51.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/52.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/53.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/54.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/55.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/56.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/57.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/58.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/59.gif"/></p></section>',
          '<section><section style="margin: 5px 0px 0px; padding: 10px'
            +'"><section class="change" change="border-top-color,border-bottom-color" style="margin: -15px auto 0px 55px; width: 0px;'
            +' height: 0px; border-left-width: 18px; border-left-style: solid; border-color: rgb(89, 195, 249) transparent; border-right-width: 0px; border-right-style: solid; border-bottom-width:'
            +' 27px; border-bottom-style: solid; color: inherit;" data-width="0px"></section>'
            +'<section class="change" change="background-color" style="margin-top: -1px; margin-bottom: 0px; visibility: '
            +'visible; height: 40px; line-height: 40px; border-radius: 3px; '
            +' text-align: center; color: #FFF; background-color: rgb(89, 195, 249);"><span style="font-size: 14px">'
            +'↑点击上方&quot;xxx&quot;关注我们</span></section></section></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/61.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/62.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/63.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/64.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/65.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/66.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/67.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/68.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/69.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/70.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/71.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/72.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/73.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/74.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/75.gif"/></p></section>',
          '<section><section style="margin: 5px auto; width: 90%;">'
            +'<img style="width: 30px; margin: 0 auto; display: inline-block;'
            +'vertical-align: middle" src="'+ WeixinEdit.imgurl +'care/76.gif">'
            +'<section style="display:inline-block; vertical-align: middle; font-size: 12px; margin-left:5px;">'
            +'点击上面的蓝字关注我们哦！</section></section></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/77.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/79.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/80.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/81.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/82.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/83.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/85.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/86.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/87.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/88.gif"/></p></section>',
          '<section><section style="text-align: left; vertical-align: top;">'
            +'<section class="change" change="border-bottom-color" style="width: 0px; margin-left: 26%;'
            +' border-top-color: transparent !important; border-right-color: transparent '
            +'!important; border-bottom-color: rgb(0, 187, 236); '
            +'border-left-color: transparent !important; border-width: 0.8em !important;'
            +' border-style: solid !important;"></section>'
            +'<section class="change" change="background-color" style="margin: 0px;'
            +' border-radius: 2em; height: 2.5em; background-color: rgb(0, 187, 236);">'
            +'<img style="margin: 0.5em 0.6em; height: 1.6em; vertical-align: top;"'
            +' src="'+ WeixinEdit.imgurl +'care/89.png">'
            +'<section style="text-align: center; overflow: hidden; font-size: 1.2em;'
            +' display: inline-block; white-space: nowrap; line-height:30px">'
            +'<section style="color: #FFF; display: inline-block;">点击上方 “蓝色字” </section>'
            +'<section style="color: #FFF; display: inline-block;">可们！</section>'
            +'</section></section></section></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/90.png"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/91.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/92.gif"/></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'care/93.png"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/94.png"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/95.png"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/96.png"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/97.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/98.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/99.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/100.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/101.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/102.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/103.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/104.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/105.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/106.gif"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/107.jpg"/></p></section>',
          '<section><p><img width="100%" src="'+ WeixinEdit.imgurl +'care/108.png"/></p></section>',
          '<section><p style="padding: 10px 20px; color: rgb(0, 187, 236);'
            +' line-height: 1.5em; font-size: 14px; margin-top: 0px;'
            +' margin-bottom: 0px; white-space: normal; word-wrap: normal;'
            +' min-height: 1em; max-width: 100%; box-sizing: border-box !important;'
            +' background-color: rgb(255, 255, 255);"><span style="color: rgb(62, 62, 62);'
            +' -ms-word-wrap: break-word !important; max-width: 100%;'
            +' box-sizing: border-box !important;"><strong style="word-wrap: break-word !important; '
            +'box-sizing: border-box !important;">'
            +'<span class="change" change="border-color" style="padding: 10px 0px 10px 20px; border: 3px solid rgb(0, 187, 236);'
            +' width: 200px; line-height: 2em; font-size: 13px; float: left; display: inline-block;'
            +' box-sizing: border-box !important; white-space: nowrap; text-overflow: ellipsis; overflow: hidden;'
            +' box-shadow: inset 4px 4px 8px 1px rgba(0,0,0,0.247059);">微信号：</span></strong></span>'
            +'<strong class="change" change="border-color,background-color" style="padding: 10px; border: 2px solid rgb(0, 187, 236);'
            +' color: #FFF; line-height: 2em; display: inline-block;'
            +' word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;'
            +' background-color: rgb(0, 187, 236);">加关注</strong></p></section>',
          '<section><section style="margin: 5px 0px 0px; padding: 10px;'
            +'"><section class="change" change="border-bottom-color" style="margin: -15px auto 0px 100px; width: 0px;'
            +' height: 0px; border-left-width: 4px; border-left-style: solid;'
            +' border-left-color: transparent; border-right-width: 22px;'
            +' border-right-style: solid; border-right-color: transparent;'
            +' border-bottom-width: 24px; border-bottom-style: solid;'
            +' border-bottom-color: rgb(112, 216, 255);"></section>'
            +'<section class="change" change="border-color" style="margin-top:-1px;'
            +' border: 3px solid rgb(112, 216, 255); margin-bottom: 0px; min-height: 40px;'
            +' visibility: visible; height: 40px; line-height: 37px; border-top-left-radius:10px;'
            +' border-top-right-radius: 10px; border-bottom-right-radius: 10px;'
            +' border-bottom-left-radius: 10px; text-align: center;">'
            +'<span style="font-size: 14px;">点击上方<span style="color: #607fa6;">'
            +'"xxx"</span>关注我们</span></section></section></section>',
          '<section><section><section style="border: 0px none; padding: 0px;'
            +' box-sizing: border-box; margin: 0px;><section style="margin: 0.8em 0px 0.3em;'
            +' box-sizing: border-box; padding: 0px;"><section style="text-align: center;'
            +' text-decoration: inherit; color: rgb(255, 255, 255); '
            +'border-color: rgb(0, 187, 236); box-sizing: border-box; padding: 0px;'
            +' margin: 0px;"><section class="change" change="border-bottom-color,border-top-color" style="width: 0px; margin: 0px 0px 0px 90px;'
            +' border-bottom-width: 0.8em; border-bottom-style: solid;'
            +' border-bottom-color: rgb(0, 187, 236); border-top-color: rgb(0, 187, 236);'
            +' box-sizing: border-box; height: 10px; color: inherit; border-left-width: 0.8em !important;'
            +' border-left-style: solid !important; border-left-color: transparent !important;'
            +' border-right-width: 0.8em !important; border-right-style: solid !important;'
            +' border-right-color: transparent !important; padding: 0px;" data-width="0px"'
            +'></section><section class="change" change="background-color"'
            +' style="padding: 0.5em; line-height: 1.2em; font-size: 1em; box-sizing: border-box;'
            +' color: inherit; border-color: rgb(61, 161, 233); background-color: rgb(0, 187, 236);'
            +' border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px;'
            +' border-bottom-left-radius: 4px; margin: 0px;">'
            +'<strong style="color:inherit">点击标题下「蓝色微信名」可快速关注</strong>'
            +'</section></section></section><section style="width: 0px; height: 0px;'
            +' clear: both; box-sizing: border-box; padding: 0px; margin: 0px;"></section>'
            +'</section></section></section>',
          '<section><section><section style="text-align: left; vertical-align: top;">'
            +'<section class="change" change="border-bottom-color" style="width: 0px; margin-left: 48%;'
            +' border-bottom-width: 0.8em; border-bottom-style: solid;'
            +' border-bottom-color: rgb(95, 170, 255); border-left-width: 0.8em !important;'
            +' border-left-style: solid !important; border-left-color: transparent !important;'
            +' border-right-width: 0.8em !important; border-right-style: solid !important;'
            +' border-right-color: transparent !important; border-top-color: transparent !important;">'
            +'</section><section class="change" change="background-color" style="margin: 0px; border-radius: 2em;'
            +' background-color: rgb(95, 170, 255); padding:5px 0; text-align:center; font-size: 16px;"><section style="display: inline-block; width: 70%;'
            +' text-align: center;  white-space: nowrap; overflow: hidden;'
            +' text-overflow:ellipsis"><section style="display: inline-block; color: rgb(255, 255, 255);'
            +'">点击上方</section><section style="display: inline-block; color: rgb(64, 84, 115);"'
            +'>“蓝色字”</section><section style="display: inline-block; color: rgb(255, 255, 255);"'
            +'>可关注我们！</section></section></section></section></section></section>',
        ]
      ],
      
      title:[
        'title',
        '标题',
        [
          '<section><section style="box-sizing: border-box; background-color: #FFF">'
            +'<section style="box-sizing: border-box;">'
            +'<section style="margin: 0.5em 0px; text-align: center; '
            +'box-sizing: border-box;"><section class="change" change="border-color" style="display: inline-block; '
            +'border-radius: 1em; border: 2px solid rgb(61, 167, 66); '
            +'box-shadow: rgb(170, 170, 170) 0px 0px 10px; padding: 0.3em 0.8em; '
            +'box-sizing: border-box;"><section style="box-sizing: border-box;"'
            +'>请输入标题</section></section></section></section></section></section>',
            
          '<section><section style="box-sizing: border-box; background-color: #FFF;">'
            +'<section style="box-sizing: border-box;">'
            +'<section style="margin: 0.5em 0px; text-align: center;'
            +' box-sizing: border-box;"><section class="change" change="background-color" style="display: inline-block;'
            +' border-radius: 1em; padding: 0.3em 0.8em; color: rgb(255, 255, 255);'
            +' box-sizing: border-box; background-color: rgb(61, 167, 66);">'
            +'<section style="box-sizing: border-box;">请输入标题</section></section>'
            +'</section></section></section></section>',
            
          '<section>​<section style="box-sizing: border-box; background-color: #FFF;'
            +'"><section style="box-sizing: border-box;"><section style="margin: 0.5em 0px; '
            +'text-align: center; position: static; box-sizing: border-box;">'
            +'<section class="change" change="border-color" style="display: inline-block; border-radius: 1em; '
            +'border: 2px solid rgb(61, 167, 66); padding: 0.3em 0.8em; '
            +'box-sizing: border-box;"><section style="box-sizing: border-box;"'
            +'>请输入标题</section></section></section></section></section></section>',
            
          '<section><section style="box-sizing: border-box; background-color: #FFF;'
            +'"><section style="box-sizing: border-box;">'
            +'<section style="margin: 10px 0%; box-sizing: border-box;">'
            +'<section class="change" change="border-color" style="display: inline-block; width: 100%; '
            +'border: 1px solid rgb(61, 167, 66); padding: 10px 0px 10px 10px; '
            +'box-shadow: rgb(204, 204, 204) 0.2em 0.2em 0.3em; box-sizing: border-box;">'
            +'<section style="box-sizing: border-box;">'
            +'<section style="margin: -3px 0%; box-sizing: border-box;">'
            +'<section style="display: inline-block; vertical-align: top;'
            +' width: 88%; box-sizing: border-box;"><section style="box-sizing: border-box;">'
            +'<section style="margin: 0px 0%; box-sizing: border-box;">'
            +'<section style="line-height: 1.4; box-sizing: border-box;">'
            +'<section style="box-sizing: border-box;">关键词：搜索</section></section></section>'
            +'</section></section><section style="display: inline-block; vertical-align: top;'
            +' width: 12%; box-sizing: border-box;"><section style="'
            +' box-sizing: border-box;"><section class="" style="text-align: center;'
            +' margin: -3px 0% -5px; box-sizing: border-box;">'
            +'<img style="box-sizing: border-box; vertical-align: middle;" src="'+ WeixinEdit.imgurl +'title/1.png"></section>'
            +'</section></section></section></section></section></section></section></section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);"'
            +'><section><section style="margin: 0.5em 0px;'
            +' text-align: center;"><section class="change" change="background-color" style="display: inline-block;'
            +' border-radius: 5px; padding:8px 0; font-size: 20px; color: #FFF; width: 100%;'
            +' background-color: rgb(61, 167, 66);"><section>按钮</section></section>'
            +'</section></section></section></section>',
            
          '<section><section style="background-color: #FFF;"><section>'
            +'<section style="margin: 0.5em 0px; text-align: center;">'
            +'<section class="change" change="background-color" style="display: inline-block; border-radius: 5px;'
            +' padding:8px 0; font-size: 20px; width: 60%; color: #FFF;'
            +' background-color: rgb(61, 167, 66);"><section>按钮</section>'
            +'</section></section></section></section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="margin: 0.5em 0px; text-align: center;'
            +' box-sizing: border-box;"><section class="change" change="border-color,color" style="display: inline-block;'
            +' border-radius: 5px; padding:8px 0; border: 1px solid rgb(61, 167, 66);'
            +' font-size: 20px; width: 60%; color: rgb(4, 190, 2);">'
            +'按钮</section></section></section></section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="margin: 0.5em 0px; text-align: center;">'
            +'<section class="change" change="border-color" style="display: inline-block; border-radius: 5px; padding: 8px 0.75em;'
            +' border: 1px solid rgb(61, 167, 66); color: rgb(69, 69, 69)">'
            +'<section>按钮</section></section></section></section></section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="margin: 0.5em 0px; text-align: center">'
            +'<section class="change" change="background-color" style="display: inline-block; border-radius: 5px; padding: 8px 0.75em;'
            +' color: rgb(255, 255, 255); box-sizing: border-box;'
            +' background-color: rgb(61, 167, 66);"><section>按钮</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="margin: 0.5em 0px; text-align: center">'
            +'<section style="display: inline-block;"><section class="change" change="border-right-color,border-bottom-color" style="width: 0px;'
            +' display: inline-block; vertical-align: top; border-right-width: 0.5em;'
            +' border-right-style: solid; border-right-color: rgb(61, 167, 66);'
            +' border-bottom-width: 1em; border-bottom-style: solid;'
            +' border-bottom-color: rgb(61, 167, 66); border-left-width: 0.5em !important;'
            +' border-left-style: solid !important; border-left-color: transparent !important;'
            +' border-top-width: 1em !important; border-top-style: solid !important;'
            +' border-top-color: transparent !important;"></section>'
            +'<section class="change" change="background-color" style="line-height: 2em; padding: 0px 10px;'
            +' display: inline-block; vertical-align: top;'
            +' color: rgb(255, 255, 255); box-sizing: border-box; '
            +'background-color: rgb(61, 167, 66);"><section>输入标题</section></section>'
            +'<section class="change" change="border-top-color,border-left-color" style="width: 0px; display: inline-block; vertical-align: top;'
            +' border-left-width: 0.5em; border-left-style: solid; '
            +'border-left-color: rgb(61, 167, 66); border-top-width: 1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66);'
            +' box-sizing: border-box; border-right-width: 0.5em !important;'
            +' border-right-style: solid !important; border-right-color: transparent !important;'
            +' border-bottom-width: 1em !important; border-bottom-style: solid !important;'
            +' border-bottom-color: transparent !important;"></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="text-align: center; margin-top: 10px;'
            +' margin-bottom: 10px;"><section class="change" change="border-color" style="display: inline-block;'
            +' border: 1px solid rgb(61, 167, 66); margin-left: 0.8em; margin-right: 0.8em;'
            +' padding: 5px; transform: skew(-30deg);"><section class="change" change="background-color" style="width: 100%;'
            +' background-color: rgb(61, 167, 66);"><section class="" style=" transform: skew(30deg);'
            +' -webkit-transform: skew(30deg); -moz-transform: skew(30deg); -o-transform: skew(30deg);'
            +' color: rgb(255, 255, 255); font-size: 18px; padding-left: 10px;'
            +' padding-right: 10px;"><section>输入标题</section></section></section>'
            +'</section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="text-align: center; margin-top: 10px;'
            +' margin-bottom: 10px;"><section class="change" change="border-color" style="display: inline-block;'
            +' border: 1px solid rgb(61, 167, 66); margin-left: 0.8em; margin-right: 0.8em;'
            +' padding: 5px; transform: skew(30deg); -webkit-transform: skew(30deg);"><section class="change" change="background-color" style="width: 100%;'
            +' background-color: rgb(61, 167, 66);"><section class="" style=" transform: skew(-30deg);'
            +' -webkit-transform: skew(-30deg); -moz-transform: skew(-30deg); -o-transform: skew(-30deg);'
            +' color: rgb(255, 255, 255); font-size: 18px; padding-left: 10px;'
            +' padding-right: 10px;"><section>输入标题</section></section></section>'
            +'</section></section></section></section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section class="" style="margin-top: 10px; margin-bottom: 10px;'
            +' text-align: center;"><section class="change" change="border-color" style="padding: 3px;'
            +' border: 1px solid rgb(61, 167, 66); display: inline-block;">'
            +'<section class="change" change="background-color" style="padding: 0.1em 0.3em; font-size:18px; color: #FFF;'
            +' background-color: rgb(61, 167, 66);">请输入标题</section></section></section></section></section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="margin-top: 10px; margin-bottom: 10px;'
            +' text-align: center;"><section class="change" change="border-color" style="display: inline-block;'
            +' vertical-align: top; border-color: rgb(61, 167, 66); padding: 3px 5px;'
            +' border-width: 4px 1px 1px; border-style: solid; box-sizing: border-box;"><section>输入标题</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="margin-top: 10px; margin-bottom: 10px;'
            +' text-align: center;"><section class="change" change="border-color" style="display: inline-block;'
            +' vertical-align: top; border-color: rgb(61, 167, 66); padding: 3px 5px;'
            +' border-width: 1px 1px 4px 1px; border-style: solid; box-sizing: border-box;"><section>输入标题</section></section></section></section></section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="margin-top: 10px; margin-bottom: 10px;'
            +' overflow: hidden; text-align: center;"><section class="change" change="border-right-color" style="display: inline-block;'
            +' vertical-align: top; width: 0px; border-right-width: 0.5em;'
            +' border-right-style: solid; border-right-color: rgb(61, 167, 66);'
            +' box-sizing: border-box; border-top-width: 1em !important;'
            +' border-top-style: solid !important; border-top-color: transparent !important;'
            +' border-bottom-width: 1em !important; border-bottom-style: solid !important;'
            +' border-bottom-color: transparent !important;"></section>'
            +'<span style="display: inline-block; vertical-align: top; line-height: 2em;'
            +' padding: 0px 5px; background-color: rgba(211, 207, 206, 0.117647);">'
            +'<section>小贴士</section></span><section class="change" change="border-left-color" style="display: inline-block;'
            +' vertical-align: top; width: 0px; border-left-width: 0.5em; border-left-style: solid;'
            +' border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 1em !important;'
            +' border-top-style: solid !important; border-top-color: transparent !important;'
            +' border-bottom-width: 1em !important; border-bottom-style: solid !important;'
            +' border-bottom-color: transparent !important;"></section></section></section>'
            +'</section></section>',
            
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section><section style="transform: translate3d(0px, 0px, 0px);'
            +' -webkit-transform: translate3d(0px, 0px, 0px); text-align: center; position: static;'
            +' box-sizing: border-box;"><section style="display: inline-block; vertical-align: top;'
            +' width: 24%; box-sizing: border-box;"><section style="position: static;'
            +' box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px;'
            +' text-align: right; font-size: 12.8px; line-height: 1; transform: translate3d(5px, 0px, 0px);'
            +' -webkit-transform: translate3d(5px, 0px, 0px); position: static; box-sizing: border-box;">'
            +'<section style="display: inline-block; box-sizing: border-box;"><section class="change" change="background-color" style="width: 0.7em;'
            +' height: 0.7em; display: inline-block; vertical-align: middle; transform: rotate(45deg);'
            +'-webkit-transform: rotate(45deg); box-sizing: border-box;'
            +' background-color: rgb(61, 167, 66);"></section><section class="change" change="background-color" style="width: 0.4em; height: 0.4em;'
            +' margin-left: 0.2em; display: inline-block; vertical-align: middle; transform: rotate(45deg);'
            +' -webkit-transform: rotate(45deg); box-sizing: border-box; background-color: rgb(61, 167, 66);'
            +'"></section></section></section></section></section><section style="display: inline-block;'
            +' vertical-align: top; width: 30%; box-sizing: border-box;"><section style="position: static;'
            +' box-sizing: border-box;"><section style="margin: 5px 0% 0px; position: static;'
            +' box-sizing: border-box;"><section style="box-sizing: border-box;">'
            +'<section style="box-sizing: border-box;"><strong style="box-sizing: border-box;"'
            +'>输入标题</strong></section></section></section></section></section>'
            +'<section style="display: inline-block; vertical-align: top; width: 24%;'
            +' box-sizing: border-box;"><section style="position: static; box-sizing: border-box;">'
            +'<section style="margin-top: 10px; margin-bottom: 10px; text-align: left; font-size: 14px;'
            +' transform: translate3d(-5px, 0px, 0px); -webkit-transform: translate3d(-5px, 0px, 0px);'
            +' position: static; box-sizing: border-box;"><section style="display: inline-block;'
            +' box-sizing: border-box;"><section class="change" change="background-color" style="width: 0.4em; height: 0.4em;'
            +' margin-right: 0.2em; display: inline-block; vertical-align: middle; transform: rotate(45deg);'
            +' -webkit-transform: rotate(45deg); box-sizing: border-box; '
            +'background-color: rgb(61, 167, 66);"></section><section class="change" change="background-color" style="width: 0.7em; height: 0.7em;'
            +' display: inline-block; vertical-align: middle; transform: rotate(45deg);'
            +'-webkit-transform: rotate(45deg); box-sizing: border-box; '
            +'background-color: rgb(61, 167, 66);"></section></section></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section style="position: static; box-sizing: border-box;">'
            +'<section style="margin-top: 10px; margin-bottom: 10px; text-align: center;'
            +' position: static; box-sizing: border-box;"><section style="display: inline-block;'
            +' vertical-align: middle; box-sizing: border-box;"><section class="change" change="border-right-color" style="border-right-width: 0.1em;'
            +' border-right-style: solid; border-right-color: rgb(61, 167, 66); width: 1.6em; height: 2em;'
            +' box-sizing: border-box;"><section style="height: 0.9em; padding-top: 1em;'
            +' box-sizing: border-box;"><section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); float: right;'
            +' margin-bottom: -0.12em; transform: rotateZ(68deg); transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section><section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); float: right;'
            +' transform: rotateZ(45deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(45deg); -webkit-transform-origin: 100% 0px 0px; box-sizing: border-box;">'
            +'</section><section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em; border-top-style: solid;'
            +' border-top-color: rgb(61, 167, 66); float: right; margin-top: -0.1em;'
            +' transform: rotateZ(18deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(18deg); -webkit-transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section></section><section class="change" change="border-top-color" style="border-top-width: 0.1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); width: 1.6em;'
            +' box-sizing: border-box;"></section><section style="height: 0.9em; box-sizing: border-box;">'
            +'<section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em; border-top-style: solid;'
            +' border-top-color: rgb(61, 167, 66); float: right; margin-top: -0.1em;'
            +' transform: rotateZ(-18deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(-18deg); -webkit-transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section><section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); float: right; margin-top: -0.1em;'
            +' transform: rotateZ(-45deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(-45deg); -webkit-transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section><section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); float: right; margin-top: -0.1em; '
            +'-webkit-transform: rotateZ(-68deg); -webkit-transform-origin: 100% 0px 0px; '
            +'transform: rotateZ(-68deg); transform-origin: 100% 0px 0px; '
            +'box-sizing: border-box;"></section></section></section><section class="change" change="background-color" style="width: 0.3em;'
            +' height: 0.5em; margin: -1.2em 0px 0.8em 1.3em; border-bottom-left-radius: 0.3em;'
            +' border-top-left-radius: 0.3em; box-sizing: border-box; background-color: rgb(61, 167, 66);">'
            +'</section></section><section style="display: inline-block; vertical-align: top;'
            +' margin: 0px 0.5em; font-size: 19.2px; max-width: 80% !important; box-sizing: border-box;">'
            +'<section style="box-sizing: border-box;">请输入标题</section></section>'
            +'<section style="display: inline-block; vertical-align: middle; margin-top: -1em;'
            +' transform: rotate(180deg); -webkit-transform: rotate(180deg); box-sizing: border-box;">'
            +'<section class="change" change="border-right-color" style="border-right-width: 0.1em; border-right-style: solid;'
            +' border-right-color: rgb(61, 167, 66); width: 1.6em; height: 2em; box-sizing: border-box;">'
            +'<section style="height: 0.9em; padding-top: 1em; box-sizing: border-box;">'
            +'<section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em; border-top-style: solid;'
            +' border-top-color: rgb(61, 167, 66); float: right; margin-bottom: -0.12em;'
            +' transform: rotateZ(68deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(68deg); -webkit-transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section><section class="change" change="border-top-color" style="width: 1em;'
            +' border-top-width: 0.1em; border-top-style: solid; border-top-color: rgb(61, 167, 66); float: right;'
            +' transform: rotateZ(45deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(45deg); -webkit-transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section><section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); float: right; margin-top: -0.1em;'
            +' transform: rotateZ(18deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(18deg); -webkit-transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section></section><section class="change" change="border-top-color" style="border-top-width: 0.1em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); width: 1.6em;'
            +' box-sizing: border-box;"></section><section style="height: 0.9em; box-sizing: border-box;">'
            +'<section class="change" change="border-top-color" style="width: 1em; border-top-width: 0.1em; border-top-style: solid;'
            +' border-top-color: rgb(61, 167, 66); float: right; margin-top: -0.1em; '
            +'transform: rotateZ(-18deg); transform-origin: 100% 0px 0px; '
            +'-webkit-transform: rotateZ(-18deg); -webkit-transform-origin: 100% 0px 0px; '
            +'box-sizing: border-box;"></section><section class="change" change="border-top-color" style="width: 1em;'
            +' border-top-width: 0.1em; border-top-style: solid; border-top-color: rgb(61, 167, 66);'
            +' float: right; margin-top: -0.1em;'
            +' transform: rotateZ(-45deg); transform-origin: 100% 0px 0px; '
            +'-webkit-transform: rotateZ(-45deg); -webkit-transform-origin: 100% 0px 0px; '
            +'box-sizing: border-box;"></section><section class="change" change="border-top-color" style="width: 1em;'
            +' border-top-width: 0.1em; border-top-style: solid; border-top-color: rgb(61, 167, 66);'
            +' float: right; margin-top: -0.1em; '
            +'transform: rotateZ(-68deg); transform-origin: 100% 0px 0px;'
            +'-webkit-transform: rotateZ(-68deg); -webkit-transform-origin: 100% 0px 0px;'
            +' box-sizing: border-box;"></section></section></section><section class="change" change="background-color" style="width: 0.3em;'
            +' height: 0.5em; margin: -1.2em 0px 1.6em 1.3em; border-bottom-left-radius: 0.3em;'
            +' border-top-left-radius: 0.3em; box-sizing: border-box; background-color: rgb(61, 167, 66);"'
            +'></section></section></section></section></section></section>',
            
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);">'
            +'<section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px;'
            +' margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;">'
            +'<section class="change" change="border-right-color,border-left-color" style="padding: 0px 5px; display: inline-block;'
            +' vertical-align: top; border-left-width: 5px; border-left-style: solid; border-left-color: rgb(61, 167, 66);'
            +' border-right-width: 5px; border-right-style: solid; border-right-color: rgb(61, 167, 66);'
            +' box-sizing: border-box;"><section class="change" change="border-top-color,border-bottom-color,border-right-color" style="border-top-width: 0.75em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); display: inline-block; vertical-align: top;'
            +' border-bottom-width: 0.75em; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66);'
            +' box-sizing: border-box; border-right-width: 0.75em !important; border-right-style: solid !important;'
            +' border-right-color: transparent !important;"></section><section style="display: inline-block;'
            +' vertical-align: top; line-height: 1.5em; box-sizing: border-box;">'
            +'<section style="box-sizing: border-box;">古典风格</section></section>'
            +'<section class="change" change="border-top-color,border-bottom-color,border-left-color" style="display: inline-block; vertical-align: top; border-bottom-width: 0.75em;'
            +' border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); border-top-width: 0.75em;'
            +' border-top-style: solid; border-top-color: rgb(61, 167, 66); box-sizing: border-box;'
            +' border-left-width: 0.75em !important; border-left-style: solid !important;'
            +' border-left-color: transparent !important;"></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);">'
            +'<section style="position: static; box-sizing: border-box;"><section style="text-align: center;'
            +' margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;">'
            +'<section style="display: inline-block; vertical-align: top; box-sizing: border-box;">'
            +'<section class="change" change="border-color" style="border-top-width: 2px;'
            +' border-bottom-width: 2px; border-left-width: 2px; border-style: solid none solid solid;'
            +' border-color: rgb(61, 167, 66);'
            +' display: inline-block; vertical-align: top; height: 2em; width: 0.8em;'
            +' border-top-left-radius: 1em; border-bottom-left-radius: 1em; box-sizing: border-box;">'
            +'</section><section style="display: inline-block; vertical-align: top;'
            +' line-height: 2em; box-sizing: border-box;"><section style="box-sizing: border-box;"'
            +'>目录</section></section><section class="change" change="border-color" style="border-top-width: 2px; border-right-width: 2px;'
            +' border-bottom-width: 2px; border-style: solid solid solid none; '
            +'border-color: rgb(61, 167, 66);'
            +' display: inline-block; vertical-align: top; height: 2em; width: 0.8em; border-top-right-radius: 1em;'
            +' border-bottom-right-radius: 1em; box-sizing: border-box;">'
            +'</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; margin-bottom: -10px; box-sizing: border-box;"><section class="change" change="border-bottom-color" style="border-bottom-width: 2px; border-bottom-style: solid; padding: 0px 5px; border-bottom-color: rgb(61, 167, 66); box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="box-sizing: border-box;">输入标题</section></section><section style="display: inline-block; vertical-align: top; margin-top: -2px; box-sizing: border-box;"><section class="change" change="border-top-color,border-right-color" style="width: 0px; border-top-width: 8px; border-top-style: solid; border-right-width: 8px; border-right-style: solid; border-top-color: rgb(61, 167, 66); border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-left-width: 8px !important; border-left-style: solid !important; border-left-color: transparent !important; border-bottom-width: 8px !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"></section><section style="width: 0px; border-top-width: 6px; border-top-style: solid; border-top-color: rgb(255, 255, 255); margin-top: -16px; margin-left: 4px; border-right-width: 5px; border-right-style: solid; border-right-color: rgb(255, 255, 255); border-left-width: 5px !important; border-left-style: solid !important; border-left-color: transparent !important; border-bottom-width: 6px !important; border-bottom-style: solid !important; border-bottom-color: transparent !important; box-sizing: border-box;"></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; margin-top: -10px; box-sizing: border-box;"><section style="display: inline-block; vertical-align: bottom; box-sizing: border-box;"><section class="change" change="border-bottom-color,border-left-color" style="width: 0px; border-left-width: 8px; border-left-style: solid; border-bottom-width: 8px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 8px !important; border-top-style: solid !important; border-top-color: transparent !important; border-right-width: 8px !important; border-right-style: solid !important; border-right-color: transparent !important;"></section><section style="width: 0px; margin-top: -12px; margin-left: 2px; border-left-width: 5px; border-left-style: solid; border-left-color: rgb(255, 255, 255); border-bottom-width: 6px; border-bottom-style: solid; border-bottom-color: rgb(255, 255, 255); border-top-width: 6px !important; border-top-style: solid !important; border-top-color: transparent !important; border-right-width: 5px !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;"></section></section><section class="change" change="border-top-color,border-bottom-color" style="border-top-width: 2px; border-top-style: solid; border-bottom-width: 2px; border-bottom-style: solid; margin-top: -2px; padding: 0px 5px; border-top-color: rgb(61, 167, 66); border-bottom-color: rgb(61, 167, 66); box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="box-sizing: border-box;">输入标题</section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; margin-bottom: -10px; box-sizing: border-box;"><section class="change" change="border-top-color,border-bottom-color" style="border-top-width: 2px; border-top-style: solid; border-bottom-width: 2px; border-bottom-style: solid; padding: 0px 5px; border-top-color: rgb(61, 167, 66); border-bottom-color: rgb(61, 167, 66); box-sizing: border-box; background-color: rgb(254, 255, 255);"><section style="box-sizing: border-box;">输入标题</section></section><section style="display: inline-block; vertical-align: top; margin-top: -2px; box-sizing: border-box;"><section class="change" change="border-top-color,border-right-color" style="width: 0px; border-top-width: 8px; border-top-style: solid; border-right-width: 8px; border-right-style: solid; border-top-color: rgb(61, 167, 66); border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-left-width: 8px !important; border-left-style: solid !important; border-left-color: transparent !important; border-bottom-width: 8px !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"></section><section style="width: 0px; border-top-width: 6px; border-top-style: solid; border-top-color: rgb(255, 255, 255); margin-top: -16px; margin-left: 4px; border-right-width: 5px; border-right-style: solid; border-right-color: rgb(255, 255, 255); border-left-width: 5px !important; border-left-style: solid !important; border-left-color: transparent !important; border-bottom-width: 6px !important; border-bottom-style: solid !important; border-bottom-color: transparent !important; box-sizing: border-box;"></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; margin-bottom: -10px; box-sizing: border-box;"><section class="change" change="border-bottom-color" style="border-bottom-width: 2px; border-bottom-style: solid; padding: 0px 5px; border-bottom-color: rgb(61, 167, 66); box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="box-sizing: border-box;">输入标题</section></section><section style="display: inline-block; vertical-align: top; margin-top: -2px; box-sizing: border-box;"><section class="change" change="border-top-color,border-left-color" style="width: 0px; border-top-width: 8px; border-top-style: solid; border-left-width: 8px; border-left-style: solid; border-top-color: rgb(61, 167, 66); border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-right-width: 8px !important; border-right-style: solid !important; border-right-color: transparent !important; border-bottom-width: 8px !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"></section><section style="width: 0px; border-top-width: 6px; border-top-style: solid; border-top-color: rgb(255, 255, 255); border-left-width: 5px; border-left-style: solid; border-left-color: rgb(255, 255, 255); margin-top: -16px; margin-left: 2px; border-right-width: 5px !important; border-right-style: solid !important; border-right-color: transparent !important; border-bottom-width: 6px !important; border-bottom-style: solid !important; border-bottom-color: transparent !important; box-sizing: border-box;"></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; margin-top: -10px; box-sizing: border-box;"><section style="display: inline-block; vertical-align: bottom; box-sizing: border-box;"><section class="change" change="border-bottom-color,border-right-color" style="width: 0px; border-right-width: 8px; border-right-style: solid; border-bottom-width: 8px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 8px !important; border-top-style: solid !important; border-top-color: transparent !important; border-left-width: 8px !important; border-left-style: solid !important; border-left-color: transparent !important;"></section><section style="width: 0px; margin-top: -12px; margin-left: 4px; border-right-width: 5px; border-right-style: solid; border-right-color: rgb(255, 255, 255); border-bottom-width: 6px; border-bottom-style: solid; border-bottom-color: rgb(255, 255, 255); border-top-width: 6px !important; border-top-style: solid !important; border-top-color: transparent !important; border-left-width: 5px !important; border-left-style: solid !important; border-left-color: transparent !important; box-sizing: border-box;"></section></section><section class="change" change="border-bottom-color,border-top-color" style="border-top-width: 2px; border-top-style: solid; border-bottom-width: 2px; border-bottom-style: solid; margin-top: -2px; padding: 0px 5px; border-top-color: rgb(61, 167, 66); border-bottom-color: rgb(61, 167, 66); box-sizing: border-box; background-color: rgb(254, 255, 255);"><section style="box-sizing: border-box;">输入标题</section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin: 0.5em 0px; text-align: center; position: static; box-sizing: border-box;"><img style="display: inline-block; width: 1.5em; box-sizing: border-box; vertical-align: middle; background-color: rgb(156, 188, 0);"  src="'+ WeixinEdit.imgurl +'title/2.png"><section style="display: inline-block; vertical-align: middle; text-align: left; font-size: 19.2px; max-width: 70% !important; box-sizing: border-box;"><section style="box-sizing: border-box;">请输入标题<br style="box-sizing: border-box;">请输入标题</section></section><img style="display: inline-block; vertical-align: top; width: 1.5em; box-sizing: border-box; background-color: rgb(156, 188, 0);" src="'+ WeixinEdit.imgurl +'title/3.png"></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section class="change" change="border-bottom-color" style="padding: 3px; display: inline-block; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); box-sizing: border-box;"><section style="box-sizing: border-box;">请输入标题</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section class="change" change="border-top-color" style="padding: 3px; display: inline-block; border-top-width: 1px; border-top-style: solid; border-top-color: rgb(61, 167, 66); box-sizing: border-box;"><section style="box-sizing: border-box;">请输入标题</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section class="change" change="border-bottom-color" style="padding: 3px; display: inline-block; border-bottom-width: 5px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); box-sizing: border-box;"><section style="box-sizing: border-box;">请输入标题</section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; position: static; box-sizing: border-box;"><section class="change" change="border-top-color" style="border-top-color: rgb(61, 167, 66); display: inline-block; border-top-width: 6px; border-top-style: solid; padding: 3px; box-sizing: border-box;"><section style="box-sizing: border-box;">请输入标题</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;"><section style="display: inline-block; box-sizing: border-box;"><section class="change" change="border-left-color" style="border-left-width: 5px; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box;"><span class="change" change="border-left-color" style="width: 0px; display: inline-block; border-left-width: 5px; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 3px !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 3px !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span><section style="display: inline-block; vertical-align: middle; font-size: 20px; padding-left: 2px; box-sizing: border-box;"><section style="box-sizing: border-box;">输入标题</section></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; line-height: 1; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; box-sizing: border-box;"><span class="change" change="border-left-color" style="width: 0px; display: inline-block; opacity: 0.6; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span><span class="change" change="border-left-color" style="width: 0px; display: inline-block; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span></section><section style="display: inline-block; vertical-align: top; line-height: 1.2; padding-left: 3px; box-sizing: border-box;"><section>输入标题</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; line-height: 1; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; box-sizing: border-box;"><span class="change" change="border-right-color" style="width: 0px; display: inline-block; border-right-width: 0.6em; border-right-style: solid; border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"></span><span class="change" change="border-right-color" style="width: 0px; display: inline-block; opacity: 0.6; border-right-width: 0.6em; border-right-style: solid; border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"></span></section><section style="display: inline-block; vertical-align: top; line-height: 1.2; padding-left: 3px; box-sizing: border-box;"><section style="box-sizing: border-box;">左方指向的标题</section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: right; line-height: 1; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; line-height: 1.2; padding-right: 3px; box-sizing: border-box;"><section style="box-sizing: border-box;">​右方指向标题</section></section><section style="display: inline-block; vertical-align: top; box-sizing: border-box;"><span class="change" change="border-left-color" style="width: 0px; display: inline-block; opacity: 0.6; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span><span class="change" change="border-left-color" style="width: 0px; display: inline-block; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; line-height: 1; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; box-sizing: border-box;"><span class="change" change="border-left-color" style="width: 0px; display: inline-block; opacity: 0.6; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span><span class="change" change="border-left-color" style="width: 0px; display: inline-block; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span></section><section style="display: inline-block; vertical-align: top; line-height: 1.2; padding-left: 3px; padding-right: 3px; box-sizing: border-box;"><section style="box-sizing: border-box;">输入标题</section></section><section style="display: inline-block; vertical-align: top; box-sizing: border-box;"><span class="change" change="border-right-color" style="width: 0px; display: inline-block; border-right-width: 0.6em; border-right-style: solid; border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span><span class="change" change="border-right-color" style="width: 0px; display: inline-block; opacity: 0.6; border-right-width: 0.6em; border-right-style: solid; border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; text-align: center; line-height: 1; position: static; box-sizing: border-box;"><section style="display: inline-block; vertical-align: top; box-sizing: border-box;"><span class="change" change="border-right-color" style="width: 0px; display: inline-block; border-right-width: 0.6em; border-right-style: solid; border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span><span class="change" change="border-right-color" style="width: 0px; display: inline-block; opacity: 0.6; border-right-width: 0.6em; border-right-style: solid; border-right-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span></section><section style="display: inline-block; vertical-align: top; line-height: 1.2; padding-left: 3px; padding-right: 3px; box-sizing: border-box;"><section style="box-sizing: border-box;">输入标题</section></section><section style="display: inline-block; vertical-align: top; box-sizing: border-box;"><span class="change" change="border-left-color" style="width: 0px; display: inline-block; opacity: 0.6; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"> </span><span class="change" change="border-left-color" style="width: 0px; display: inline-block; border-left-width: 0.6em; border-left-style: solid; border-left-color: rgb(61, 167, 66); box-sizing: border-box; border-top-width: 0.5em !important; border-top-style: solid !important; border-top-color: transparent !important; border-bottom-width: 0.5em !important; border-bottom-style: solid !important; border-bottom-color: transparent !important;"></span></section></section></section></section></section>',
          
          '<section><section class="change" change="border-left-color" style="display: inline-block; width: 100%; border-left-color: rgb(95, 156, 239); border-right-color: rgb(95, 156, 239); border-style: solid; border-width: 0px 0px 0px 1px; padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;">请输入文字</section></section></section></section></section></section>',
          
          '<section><section class="change" change="border-left-color" style="display: inline-block; width: 100%; border-left-color: rgb(95, 156, 239); border-right-color: rgb(95, 156, 239); border-style: solid; border-width: 0px 0px 0px 3px; padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;">请输入文字</section></section></section></section></section></section>',
          
          '<section><section class="change" change="border-left-color" style="display: inline-block; width: 100%; border-left-color: rgb(95, 156, 239); border-right-color: rgb(95, 156, 239); border-style: solid; border-width: 0px 0px 0px 6px; padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;">请输入文字</section></section></section></section></section></section>',
          
          '<section><section class="change" change="border-right-color" style="display: inline-block; width: 100%; border-left-color: rgb(95, 156, 239); border-right-color: rgb(95, 156, 239); border-style: solid; border-width: 0px 1px 0px 0px; padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="text-align:right;">请输入文字</section></section></section></section></section></section>',
          
          '<section><section class="change" change="border-right-color" style="display: inline-block; width: 100%; border-left-color: rgb(95, 156, 239); border-right-color: rgb(95, 156, 239); border-style: solid; border-width: 0px 3px 0px 0px; padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="text-align:right;">请输入文字</section></section></section></section></section></section>',
          
          '<section><section class="change" change="border-right-color" style="display: inline-block; width: 100%; border-left-color: rgb(95, 156, 239); border-right-color: rgb(95, 156, 239); border-style: solid; border-width: 0px 6px 0px 0px; padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="text-align:right;">请输入文字</section></section></section></section></section></section>',
          
          '<section><section style="  margin: 1em auto;"><section class="change" change="color" style="width: 100%; color: rgb(61, 167, 66); font-size: 18px; line-height: 45px; height: 45px; margin: 0px auto; text-align: center;">请输入标题</section><section class="change" change="border-top-color" style="border-top-width: 2px; border-top-style: solid; border-top-color: rgb(61, 167, 66); height: 2px; width: 100%;"></section><section style="width:18%; background:#fff; margin:-5px auto 0;height:8px;"><section style="width:25%; float:left;text-align:center;margin:0 auto;display:inline-block;"><section class="change" change="background" style="width: 8px; height: 8px; border-radius: 100%; margin: 0px auto; background: rgb(61, 167, 66);"></section></section><section style="width:25%; float:left;text-align:center;margin:0 auto;display:inline-block;"><section class="change" change="background" style="width: 8px; height: 8px; border-radius: 100%; margin: 0px auto; background: rgb(61, 167, 66);"></section></section><section style="width:25%; float:left;text-align:center;margin:0 auto;display:inline-block;"><section class="change" change="background" style="width: 8px; height: 8px; border-radius: 100%; margin: 0px auto; background: rgb(61, 167, 66);"></section></section><section style="width:25%; float:left;text-align:center;margin:0 auto;display:inline-block;"><section class="change" change="background" style="width: 8px; height: 8px; border-radius: 100%; margin: 0px auto; background: rgb(61, 167, 66);"></section></section><section style="height:0; clear: both;"></section></section></section></section>',
          
          '<section><section style="margin:1em auto;"><section style="width:100%; height:auto;margin: 0  auto; "><section class="change" change="background" style="width: 100%; padding: 5px 0px; background: rgb(61, 167, 66);"><section style="margin:0 5px; border:1px solid #fff; "><section style="width:100%; height:8px; margin-top: 6px;"><section style="width: 8px; height: 8px; float: left; border-radius: 100%; padding: 0px; margin: 0px 0px 0px 6px; background: rgb(255, 255, 255);"></section><section style="width: 8px; height: 8px; float: right; border-radius: 100%; padding: 0px; margin: 0px 6px 0px 0px; background: rgb(255, 255, 255);"></section></section><section style="width:100%;color:#fff;"><section style="text-align: center;line-height:30px; font-size:20px; font-weight:bold;">请输入标题</section></section><section style="width:100%; height:8px;margin-bottom: 6px; "><section style="width: 8px; height: 8px; float: left; border-radius: 100%; padding: 0px; margin: 0px 0px 0px 6px; background: rgb(255, 255, 255);"></section><section style="width: 8px; height: 8px; float: right; border-radius: 100%; padding: 0px; margin: 0px 6px 0px 0px; background: rgb(255, 255, 255);"></section></section></section></section></section></section></section>',
          
          '<section><section style="margin: 1em auto;"><section style="width:100%; height:auto;margin:0 auto;"><section style="background:#fff;width:100%; padding: 5px 0;"><section class="change" change="border-color" style="margin: 0px 5px; border: 2px solid rgb(61, 167, 66); border-radius: 6px;"><section style="width:100%; height:8px; margin-top: 6px;"><section class="change" change="border-color,background" style="width: 8px; height: 8px; float: left; border-radius: 100%; padding: 0px; margin: 0px 0px 0px 6px; border-color: rgb(61, 167, 66); background: rgb(61, 167, 66);">​</section><section class="change" change="background" style="width: 8px; height: 8px; float: right; border-radius: 100%; padding: 0px; margin: 0px 6px 0px 0px; background: rgb(61, 167, 66);"></section></section><section class="change" change="color" style="width: 100%; color: rgb(61, 167, 66);"><section style="text-align:center;line-height:30px; font-size: 20px; font-weight: bold;">请输入标题</section></section><section style="width:100%; height:8px;margin-bottom: 6px;"><section class="change" change="background" style="width: 8px; height: 8px; float: left; border-radius: 100%; padding: 0px; margin: 0px 0px 0px 6px; background: rgb(61, 167, 66);"></section><section class="change" change="background" style="width: 8px; height: 8px; float: right; border-radius: 100%; padding: 0px; margin: 0px 6px 0px 0px; background: rgb(61, 167, 66);"></section></section></section></section></section></section></section>',
          
          '<section><section style="margin: 1em auto;"><section style="width:100%; height:auto;margin: 0  auto; text-align: center;"><section class="change" change="border-left-color,border-bottom-color" style="margin:-50px auto 0px; border-left-width: 1px; border-left-style: solid; border-left-color: rgb(61, 167, 66); border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); width: 150px; height: 150px; transform: rotate(-45deg); -webkit-transform: rotate(-45deg); position: relative; z-index: 0;"></section><section class="change" change="border-bottom-color" style="border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); margin-top: -75px; position: relative; z-index: 55;"><section class="change" change="color" style="width: 150px; line-height: 30px; font-size: 22px; margin: 0px auto; color: rgb(61, 167, 66);">标题·标题</section></section><section style="width: 100%; line-height: 20px; font-size: 16px; margin: 0px auto; position: relative; z-index: 55; color: rgb(61, 167, 66);" class="change" change="color">输入内容</section><section style="width: 100%; line-height: 20px; font-size: 16px; margin: 0px auto; padding-bottom: 50px; position: relative; z-index: 55; color: rgb(61, 167, 66);" class="change" change="color">输入内容</section></section></section></section>',
          
          '<section><section style="margin: 1em auto;"><section style="width:100%; height:auto;margin: 0  auto; text-align: center;"><section class="change" change="border-bottom-color" style="height: 30px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); width: 100%;"></section><section style="color:#333;width:60%; margin: -26px auto 0; background: #fff;"><section class="change" change="border-color" style="width: 95%; margin: 0px auto; height: 8px; border-top-width: 1px; border-right-width: 1px; border-left-width: 1px; border-style: solid solid none; border-color: rgb(61, 167, 66);"></section><section class="change" change="color" style="width: 92%; margin: 0px auto; font-size: 22px; word-break: break-all; color: rgb(61, 167, 66);"><p style="font-size: 14px; line-height: 28px; margin-top: 0px; margin-bottom: 0px; text-transform: uppercase;">输入内容<span style="font-size:16px;"> 输入内容</span></p></section><section class="change" change="border-color" style="width: 95%; margin: 0px auto; height: 8px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: none solid solid; border-color: rgb(61, 167, 66);"></section><section style="clear: both; height:0;"></section></section></section></section></section>',
          
          '<section><section style="margin: 1em auto;"><section style="width:100%; height:auto;margin: 0  auto; text-align: center;"><section style="width: 100%; height: auto; margin: 0px auto;"><section class="change" change="color" style="width: 92%; margin: 0px auto; font-size: 22px; word-break: break-all; color: rgb(61, 167, 66);"><p style="font-size: 18px; line-height: 30px; margin-top: 0px; margin-bottom: 0px; text-transform: uppercase;">​输入内容</p></section><section class="change" change="border-bottom-color" style="height: 20px; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(61, 167, 66); width: 100%;"></section><section style="color:#333;width:60%; margin: -18px auto 0; background: #fff;"><section class="change" change="border-color" style="width: 95%; margin: 0px auto; height: 8px; border-top-width: 1px; border-right-width: 1px; border-left-width: 1px; border-style: solid solid none; border-color: rgb(61, 167, 66);"></section><section class="change" change="color" style="width: 92%; margin: 0px auto; font-size: 22px; word-break: break-all; color: rgb(61, 167, 66);"><p style="font-size: 14px; line-height: 18px; margin-top: 0px; margin-bottom: 0px; text-transform: uppercase;">输入内容</p></section><section class="change" change="border-color" style="width: 95%; margin: 0px auto; height: 8px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: none solid solid; border-color: rgb(61, 167, 66);"></section><section style="clear: both; height:0;"></section></section></section></section></section></section>',
          
          '<section><section style="margin: 1em auto;"><section style="width:100%; height:auto;margin: 0 auto; text-align: center;"><section class="change" change="color" style="width: 100%; margin: 0px auto; font-size: 22px; word-break: break-all; color:rgb(22, 134, 33);"><p style="font-size: 18px; line-height: 38px; margin-top: 0px; margin-bottom: 0px; text-transform: uppercase;">输入内容</p></section><section class="change" change="border-top-color" style="width: 0px; height: 0px; margin: -5px auto 0px; border-top-width: 8px; border-top-style: solid; border-top-color: rgb(22, 134, 33); border-left-width: 10px; border-left-style: solid; border-left-color: transparent; border-right-width: 10px; border-right-style: solid; border-right-color: transparent;"></section><section class="change" change="border-top-color" style="width: 0px; height: 0px; margin: -3px auto 0px; border-top-width: 8px; border-top-style: solid; border-top-color: rgb(22, 134, 33); border-left-width: 10px; border-left-style: solid; border-left-color: transparent; border-right-width: 10px; border-right-style: solid; border-right-color: transparent;"></section></section></section></section>'
        ]
      ],
      
      content:[
        'content',
        '正文',
        [
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section style="margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section class="change" change="border-right-color,border-left-color" style="border-left-style: dotted; border-left-width: 6px; border-right-style: dotted; border-right-width: 6px; padding: 10px; border-left-color: rgb(61, 167, 66); border-right-color: rgb(61, 167, 66); box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="text-align: center; box-sizing: border-box;"><section style="box-sizing: border-box; text-align: justify;">在这里替换你的文字内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后TXT文档复制粘贴替换，防止格式错乱。</section></section></section></section></section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="position: static; box-sizing: border-box;"><section class="change" change="border-color" style="margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;"><section class="change" change="border-color" style="display: inline-block; width: 100%; border-radius: 0.7em; box-shadow: rgb(204, 204, 204) 0.2em 0.2em 0.3em; border: 0.14em solid rgb(61, 167, 66); box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="padding: 10px; position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;"><p>在这里替换你的文字内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后TXT文档复制粘贴替换，防止格式错乱。</p></section></section></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="margin: 10px 0%; position: static; box-sizing: border-box;"><section class="96wx-bgpic" style="display: inline-block; width: 100%; vertical-align: top; padding: 2px 3px; line-height: 1.6; box-sizing: border-box; background-image: url('+ WeixinEdit.imgurl +'title/4.gif); background-attachment: scroll; background-size: auto; background-position: 0% 0%; background-repeat: repeat;"><section style="position: static; box-sizing: border-box;"><section style="margin: 1px 0%; transform: translate3d(0px, 0px, 0px); -webkit-transform: translate3d(0px, 0px, 0px); -moz-transform: translate3d(0px, 0px, 0px); -o-transform: translate3d(0px, 0px, 0px); text-align: center; position: static; box-sizing: border-box;"><section style="display: inline-block; width: 100%; border: 1px solid transparent; padding: 10px; box-sizing: border-box; background-color: rgb(254, 255, 255);"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="text-align: left; box-sizing: border-box;"><section style="box-sizing: border-box;">请这里选择替换文字内容</section><section style="box-sizing: border-box;">请这里选择替换文字内容</section></section></section></section></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="margin: 10px 0%; position: static; box-sizing: border-box;"><section class="96wx-bgpic" style="display: inline-block; width: 100%; vertical-align: top; padding: 2px 3px; line-height: 1.6; box-sizing: border-box; background-image: url('+ WeixinEdit.imgurl +'title/5.gif); background-attachment: scroll; background-size: auto; background-position: 0% 0%; background-repeat: repeat;"><section style="position: static; box-sizing: border-box;"><section style="margin: 1px 0%; transform: translate3d(0px, 0px, 0px); -webkit-transform: translate3d(0px, 0px, 0px); -moz-transform: translate3d(0px, 0px, 0px); -o-transform: translate3d(0px, 0px, 0px); text-align: center; position: static; box-sizing: border-box;"><section style="display: inline-block; width: 100%; border: 1px solid transparent; padding: 10px; box-sizing: border-box; background-color: rgb(254, 255, 255);"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="text-align: left; box-sizing: border-box;"><section style="box-sizing: border-box;">请这里选择替换文字内容</section><section style="box-sizing: border-box;">请这里选择替换文字内容</section></section></section></section></section></section></section></section></section></section></section>',
          
          '<section><section style="background-color: rgb(255, 255, 255);"><section style="margin-top:10px; margin-bottom:10px; position: static; box-sizing: border-box;"><section class="change" change="border-color" style="border: 2px solid rgb(61, 167, 66); padding: 10px; border-radius: 0.8em; box-sizing: border-box;"><section class="change" change="border-color" style="border: 2px dotted rgb(61, 167, 66); padding:10px; box-sizing:border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="text-align: center; box-sizing: border-box;"><section style="box-sizing: border-box;">在这里选择替换文字内容</section></section></section></section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;"><section class="change" change="background-color" style="height: 1.3em; box-sizing: border-box; background-color: rgb(46, 181, 52);"><section style="width: 50%; padding-top: 0.25em; display: inline-block; vertical-align: top; box-sizing: border-box;"><section style="width: 0.8em; height: 0.8em; margin: 0px 25% 0px 15%; display: inline-block; vertical-align: top; border-radius: 100%; box-sizing: border-box; background-color: rgb(255, 255, 255);"></section><section style="width: 0.8em; height: 0.8em; display: inline-block; vertical-align: top; border-radius: 100%; box-sizing: border-box; background-color: rgb(255, 255, 255);"></section></section><section style="width: 50%; padding-top: 0.25em; text-align: right; display: inline-block; vertical-align: top; box-sizing: border-box;"><section style="width: 0.8em; height: 0.8em; display: inline-block; vertical-align: top; border-radius: 100%; box-sizing: border-box; background-color: rgb(255, 255, 255);"></section><section style="width: 0.8em; height: 0.8em; margin: 0px 15% 0px 25%; display: inline-block; vertical-align: top; border-radius: 100%; box-sizing: border-box; background-color: rgb(255, 255, 255);"></section></section></section><section class="change" change="border-color" style="border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: none solid solid; border-color: rgb(46, 181, 52); padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后TXT文档复制粘贴替换，防止格式错乱。</section></section></section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin: 0.5em 0px; position: static; box-sizing: border-box;"><section class="change" change="background-color" style="width: 100%; height: 8px; box-sizing: border-box; background-color: rgb(61, 167, 66);"></section><section style="border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: none solid solid; border-color: rgb(204, 204, 204); box-shadow: rgb(204, 204, 204) 0px 0px 8px; box-sizing: border-box;"><section style="font-size: 24px; padding: 5px 10px; box-sizing: border-box;"><section style="box-sizing: border-box;">输入标题</section></section><section style="border-top-width: 1px; border-top-style: solid; border-top-color: rgb(204, 204, 204); padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="font-size: 20px; box-sizing: border-box;"><section style="box-sizing: border-box;"><p><span style="font-size: 16px;">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后TXT文档复制粘贴替换，防止格式错乱。</span></p></section></section></section></section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;"><section class="change" change="background-color" style="display: inline-block; vertical-align: top; border-radius: 100%; width: 3em; height: 3em; text-overflow: ellipsis; line-height: 3em; text-align: center; margin-bottom: -1.5em; color: rgb(255, 255, 255); box-sizing: border-box; background-color: rgb(61, 167, 66);"><section style="box-sizing: border-box;">注:</section></section><section class="change" change="background-color" style="border-radius: 15px; padding: 10px; width: 90%; margin-left: 2em; box-sizing: border-box; background-color: rgb(61, 167, 66);"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="padding-left: 5px; color: rgb(255, 255, 255); box-sizing: border-box;"><section style="box-sizing: border-box;">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后TXT文档复制粘贴替换，防止格式错乱。</section></section></section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin: 0.5em 0px; padding-left: 0.45em; position: static; box-sizing: border-box;"><section class="change" change="border-left-color" style="border-left-width: 2px; border-left-style: dashed; border-left-color: rgb(95, 156, 239); padding: 5px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后TXT文档复制粘贴替换，防止格式错乱。</section></section></section></section></section><section class="change" change="border-top-color" style="width: 0px; margin-left: -0.45em; border-top-width: 1em; border-top-style: solid; border-top-color: rgb(95, 156, 239); border-left-width: 0.5em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 0.5em !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;"></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin: 0.5em 0px; padding-right: 0.45em; position: static; box-sizing: border-box;"><section class="change" change="border-right-color" style="border-right-width: 2px; border-right-style: dashed; border-right-color:rgb(95, 156, 239); padding: 5px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing:border-box;">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后TXT文档复制粘贴替换，防止格式错乱。</section></section></section></section></section><section class="change" change="border-top-color" style="width: 0px; margin-right: -0.45em; float: right; border-top-width: 1em; border-top-style: solid; border-top-color: rgb(95, 156, 239); border-left-width: 0.5em !important; border-left-style: solid !important; border-left-color: transparent !important; border-right-width: 0.5em !important; border-right-style: solid !important; border-right-color: transparent !important; box-sizing: border-box;"></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section>',
          
          '<section><section style="clear: both; position: relative; width: 100%; margin: 0px auto; overflow: hidden;"><section class="change" change="border-top-color,border-bottom-color" style="margin: 1.5em auto 0px; padding: 0.5em 0px; white-space: normal; border-style: solid none; border-top-width: 1px; border-top-color: rgb(211, 36, 109); font-weight: inherit; text-decoration: inherit; border-bottom-width: 1px; border-bottom-color: rgb(211, 36, 109);"><section style="margin-top: -1.8em; padding: 0px; border: none; line-height: 1.4;"><p class="change" change="background-color" style="color: rgb(255, 255, 255); font-size: 16px; font-weight: inherit; padding: 8px 23px; text-align: center; text-decoration: inherit; box-shadow: rgb(104, 104, 202) 2px 2px 10px; display: inline-block; background-color: rgb(211, 36, 109);">常回家看看</p></section><section style="padding: 16px 16px 10px;  line-height: 1.4;  font-size: 1em; box-sizing: border-box; margin: 0px;"><p style="line-height: 1.5em;"><span style="color: rgb(12, 12, 12);">复制文章，不要把素材里面的文字删除，应该选择，然后替换进去，就可以了，文字必须是从txt文档复制到编辑器或者微信公众平台里，目前由于微信公众平台不支持word里面，同时微信公众平台不支持其他格式，所以必须不需要格式复制到微信编辑器或者微信公众平台里面。</span></p></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin: 0.5em 0px; position: static; box-sizing: border-box;"><section class="change" change="border-color" style="display: inline-block; width: 100%; border-bottom-left-radius: 1.6em; border-bottom-right-radius: 1.6em; border: 2px solid rgb(95, 156, 239); padding: 10px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后直接输入，防止格式错乱。</section></section></section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;"><section style="width: 100%; line-height: 1.5em; box-sizing: border-box; background-color: rgb(241, 241, 241);"><section style="width: 100%; box-sizing: border-box;"><section class="change" change="background-color" style="width: 0.62em; height: 0.62em; float: left; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section class="change" change="background-color" style="width: 0.62em; height: 0.62em; float: right; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section style="clear: both; box-sizing: border-box;"></section></section><section style="padding: 10px 5px; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="position: static; box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="box-sizing: border-box;">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后直接输入，防止格式错乱。</section></section></section></section></section><section style="width: 100%; box-sizing: border-box;"><section class="change" change="background-color" style="width: 0.62em; height: 0.62em; float: left; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section class="change" change="background-color" style="width: 0.62em; height: 0.62em; float: right; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section></section></section>',
          
          '<section><section style="box-sizing: border-box; background-color: rgb(255, 255, 255);"><section style="margin-top: 10px; margin-bottom: 10px; position: static; box-sizing: border-box;"><section class="change" change="background-color" style="width: 0.8em; height: 0.8em; float: left; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section class="change" change="background-color" style="width: 0.8em; height: 0.8em; float: right; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section style="clear: both; box-sizing: border-box;"></section><section style="padding: 0px 0.68em; margin: -0.4em; box-sizing: border-box;"><section style="border: 0.14em dotted rgb(193, 193, 193); box-sizing: border-box;"><section style="box-sizing: border-box;"><section style="padding: 10px; position: static; box-sizing: border-box;"><section style="text-align: center; box-sizing: border-box;"><section style="box-sizing: border-box;">替换素材文字内容<br style="box-sizing: border-box;">替换素材文字内容</section></section></section></section></section></section><section class="change" change="background-color" style="width: 0.8em; height: 0.8em; float: left; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section class="change" change="background-color" style="width: 0.8em; height: 0.8em; float: right; box-sizing: border-box; background-color: rgb(95, 156, 239);"></section><section style="clear: both; box-sizing: border-box;"></section></section></section></section>',
          
          '<section><section style=" margin: 1em auto;width:100%;"><section style="width:100%;"><section class="change" change="border-top-color" style="border-top-width: 3px; border-top-style: solid; border-top-color: rgb(61, 167, 66);"><section style="border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-style: none solid solid; border-right-color: rgb(197, 200, 204); border-bottom-color: rgb(197, 200, 204); border-left-color: rgb(197, 200, 204); padding: 12px; line-height: 1.5em; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px; box-shadow: rgba(0, 0, 0, 0.2) 2px 2px 5px;">正文素材<br>在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后直接输入，防止格式错乱。</section></section></section></section></section>',
          
          '<section><section style="margin: 1em auto;"><section style="width:100%; height:auto;margin: 0 auto; "><section class="change" change="background" style="width: 100%; padding: 5px 0px; margin-top: 20px; background: rgb(61, 167, 66);"><section style="margin:0 5px; border:1px solid #fff;"><section style="width:100%; height:8px;"><section style="width: 8px; height: 8px; float: left; border-bottom-right-radius: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255);"></section><section style="width: 8px; height: 8px; float: right; border-bottom-left-radius: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255);"></section></section><section style="width:100%;color:#fff;"><section style="text-align: center; line-height: 30px; font-size: 18px; margin: 0px auto; width: 50%; height: 30px; border-radius: 5px;"><section style="width:100%;border-radius:5px; box-shadow: 1px 1px 4px rgba(255,255,255,0.6) inset;border:1px solid #fff;">标题</section></section><section style="width:95%; margin:0 auto; line-height: 26px; padding:15px 10px; ">在这里输入你的内容，注意不要用删除键把所有文字删除，请保留一个或者用鼠标选取后直接输入，防止格式错乱。</section></section><section style="width:100%; height:8px;"><section style="width: 8px; height: 8px; float: left; border-top-right-radius: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255);"></section><section style="width: 8px; height: 8px; float: right; border-top-left-radius: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255);"></section></section></section></section></section></section></section>',
          
          '<section><section style=" margin: 1em auto; "><section class="change" change="background" style="width: 100%; height: auto; margin: 0px auto; padding: 10px 0px; border-radius: 10px; background: rgb(31, 135, 122);"><section style="width:92%; margin: 0 auto 10px;"><section style="width:42%; float:left; text-align: center;  "><section style="width:100px;height:100px; border-radius: 100%; background: rgba(255,160,255,0.5); margin:0 auto;"><p style="color: rgb(51, 51, 51); padding: 20px; font-size: 18px; font-weight: 700; letter-spacing: 5px; line-height: 25px; margin-top: 0px; margin-bottom: 0px;">使用教程</p></section></section><section style="width:57%; float: left;min-height:100px; "><section style="background: rgba(255,255,255,0.5);border-radius:10px; padding: 10px 0;min-height:80px;"><p style="margin-top: 0px; margin-bottom: 0px; line-height: 26px; padding-right: 2px;"><span style="display:block; float: left; background: rgba(0,0,0,0.5);width:8px; height:8px; border-radius: 100%; margin:10px 0 0 6px "></span> <span style="display: block; float: right;width:85%;">字数不能太多</span> <span style="clear: both;height:0; display: block; "></span></p><p style="margin-top: 0px; margin-bottom: 0px; line-height: 26px; padding-right: 2px;"><span style="display:block; float: left; background: rgba(0,0,0,0.5);width:8px; height:8px; border-radius: 100%; margin:10px 0 0 6px "></span> <span style="display: block; float: right;width:85%;">输入到TXT记事本里</span> <span style="clear: both;height:0; display: block; "></span></p><p style="margin-top: 0px; margin-bottom: 0px; line-height: 26px; padding-right: 2px;"><span style="display:block; float: left; background: rgba(0,0,0,0.5);width:8px; height:8px; border-radius: 100%; margin:10px 0 0 6px "></span> <span style="display: block; float: right;width:85%;">选中替换素材文字</span> <span style="clear: both;height:0; display: block; "></span></p></section></section><section style="clear: both;height:0;"></section></section></section></section></section>',
          
          '<section><section style=" margin: 1em auto;"><section style="width:100%; height:auto;margin: 0 auto; "><section style="line-height: 40px; float: left;"><section class="change" change="background" style="width: 60px; height: 60px; float: right; text-align: center; background: rgb(236, 239, 15);"><p style="font-family: Impact; color: rgb(255, 255, 255); font-size: 30px; line-height: 60px; margin-top: 0px; margin-bottom: 0px;">01</p></section></section><section class="change" change="background" style="width: 75%; float: left; min-height: 60px; background: rgb(236, 239, 15);"><section style="background:rgba(255,255,255,0.5);"><p style="line-height: 22px; padding: 8px; margin-top: 0px; margin-bottom: 0px; min-height: 44px;">读者对一本书感兴趣往往是被封面等外貌因素所吸引</p></section></section><section style="clear: both;height:0;"></section></section></section></section>',
          
          '<section><section style=" margin: 1em auto;"><section style="width:100%; height:auto;margin: 0 auto;"><section style="width:25%;  line-height: 40px; float: left;"><section class="change" change="background" style="width: 60px; height: 60px; float: right; text-align: center; background: rgb(236, 239, 15);"><p style="font-family: Impact; color: rgb(255, 255, 255); font-size: 30px; line-height: 60px; margin-top: 0px; margin-bottom: 0px;">01</p></section></section><section class="change" change="background" style="width: 75%; float: left; min-height: 60px; background: rgb(236, 239, 15);"><section style="background:rgba(255,255,255,0.5);"><p style="line-height: 22px; padding: 8px; margin-top: 0px; margin-bottom: 0px; min-height: 44px;">读者对一本书感兴趣往往是被封面等外貌因素所吸引</p></section></section><section style="clear: both;height:0;"></section></section></section></section>',
          
          '<section><section style="clear: both; position: relative; width: 100%; margin: 0px auto; overflow: hidden;"><section style="position: static; box-sizing: border-box; border: 0px none;margin: 0.5em 0;"><section style="margin-right: auto; margin-left: auto;"><section class="change" change="background-color" style="width: 90%; margin-top: 10px; margin-bottom: 10px; padding: 20px 10px 40px; box-sizing: border-box; border-radius: 10px; color: rgb(255, 255, 255); height: auto !important; background-color: rgb(236, 239, 15);"><section style="line-height: 32px; display: inline-block; width: 100%;"><section style="display: inline-block; float: left; width: 5%;"><section style="color: inherit; /* border-color: rgb(248, 122, 22); */"><p style="font-size: 30px;">问</p></section></section><section style="float: left; padding-left: 20px;width: 90%;"><section style="display:inline-block;"><p style="line-height: 1.5em; margin-top: 0px; margin-bottom: 0px;">唐僧哪了？</p><p style="line-height: 1em; margin-top: 0px; margin-bottom: 0px;">本大王要吃唐僧肉...</p></section></section></section></section><section class="change" change="border-color" style="box-sizing: border-box; width: 90%; border: 1px dashed rgb(236, 239, 15); display: inline-block; margin-left: 10%; margin-top: -40px; padding: 20px 10px; border-radius: 8px; height: auto !important; background-color: rgb(254, 254, 254);"><section style="line-height: 32px; display: inline-block; width: 100%;"><section style="display: inline-block; float: left; width: 5%;"><section><p style="font-size: 30px;">答</p></section></section><section style="float: left; padding-left: 20px;width: 90%;"><section style="display:inline-block;"><p style="line-height: 1.5em; margin-top: 0px; margin-bottom: 0px;">大王，唐僧马上就到火焰山了。</p><p style="line-height: 1.5em; margin-top: 0px; margin-bottom: 0px;">小的已经布置好天罗地网，到时一定能吃到唐僧肉^_^</p></section></section></section></section></section></section></section></section>',
          
          '<section><section style="border-width: 0px; border-style: none; border-top-color: rgb(236, 239, 15); padding: 0px;"><section style="margin: 10px 0px; padding: 0px; text-align: center;"><section style="margin: 0px; padding: 0px; -webkit-transform: rotate(0deg); display: inline-block;"><section style="margin: 0px auto; padding: 0px; width: 25px; height: 25px; background-color: rgb(255, 255, 255); display: table-cell; vertical-align: middle;"><span class="change" change="color" style="color: rgb(236, 239, 15); font-size: 18px;">★</span></section></section><section class="change" change="border-color" style="margin: -16px 0px; padding: 20px 10px; border-top-width: 1px; border-top-style: solid; border-color: rgb(236, 239, 15) rgb(30, 178, 225); border-bottom-width: 1px; border-bottom-style: solid; color: rgb(30, 178, 225);"><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; word-wrap: break-word; color: inherit; font-size: 14px; text-align: justify; line-height: 1.5em;"><span style="color:inherit;">可在这输入内容</span></p></section><section style="margin: 0px; padding: 0px; -webkit-transform: rotate(0deg); display: inline-block;"><section style="margin: 0px auto; padding: 0px; width: 25px; height: 25px; background-color: rgb(255, 255, 255); display: table-cell; vertical-align: middle;"><span class="change" change="color" style="color: rgb(236, 239, 15); font-size: 18px;">★</span></section></section></section></section></section>',
          
          '<section><section style="border-width: 0px; border-style: none; border-top-color: rgb(236, 239, 15); padding: 0px; box-sizing: border-box; margin: 0px;><section style="width: 100%; text-align: left; box-sizing: border-box; padding: 0px; margin: 0px;" data-width="100%"><section class="change" change="border-color" style="width: 100%; padding: 0px; border-top-width: 2px; border-top-style: solid; border-top-color: rgb(236, 239, 15); border-bottom-width: 2px; border-bottom-style: solid; border-bottom-color: rgb(236, 239, 15); color: inherit; font-size: 14px; margin: 15px 0px; display: inline-block; box-sizing: border-box; background-color: rgb(255, 255, 255);" data-width="100%"><section class="change" change="border-color" style="padding: 15px; margin: -15px 15px; border-right-width: 2px; border-right-style: solid; border-right-color: rgb(236, 239, 15); border-left-width: 2px; border-left-style: solid; border-left-color: rgb(236, 239, 15); box-sizing: border-box;"><p style="line-height: 24px; color: rgb(12, 12, 12); border-color: rgb(12, 12, 12); white-space: normal;"><span style="color:rgb(12, 12, 12); font-size:16px">编辑的时候，提倡大家素材先进行上传到服务器</span></p></section></section></section></section></section>',
          
          '<section><section><section class="change" change="border-color" style="width: 100%; border: 2px solid rgb(236, 239, 15); box-sizing: border-box; background-color: rgb(255, 255, 255);"><section class="change" change="border-top-color" style="padding: 0px 15px; margin: 20px 0px; font-size: 1.5em; text-align: center; box-sizing: border-box; border-top-color: rgb(236, 239, 15);"><section style="box-sizing: border-box; border-top-color: rgb(236, 239, 15);">输入标题</section></section><section class="change" change="border-color" style="display: inline-block; height: 2em; border: 2px solid rgb(236, 239, 15); overflow: hidden; line-height: 2em; padding: 0px 10px; border-top-right-radius: 1em; border-bottom-right-radius: 1em; font-size: 1.2em; box-sizing: border-box;"><section>2015/07/27</section></section><section style="padding: 0px 15px; margin: 20px 0px; box-sizing: border-box; border-top-color: rgb(236, 239, 15);"><section style="box-sizing: border-box; border-top-color: rgb(236, 239, 15);">输入文字内容请输入文字内容输入文字内容请输入文字内容</section></section></section></section></section>',
          
          '<section><section style="padding: 0px; box-sizing: border-box; margin: 0px;"><section class="change" change="background-color" style="width: 5em; height: 5em; border-radius: 50%; float: left; padding: 12px 5px; margin-right: 10px; color: rgb(255, 255, 255); box-sizing: border-box; background-color: rgb(236, 239, 15);"><p style="line-height: 1.2em; text-align: center;">内容1</p><p style="line-height: 1.2em; text-align: center;"><span style="font-size: 14px;">内容2</span></p></section><section style="box-sizing: border-box; padding: 0px; margin: 0px;"><p style="clear: none; color: inherit; line-height: 1.75em; white-space: normal;"><span style="color:rgb(127, 127, 127); font-size:14px">内容3</span></p></section></section></section><p style="clear:both"></p>',
          
          '<section><section style="margin: 4em 0 0"><section class="change" change="border-color" style="width:100%; margin-right: 20%; border: 2px dotted rgb(236, 239, 15); border-radius: 0.5em;"><section class="change" change="background-color" style="width: 100%; border-radius: 0.5em; text-align: center; color: rgb(0, 0, 0); border-color: rgb(255, 107, 145); background-color: rgb(236, 239, 15);"><section class="change" change="border-color" style="width: 7em; height: 7em; display: inline-block; vertical-align: middle; border: 1px solid rgb(236, 239, 15); border-radius: 100%; margin: -3.6em auto 0px; box-shadow: rgb(204, 204, 204) 1px 1px 3px;"><section style="box-sizing: border-box; width: 100%; height: 100%; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%; background-image: url('+ WeixinEdit.imgurl +'title/6.jpg); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat no-repeat;"></section></section><section style="padding: 15px"><section>微信编辑器</section></section><section style="width: 90%; border-top-width: 0.1em; border-top-style: solid; border-color: rgba(255,255, 255,0.5); margin-left: 5%; padding: 10px 0px; font-family: inherit; font-style: normal; font-weight: inherit; text-align: center; text-decoration: inherit; color: rgb(0, 0, 0); background-color: transparent;"><section><p style="color: rgb(52, 54, 60); font-size: 17px; line-height: 23px; white-space: normal;">本素材的头像是正方形，先上传素材后进行图片地址替换</p></section></section></section></section></section></section>',
          
          '<section><section style="margin: 0.5em 0px;"><section class="change" change="background-color" style="display: inline-block; vertical-align: top; padding: 10px; text-align: center; margin:0; font-size: 1em; color: rgb(99, 100, 84); border-color: rgb(249, 110, 87); background-color: rgb(236, 239, 15);"><section><section><strong><span style="font-size: 18px;">微信编辑器</span></strong></section></section><section style="margin: 10px 15px; font-size: 1em;"><section>微信号：xx</section></section><section style="margin: 0.5em auto; box-sizing: border-box; border: 3px solid rgb(248, 248, 248); width: 10em; height: 10em; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%;"><section style="box-sizing: border-box; margin: 5%; width: 90%; height: 90%; border-radius: 100%; border-top-color: rgb(236, 239, 15); background-image: url('+ WeixinEdit.imgurl +'title/6.jpg); background-size: cover; background-position: 50% 50%; background-repeat: no-repeat;"></section></section><section style="margin: 15px"><section style="border-top-color: rgb(236, 239, 15);"><p style="color: rgb(52, 54, 60); font-size: 17px; line-height: 23px; white-space: normal;">本素材的头像是正方形，先上传素材后进行图片地址替换。</p></section></section></section></section></section>'
        ]
      ],
      
      background:[
        'background',
        '背景',
        [
          '<section><section class="change" change="background-color" style="white-space: normal; padding: 12px 15px; color: rgb(255, 255, 255); margin:0; min-height: 1em; background-color: rgb(0, 187, 236);"><p>纯色背景-输入字体即可</p></section></section>',
          
          '<section><section style="font-size: 16px; border-top-color: rgb(43, 180, 214);"><table width="100%" cellspacing="0" cellpadding="8" uetable="null"><tbody><tr><td style="border: 4px double rgb(68, 130, 223); word-wrap: break-word; word-break: break-all; color: rgb(255, 255, 255); background-image: url('+ WeixinEdit.imgurl +'background/1.gif); box-sizing: border-box !important;"><p style="line-height: 1.6em;">在这里面输入文字内容或者插入素材样式，可以作为整篇文章的背景，边框可以改变颜色。</p></td></tr></tbody></table></section></section>',
          
          '<section><section style="font-size: 16px"><table width="100%" cellspacing="0" cellpadding="8" border="0"><tbody><tr><td style="color: rgb(255, 255, 255); background-image: url('+ WeixinEdit.imgurl +'background/1.gif); box-sizing: border-box !important;"><p style="line-height: 1.6em;">在这里面输入文字内容或者插入素材样式，可以作为整篇文章的背景，边框可以改变颜色。</p></td></tr></tbody></table></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/2.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/3.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/4.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/5.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/6.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/7.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/8.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/9.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/10.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/10.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/11.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/12.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/13.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/14.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/15.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/16.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/17.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px;">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/18.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/19.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/20.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/21.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/22.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/23.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/24.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/25.jpg); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/26.jpg); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/27.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>',
          
          '<section><section style="margin:0; padding: 5px 15px 20px; background-image: url('+ WeixinEdit.imgurl +'background/28.gif); word-wrap: break-word !important; box-sizing: border-box !important;"><p style="font-size: 16px; color:#FFF">在这里输入你的内容或者插入样式，可以作为整篇文字的背景,背景色可以更改</p></section></section>'
        ]
      ],
      
      hrs:[
        'hrs',
        '分割线',
        [
          '<section>​<p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/1.gif"></p></section>',
          
          '<section>​<hr class="change" change="border-color" style="border:none; border-top: solid 1px rgb(99, 161, 225); margin:10px auto;"></section>',
          
          '<section>​<hr class="change" change="border-color" style="border:none; border-top: solid 2px rgb(99, 161, 225); margin:10px auto;"></section>',
          
          '<section>​<hr class="change" change="border-color" style="border:none; border-top: solid 4px rgb(99, 161, 225); margin:10px auto;"></section>',
          
          '<section>​<hr class="change" change="border-color" style="border:none; border-top: dashed 4px rgb(99, 161, 225); margin:10px auto;"></section>',
          
          '<section>​<hr class="change" change="border-color" style="border:none; border-top: dotted 4px rgb(99, 161, 225); margin:10px auto;"></section>',
          
          '<section>​<hr class="change" change="border-color" style="border:none; border-top: double 5px rgb(99, 161, 225); margin:10px auto;"></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/2.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/3.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/4.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/5.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/6.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/7.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/8.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/9.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/10.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/11.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/12.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/13.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/14.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/15.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/16.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/17.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/18.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/19.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/20.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/21.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/22.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/23.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/24.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/25.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/26.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/27.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/28.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/29.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/30.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/31.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/32.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/33.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/34.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/35.png"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/36.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/37.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/38.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/39.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/40.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/41.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/42.jpg"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/43.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/44.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/45.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/46.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/47.gif"></p></section>',
          
          '<section><p><img style="margin:10px auto; display:block; max-width:100%" src="'+ WeixinEdit.imgurl +'hrs/48.gif"></p></section>'
        ]
      ],
      
      picture:[
        'picture',
        '图片',
        [
          '<section><section style=" margin-top: 0.5em; margin-bottom: 0.5em; padding-left: 0.5em; padding-right: 0.5em;  box-sizing: border-box; "><img style="box-sizing: border-box; margin: 0px 20% -1em; vertical-align: middle; width: 60% !important; height: auto !important; visibility: visible !important;" border="0" src="'+ WeixinEdit.imgurl +'picture/1.png"><section style="transform: rotate(0deg); -webkit-transform: rotate(0deg); box-sizing: border-box;"><img style="box-sizing: border-box; vertical-align: middle; width: 100% !important; height: auto !important; visibility: visible !important;" border="0" src="'+ WeixinEdit.imgurl +'picture/2.png"></section><img style="box-sizing: border-box; margin: -0.6em 0px -0.5em 3%; border: 0.8em solid white; box-shadow: rgb(102, 102, 102) 0.2em 0.2em 0.5em; vertical-align: middle; width: 94% !important; visibility: visible !important;" border="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"><img style="box-sizing: border-box; vertical-align: middle; width: 100% !important; visibility: visible !important;" border="0" src="'+ WeixinEdit.imgurl +'picture/4.png"></section></section>',
          
          '<section><section style=" margin: 0.5em auto; text-align: center;  box-sizing: border-box; "><img style="border-radius: 3.8em 0px; box-sizing: border-box; vertical-align: middle; width: auto !important; visibility: visible !important;" border="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section></section>',
          
          '<section><section style="border: 32px solid rgb(12, 137, 24); -webkit-border-image: url('+ WeixinEdit.imgurl +'picture/5.png) 45 fill;"><p style="text-align: center; white-space: normal;"><img style="width: 100%; margin: 0px; height: auto !important;" width="100%" height="auto" border="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></p></section></section>',
          
          '<section><section style="display:inline-block; transform: rotate(40deg); -webkit-transform: rotate(40deg);margin-left:1em;"><p><img style="width:50px;height:auto ！important;" src="'+ WeixinEdit.imgurl +'picture/6.png"></p></section><section style="margin-top:-6em;"><section class="change" change="background-color" style="margin: 0px 16px; padding: 20px; color: rgb(255, 255, 255); background-color: rgb(61, 167, 66);"><p style="text-align: center;"><img style="width: 100%; margin: 0px;" width="100%" height="" border="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></p></section><section style="margin-top:-5.4em;"><p style="text-align: right;"><img src="'+ WeixinEdit.imgurl +'picture/7.png"></p></section></section></section>',
          
          '<section><section style="position: relative; width: 100%; margin: 0 auto;"><section style="box-sizing: border-box; margin: 0.5em 0;text-align: center;"><section style="width: 200px; height: 200px; display: inline-block; opacity: 0.7;"><img style="border-radius: 50%; display: inline-block; width: 200px; margin: 0px;" height="200" border="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section><section style="margin-top: -90px;"><section style="transform: rotate(0); -webkit-transform:rotate(0);"><section><section style="border: 1px solid rgb(81, 45, 161); padding: 5px; width: 41px; display: inline-block;"><p style="color: inherit; line-height: 1.6em;"><span style="font-size: 28px; color: rgb(89, 89, 89); font-weight: bold;">旅</span></p></section></section><section><section style="border-left-width: 1px; border-left-style: solid; border-color: rgb(81, 45, 161); padding: 5px; width: 41px; display: inline-block; border-right-width: 1px; border-right-style: solid;"><p style="color: inherit; line-height: 1.6em;"><span style="font-size: 16px; color: rgb(89, 89, 89); font-weight: bold;">●</span></p></section></section><section><section style="border: 1px solid rgb(81, 45, 161); padding: 5px; width: 41px; display: inline-block;"><p style="color: inherit; line-height: 1.6em;"><span style="font-size: 28px; color: rgb(89, 89, 89); font-weight: bold;">游</span></p></section></section></section></section><section style="margin-top: -82px;"><img style="border-radius: 50%; display: inline-block; width: 200px; margin: 0px;" width="200" height="" border="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section></section></section></section>',
          
          '<section><section style="margin: 0.5em auto; text-align: center;"><img style="border-radius: 1.5em; box-sizing: border-box; margin: 0px; max-width:100%; visibility: visible !important;" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section></section>',
          
          '<section><section style="margin:0 auto"><section style="border:26px solid rgb(249, 110, 87); -webkit-border-image: url('+ WeixinEdit.imgurl +'picture/8.png) 20 fill; border-width: 28px;"><p style="text-align: center; white-space: normal;"><img style="width: 100%;height: auto !important;" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></p></section></section></section>',
          
          '<section><section style=" margin: 1em auto;"><section style="width:21.7em; margin:0 auto; "><section style="margin-bottom:5px; "><section style="width:49% !important; float:left; "><img style="width:100% !important; height:150px;" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section><section style="width:49%; float:right; "><img style="width:100% !important;height:150px !important;" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section></section><div style="clear:both"></div><section><img style="width:100% !important; height:auto !important; margin-top:8px" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section></section></section><div style="clear:both"></div></section>',
          
          '<section><section style="width: 100%; clear: both; overflow: hidden; margin: 10px auto; word-wrap: break-word !important;"><img style="float: left; width:100%; border-radius: 4px; box-sizing: border-box !important; word-wrap: break-word !important; height: auto !important; border="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"><section style="box-sizing: border-box !important; width: 100%; word-wrap: break-word !important; margin-top: -13.5em; text-align: center; color: rgb(4, 4, 4); float: left; font-size: 14px;"><span style="width: 100%; background-color: rgb(255, 255, 255); width: 150px; height: 150px; margin:0 auto; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%; margin: 0px auto; opacity: 0.5; display: block; padding-top: 30px; box-sizing: border-box !important; word-wrap: break-word !important;"><span style="box-sizing: border-box !important; max-width: 100%; word-wrap: break-word !important; border-bottom-width: 1px; border-bottom-style: solid; border-bottom-color: rgb(0, 0, 0); padding-bottom: 3px; font-size: 24px; margin-bottom: 10px; margin-top: 20px;">春</span><p style="margin-top: 10px; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;">好雨知时节</p><p style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;">当春乃发生</p></span></section></section></section>',
          
          '<section><section style="font-size:14px;margin: 5px auto;white-space: normal;"><section style="box-sizing:border-box"><img style="width: 200px; float: left; margin-right: 10px; margin-bottom: 5px; height: 130px;" width="200" height="130" src="'+ WeixinEdit.imgurl +'picture/3.jpg"><section style="font-size:14px;font-family:inherit;line-height:30px;text-decoration:inherit"><section>春天的雨是连绵的、柔和的，它滋润着大地，抚摸着大地，小声地呼唤着大地，在人们不知不觉的时候，他们竟悄悄地汇成了小河，积成了深潭。啊，原来是春雨给潭水带来绿色的生命。</section></section></section></section></section>',
          
          '<section><section style="margin: 5px auto; white-space: normal;"><section style="border:20px solid #f96e57; border-image-source:url('+ WeixinEdit.imgurl +'picture/9.png); border-image-slice:20; box-sizing:border-box;padding:0;margin:0"><p style="text-align: center;"><img style="width:100%; margin: 0px auto; display:block; height:auto !important;" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></p></section></section></section>',
          
          '<section><section style="border-top-color: rgb(81, 45, 161);"><fieldset style="border: 0px rgb(0, 187, 236); margin: 0px; box-sizing: border-box; clear: both; padding: 0px; text-align: center;"><p style="box-sizing: border-box; padding: 0px; margin-top: 0px; margin-bottom: 0px; color: inherit; border-color: rgb(30, 155, 232);"><img border="0" style="box-sizing: border-box; color: inherit; display: block; margin: 0px; padding: 0px; width: 100%; border-color: rgb(30, 155, 232);" vspace="0" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></p><section style="display: inline-block; margin-top: -5em; box-sizing: border-box; width: 100%; padding: 0px; border-color: rgb(0, 187, 236);"><section class="change" change="background-color" style="margin: 0px; padding: 0.5em; width: 8em; height: 8em; border-radius: 50%; display: inline-block; box-sizing: border-box; color: rgb(255, 255, 255); border: 2px solid rgb(254, 254, 254); background-color: rgb(81, 45, 161);"><section style="line-height: 1.2; font-weight: inherit; text-decoration: inherit; color: inherit; box-sizing: border-box; border-color: rgb(0, 187, 236); display: inline-block; padding: 0px; margin: 0px;"><p style="box-sizing: border-box; color: inherit; border-color: rgb(0, 187, 236); padding-top: 1.2em; margin-top: 0px; margin-bottom: 0px;"><span style="border-color: rgb(81, 45, 161) rgb(0, 187, 236) rgb(0, 187, 236); box-sizing: border-box; font-size: 30px; letter-spacing: 10px; margin: 0px; padding: 0px;">牧场</span></p><p style="font-size: 8px; box-sizing: border-box; color: inherit; border-color: rgb(0, 187, 236); padding-top: 2px; margin-top: 0px; margin-bottom: 0px;"><span style="box-sizing: border-box; font-size: 8px; margin: 0px; padding: 0px; color: inherit; border-top-color: rgb(81, 45, 161);">呼伦贝尔草原</span></p></section></section></section></fieldset></section><p><br></p></section>',
          
          '<section><section style="margin: 0.8em 0px 0.3em; box-sizing: border-box; padding: 0px;"><section style="margin: 0px 0px 2px 4px; text-align: right;"><section style="display: inline-block; width: 60%; border-color: rgb(30, 155, 232);"><img style="-webkit-box-reflect: below 0px -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), color-stop(0.7, transparent), to(rgba(250, 250, 250, 0.298039))); border-color: rgb(30, 155, 232); color: inherit; width: 100%;" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section></section><section style="margin: -90px 0px 2px 4px; color: rgb(30, 155, 232); border-color: rgb(30, 155, 232); width: 60%;"><img style="-webkit-box-reflect: below 0px -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), color-stop(0.7, transparent), to(rgba(250, 250, 250, 0.298039))); border-color: rgb(81, 45, 161) rgb(30, 155, 232) rgb(30, 155, 232); color: inherit; width: 100%;" src="'+ WeixinEdit.imgurl +'picture/3.jpg"></section><p style="line-height: 3.5em; border-color: rgb(30, 155, 232);"></p></section></section>',
        ]
      ],
      
      qrcode:[
        'qrcode',
        '二维码',
        [
          '<section><section style="border-style: none; width: 320px; clear: both; overflow: hidden; margin: 0 auto;"><section style="width: 100%; float: left; padding: 0 0.1em 0 0;" data-width="100%"><img style="width: 295px !important; height: auto !important;" src="'+ WeixinEdit.imgurl +'qrcode/1.gif"><section style="padding:0.1em 2em;float: left;font-size: 17px; margin-top: -10.5em;margin-left:0.2em;text-align: center; color: #fff; opacity: 0.95; background-color: abg(255,255,255);"><img style="width: 130px !important; height: 130px!important;" src="'+ WeixinEdit.imgurl +'qrcode/qrcode.png"></section></section></section></section>',
          
          '<section><section style="border-style: none; width: 320px; clear: both; overflow: hidden; margin: 0 auto;"><section style="width: 100%; float: left; padding: 0 0.1em 0 0;" data-width="100%"><img style="width: 295px !important; height: auto !important;" src="'+ WeixinEdit.imgurl +'qrcode/2.gif"><section style="padding:0.1em 2em;float: left;font-size: 17px; margin-top: -11em; margin-left:-1em;text-align: center; color: #fff; opacity: 0.95; background-color: abg(255,255,255);"><img style="width: 130px !important; height: 130px!important;" src="'+ WeixinEdit.imgurl +'qrcode/qrcode.png"></section></section></section></section>',
          
          '<section><section style="width: 320px !important;; clear: both; overflow: hidden;margin: 0 auto;"><section style="width: 100%; float: left; padding: 0 0.1em 0 0;"><img style="width: 295px !important;; height: auto !important;" src="'+ WeixinEdit.imgurl +'qrcode/3.gif"><section style="padding:0.2em 1em;float: left;font-size: 14px; margin-top: -10.5em; margin-left:0.2em;text-align: center; color: #fff; opacity: 0.95; background-color: abg(255,255,255);"><img style="width: 130px !important;; height: 130px!important;;" src="'+ WeixinEdit.imgurl +'qrcode/qrcode.png"></section></section></section></section>',
          
          '<section><section style="border-style: none; width: 296px; clear: both; overflow: hidden;margin: 0 auto;"><br/><section style="width: 100%; float: left; padding: 0 0.1em 0 0;"><img style="width: 295px; height: auto !important;" border="0" vspace="0" src="'+ WeixinEdit.imgurl +'qrcode/4.jpg"/><section style="padding:0.1em 0.8em;float: left;font-size: 17px; margin-top: -180px; text-align: center; opacity: 0.85;"><img style="width: 110px; height: 110px;" src="'+ WeixinEdit.imgurl +'qrcode/qrcode.png"/></section></section></section></section>',
          
          '<section><section style="border-style: none; width: 296px; clear: both; overflow: hidden;margin: 0 auto;"><br/><section style="width: 100%; float: left; padding: 0 0.1em 0 0;"><img style="width: 295px; height: auto !important;" border="0" vspace="0" src="'+ WeixinEdit.imgurl +'qrcode/5.png"/><section style="padding:0.1em 0.8em;float: left;font-size: 17px; margin-top: -7.4em;text-align: center; color: #fff; opacity: 0.85;"><img style="width: 110px; height: 110px;;margin-top:5px;" src="'+ WeixinEdit.imgurl +'qrcode/qrcode.png"/></section></section></section></section>'
        ]
      ],
      
      sign:[
        'sign',
        '符号',
        [
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/1.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/2.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/3.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/4.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/5.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/6.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/7.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/8.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/9.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/10.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/11.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/12.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/13.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/14.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/15.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/16.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/17.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/18.jpg"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/19.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/20.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/21.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/22.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/23.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/24.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/25.png"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/26.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/27.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/28.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/29.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/30.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/31.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/32.png"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/33.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/34.png"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/35.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/36.gif"></p></section>',
          '<section><p><img src="'+ WeixinEdit.imgurl +'sign/37.gif"></p></section>'
        ]
      ],
      
      original:[
        'original',
        '原文',
        [
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/1.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/2.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/3.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/4.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/5.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/6.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/7.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/8.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/9.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/10.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/11.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/12.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/13.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/14.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/15.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/16.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/17.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/18.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/19.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'original/20.gif"></p>'
        ]
      ],
      
      share:[
        'share',
        '分享',
        [
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/1.jpg"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/2.png"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/3.jpg"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/4.jpg"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/5.jpg"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/6.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/7.jpg"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/8.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/9.jpg"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/10.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/11.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/12.gif"></p>',
          '<p><img style="max-width:100%" src="'+ WeixinEdit.imgurl +'share/13.gif"></p>'
        ]
      ],
      
      introduce:[
        'introduce',
        '互推',
        [
          '<section><section style="clear: both; position: relative; width: 100%; margin: 0px auto; overflow: hidden;"><section style="background-color:#fbfbfb;margin: 0.5em 0;"><section style="color:#333;margin:0;padding:10px;border-bottom: 2px solid #ddd;"><p style="font-weight: bold;">联系我们</p></section><section style="overflow: hidden; border-bottom-width: 1px; border-bottom-style: dotted; border-bottom-color: rgb(221, 221, 221); color: rgb(84, 84, 84);"><span style="background-image:url('+ WeixinEdit.imgurl +'introduce/1.png);background-position:0 -135px ; background-repeat:no-repeat;display:block;width:24px;height:24px;float:left;margin-top:6px;margin-left:10px;"></span><span style="display:block;float:left;margin-left:10px;line-height:38px;">电话：</span><span style="display:block;float:left;margin-left:10px;line-height:38px;">156xxxxxxxx</span></section><section style="overflow: hidden; border-bottom-width: 1px; border-bottom-style: dotted; border-bottom-color: rgb(221, 221, 221); color: rgb(84, 84, 84);"><span style="background-image:url('+ WeixinEdit.imgurl +'introduce/1.png);background-position:0 -472px ; background-repeat:no-repeat;display:block;width:24px;height:24px;float:left;margin-top:6px;margin-left:10px;"></span><span style="display:block;float:left;margin-left:11px;line-height:38px;">QQ：</span><span style="display:block;float:left;margin-left:10px;line-height:38px;">xxxxxxxx（群）</span></section><section style="overflow: hidden; border-bottom-width: 1px; border-bottom-style: dotted; border-bottom-color: rgb(221, 221, 221); color: rgb(84, 84, 84);"><span style="background-image:url('+ WeixinEdit.imgurl +'introduce/1.png);background-position:0 0px ; background-repeat:no-repeat;display:block;width:24px;height:24px;float:left;margin-top:8px;margin-left:10px;"></span><span style="display:block;float:left;margin-left:10px;line-height:38px;">微信：</span><span style="display:block;float:left;margin-left:10px;line-height:38px;">xxxxxxx</span></section><section style="overflow: hidden; border-bottom-width: 1px; border-bottom-style: dotted; border-bottom-color: rgb(221, 221, 221); color: rgb(84, 84, 84);"><span style="background-image:url('+ WeixinEdit.imgurl +'introduce/1.png);background-position:0 -270px ; background-repeat:no-repeat;display:block;width:24px;height:24px;float:left;margin-top:6px;margin-left:10px;"></span><span style="display:block;float:left;margin-left:10px;line-height:38px;">地址：</span><span style="display:block;float:left;margin-left:10px;line-height:38px;">安徽省合肥</span></section><section style="overflow: hidden; border-bottom-width: 1px; border-bottom-style: dotted; border-bottom-color: rgb(221, 221, 221); color: rgb(84, 84, 84);"><span style="background-image:url('+ WeixinEdit.imgurl +'introduce/1.png);background-position:0 -31px ; background-repeat:no-repeat;display:block;width:24px;height:24px;float:left;margin-top:6px;margin-left:10px;"></span><span style="display:block;float:left;margin-left:10px;line-height:38px;">官网：</span><span style="display:block;float:left;margin-left:10px;line-height:38px;">bj.96weixin.com</span></section><section style="overflow: hidden; border-bottom-width: 1px; border-bottom-style: dotted; border-bottom-color: rgb(221, 221, 221); color: rgb(84, 84, 84);"><span style="background-image:url('+ WeixinEdit.imgurl +'introduce/1.png);background-position: 0px -99px; background-repeat:no-repeat;display:block;width:24px;height:24px;float:left;margin-top:6px;margin-left:10px;"></span><span style="display:block;float:left;margin-left:10px;line-height:38px;">邮箱：</span><span style="display:block;float:left;margin-left:10px;line-height:38px;">56790468@qq.com</span></section></section></section></section>',
          
          '<section><blockquote class="change" change="background-color" style="margin: 0px; padding: 5px 20px; border-radius: 15px 15px 2px 2px; border: 2px rgb(66, 249, 255); width: 180px; text-align: center; color: rgb(255, 255, 255); line-height: 24px; font-size: 16px; font-weight: 700; white-space: normal; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important; box-shadow: 0px -1px 6px #999; text-shadow: 0px -1px 2px #0a0a0a; background-color: rgb(0, 187, 236);">关注我</blockquote><blockquote style="margin: 0px; padding: 10px; border-radius: 0px 0px 10px 10px; border: 1px solid rgb(204, 204, 204); color: rgb(62, 62, 62); line-height: 24px; font-size: 12px; white-space: normal; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important; box-shadow: 0px -1px 6px rgb(204,204,204); background-color: rgb(228, 228, 228);"><span style="color: rgb(0, 176, 80); -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">微信号：</span><span class="change" change="background-color" style="padding: 2px 5px; color: rgb(255, 255, 255); font-weight: 700; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important; background-color: rgb(0, 187, 236);">xxxxxxxx</span><span style="color: rgb(0, 187, 236); padding-left: 5px; -ms-word-wrap: break-word !important; max-width: 100%; box-sizing: border-box !important;">(←长按复制) </span><p style="padding: 10px 0px 0px; color: rgb(102, 102, 102); line-height: 2em; margin-top: 0px; margin-bottom: 0px; white-space: pre-wrap; -ms-word-break: normal; -ms-word-wrap: normal; min-height: 1.5em; max-width: 100%; box-sizing: border-box !important;">了解更多，请关注微信号</p></blockquote></section>',
          
          '<section><section style="margin: 0px; padding: 0px;"><span class="96wx-color" style="margin: 0px; padding: 0px; font-family: arial, helvetica, sans-serif;"><strong style="color: rgb(102, 102, 102);"><span class="change" change="background-color" style="margin: 0px 8px 0px 0px; padding: 4px 10px; color: rgb(255, 255, 255); border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; background-color: rgb(191, 0, 0);">输入内容1</span></strong><strong><span class="change" change="color" style="margin: 0px; padding: 0px; color: rgb(192, 0, 0);">ID:输入内容2</span></strong></span></section><p style="margin-top: 0px; margin-bottom: 0px; padding: 0px; max-width: 100%; clear: both; line-height: 19px; box-sizing: border-box !important; word-wrap: break-word !important;"><br>输入内容3</p></section>',
          
          '<section><section style="border: 0px; padding: 0px; box-sizing: border-box;"><section style="margin-left: 1em; line-height: 1.4; box-sizing: border-box;"><span class="change" change="background-color" style="display: inline-block; padding: 3px 8px; border-top-left-radius: 4px; border-top-right-radius: 4px; border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; color: rgb(255, 255, 255); font-size: 1em; background-color: rgb(0, 187, 236); border-color: rgb(0, 187, 236); box-sizing: border-box;"><section style="box-sizing: border-box;">微信ID</section></span> <span style="display: inline-block; padding: 3px 8px; margin-left: 4px; border-top-left-radius: 1.2em; border-top-right-radius: 1.2em; border-bottom-right-radius: 1.2em; border-bottom-left-radius: 1.2em; color: rgb(255, 255, 255); font-size: 1em; line-height: 1.2; background-color: rgb(204, 204, 204); border-color: rgb(0, 187, 236); box-sizing: border-box;"><section style="box-sizing: border-box;">请输入内容1</section></span></section><section style="margin-top: -11px; padding: 22px 16px 16px; border: 1px solid rgb(192, 200, 209); color: rgb(51, 51, 51); font-size: 1em; background-color: rgb(239, 239, 239); box-sizing: border-box;"><section style="box-sizing: border-box;">请输入内容2。</section></section></section></section>',
        ]
      ],
      
      festival:[
        'festival',
        '节日',
        [
          '<p><img style="max-width:100%; display:block; margin:0 auto;" src="'+ WeixinEdit.imgurl +'festival/1.png"></p>',
          
          '<p><img style="max-width:100%; display:block; margin:0 auto;" src="'+ WeixinEdit.imgurl +'festival/2.png"></p>',
          
          '<section><section style="width:21.7em; height:auto; margin:0 auto;"><section style="border: 4px solid rgb(190, 163, 123); border-radius: 3px;"><section style="text-align: center; height: 12.58333em;"><img style="width: 100% !important; height: auto !important;" src="'+ WeixinEdit.imgurl +'festival/3.png"></section><section style="text-align: center; height:0.75em; margin-top:-0.75em;"><img style="width:21em;height:0.75em;" src="'+ WeixinEdit.imgurl +'festival/4.png"></section><section style="padding:1em; line-height: 2em;"><section style="font-size:1.2em; ">父亲是一泓美丽的清泉，让生活洒满恬静；父亲是一轮明亮的太阳，让生活充满温暖；父亲是一座挺拔的高山，让生活总是向上；父亲是一首温馨的歌谣，让生活总是快乐。父亲节到了，愿父亲节日快乐！</section></section></section></section></section>',
          
          '<section><section style="margin: 5px auto;"><section style="border: medium none rgb(0, 0, 0); margin: 2em 0px; padding-bottom: 1.5em; font-weight: bold; text-align: center;"><img style="width:78px; margin-right:10px; margin-top: 7px" src="'+ WeixinEdit.imgurl +'festival/5.gif"><section style="border-left-width: 2px; border-left-style: solid; border-color: rgb(165, 0, 3); padding-left: 1em; text-align: left; display: inline-block; height: 3.7em; line-height: 3.7em; vertical-align: top;"><section style="width: 100%; overflow: hidden; margin-bottom: -1px; font-size: 24px;">父亲节<br></section></section></section></section></section>',
          
          '<section><section style="overflow: hidden; margin: 1em auto; width: 100%; text-align: center; border: 2px solid rgb(61, 167, 66);"><section style="padding:20px 20px 49px 20px;font-size: 15px;line-height:1.8;">粽子香嗅到儿时的时光艾叶香挂在门上龙舟荡是碧波的狂欢不去想有着淡淡的忧伤叹只叹命运无常算了吧不要彷徨忘了吧冷落的眼光霓虹灯哪里有醉人的光芒可能已忘了故乡。</section><img style="float: right; margin-right: 10px; margin-top: -50px; width: 150px; height: 119px;" src="'+ WeixinEdit.imgurl +'festival/6.png"></section></section>',
          
          '<section><section style="height:auto; margin:0 auto;"><section style="text-align: center; margin:0 auto;"><img style="width: 160px !important; height: 70px !important;" src="'+ WeixinEdit.imgurl +'festival/7.png"></section><section style="border: 2px solid rgb(21, 133, 47); padding: 1em; border-radius: 0.3em; line-height: 2em; margin-top: -1.5em; color: rgb(123, 48, 42);"><section style="font-size: 1.2em; color: rgb(21, 133, 47);">粽子淡淡的清香，在艾草的苦味中飘浮。笛声悠远，麦子饱满，汩罗江边，先生那...端午是竹叶的色彩，端午是艾草的青涩，端午是麦收的季节，端午是屈原的祭日，端午是人们永远的牵挂！</section></section></section></section>',
          
          '<section><section style="width:21.7em; height:auto; margin:0 auto;"><section style="color: rgb(255, 255, 255); text-align: center; height: 5em; line-height: 5em; background-image: url('+ WeixinEdit.imgurl +'festival/8.png); background-size: 21.7em; background-position: 50% 0%; background-repeat: no-repeat;"><section style="font-size:1.4em; font-weight: bold; padding-left: 1em; ">端午粽情标题</section></section></section></section>',
          
          '<section><section style="width:21.7em; height:auto; margin:0 auto;"><section style="width:5em;float: left; height:5em;"><img style="width:5em !important;height: 5em !important; " src="'+ WeixinEdit.imgurl +'festival/9.png"></section><section style="width:16.7em; float: left; line-height: 3em; padding:1em 0; "><section style=" font-size:1.3em; padding-left:0.5em;">帝高阳之苗裔兮，朕皇考..</section></section><section style="clear: both;height: 0;"></section></section></section>',
          
          '<section><section style="width:21.7em; height:auto; margin:0 auto; font-size:12px;"><section style="min-height: 2.3em; padding-bottom: 2.3em; background-image: url('+ WeixinEdit.imgurl +'festival/10.png); background-size: 21.7em 5em; background-origin: initial; background-position: 50% 100%; background-repeat: no-repeat;"><section style=" width:20em; margin: 0 auto;  text-align: center; overflow: hidden; "><section style="font-size:1.4em;color:#0a4910; font-weight: bold; line-height: 1.6em; ">端午节标题</section><section style="margin: 0px auto;"><img style="width: 14em; height: auto;" src="'+ WeixinEdit.imgurl +'festival/11.png"></section></section></section></section></section>',
          
          '<section><section style="margin: 1em auto; text-align:center;"><img style="display: block; width:125px !important; height:auto !important; margin: 3px auto;" src="'+ WeixinEdit.imgurl +'festival/12.png"></section></section>',
          
          '<section style="text-align: center;"><img style="max-width:100%;" src="'+ WeixinEdit.imgurl +'festival/13.png"></section>',
          
          '<section><section style="width:21.7em; height:auto; margin:0 auto;"><section class="change" change="background" style="float: right; display: inline; border-radius: 3px; margin-left: -3.8em; width: 19em; line-height: 3em; height: 3em; margin-top: 3.6em; border: 2px solid rgb(77, 60, 38); background: rgb(61, 167, 66);"><section style="color: #fff;font-size:1.4em; padding: 0 0.7em 0 1.5em; ">开心六一 快乐做主</section></section><section style="float: left;width:5em;height:7.1666em;"><img style="width:5em;height:7.1666em;" src="'+ WeixinEdit.imgurl +'festival/14.png"></section></section></section><p style="clear: both;"></p>',
          
          '<section><section style="width:21.7em; height:auto; margin:0 auto;"><section class="change" change="background" style="float: right; display: inline; border-radius: 3px; margin-left: -3.8em; width: 19em; line-height: 3em; height: 3em; margin-top: 3.6em; border: 2px solid rgb(77, 60, 38); background: rgb(61, 167, 66);"><section style="color: #fff;font-size:1.4em; padding: 0 0.7em 0 1.5em; ">开心六一 快乐做主</section></section><section style="float: left;width:5em;height:7.1666em;"><img style="width:5em;height:7.1666em;" src="'+ WeixinEdit.imgurl +'festival/15.png"></section></section></section><p style="clear: both;"></p>',
          
          '<section><section style="display: inline-block; width: 100%; vertical-align: top; background-image: url('+ WeixinEdit.imgurl +'festival/16.jpg); background-size: cover; background-position: 50% 50%; background-repeat: repeat;"><section style="margin: 10px; vertical-align: top;"><section style="padding: 10px; border: 2px dashed rgb(255, 255, 255); border-radius: 0.7em; background-color: rgba(228, 170, 152, 0.780392);"><section style="text-align: center; color: rgb(255, 255, 255); font-size: 28px; line-height: 1.3;">母亲节快乐</section><section style="margin-top: 0.5em; margin-bottom: 0.5em; position: static;"><section style="border-top-width: 1px; border-top-style: dashed; border-top-color: rgb(255, 255, 255);"></section></section><section style="text-align: center; color: rgb(255, 255, 255);"><section>2016.07.14</section></section></section></section></section></section>',
          
          '<section><p style="text-align: center;"><img style="width:120px !important; height:auto !important"  src="'+ WeixinEdit.imgurl +'festival/17.png"></p><p style="text-align: center;"><strong><span style="font-size: 18px; color: #61312A;">Happy Mother`s Day</span></strong></p></section>',
          
          '<section><section style="margin:auto;text-align:;"><img style="display: inline-block; width: 200px !important; height: auto !important;" src="'+ WeixinEdit.imgurl +'festival/18.png"></section><section style="margin-top:-22px;"><section style="border: 1px solid rgb(216, 40, 33); box-shadow: rgb(220, 220, 220) 0px 0px 4px; border-radius: 6px; padding: 20px;"><p style="line-height: 1.75em; font-size: 12px; color: inherit;">劳动的快乐说不尽，劳动的创造最光荣，劳动的创造最光荣。——《劳动最光荣》</p></section></section></section>',
          
          '<p><img style="max-width:100%; display:block; margin:0 auto;" src="'+ WeixinEdit.imgurl +'festival/19.gif"></p>',
          
          '<section><section style="text-align:center;"><section style="display:inline-block;margin-right:-1.6em;"><img style="margin:0px; width:100px !important;" src="'+ WeixinEdit.imgurl +'festival/20.png"></section><section style="display:inline-block;vertical-align:top;margin-top:0.6em;"><section style="margin: 0.2em 0px 0px 2em; padding: 0px 1em 5px 0px; color: rgb(239, 38, 38); border-bottom-width: 2px; border-bottom-style: solid; border-color: rgb(239, 38, 38);"><p style="line-height:1.1em;font-size:14px;text-align:left;">女神节</p><p style="line-height:1.1em;font-size:14px;text-align:left;"><em>LADY`S DAY</em></p></section><section style="margin: 5px 0em; color: rgb(239, 38, 38);"><span style="font-size:22px;"><strong>遇见最美的你</strong></span></section></section></section><section style="width: 0px; height: 0px; clear: both;"></section></section>',
          
          '<section><p style="text-align: center; white-space: normal;"><img src="'+ WeixinEdit.imgurl +'festival/21.png"></p><section style="margin-top:-4.5em;text-align:center;"><span style="font-size: 36px; color: #C00000;">元旦快乐</span></section></section>',
        ]
      ],
      
      /*code:[
        'code',
        '弹幕',
        [
          '<section><section style="margin-top: 0.5em; margin-bottom: 0.5em; box-sizing: border-box; font-size: 16px;"><section style="width:360px; margin:0 auto;"><svg width="360" height="32" xmlns="http://www.w3.org/2000/svg" style="box-sizing: border-box;"><text width="300" font-size="18" y="24" x="69.6383" style="box-sizing: border-box;"><tspan style="box-sizing: border-box;">输入内容</tspan><animate attributeName="x" values="30;180;30" dur="4s" repeatCount="indefinite"></animate></text></svg></section><section style="clear: both;"></section></section><p><br></p></section>',
          
          '<section><section style="margin-top: 0.5em; margin-bottom: 0.5em;box-sizing: border-box;"><svg style="display: block;margin: 5px auto; width:400px; height:200px" viewBox="0 0 500 300"><path id="myPath" fill="none" stroke="#000000" stroke-miterlimit="10" d="M91.4,104.2c3.2-3.4,18.4-0.6,23.4-0.6c5.7,0.1,10.8,0.9,16.3,2.3c13.5,3.5,26.1,9.6,38.5,16.2c12.3,6.5,21.3,16.8,31.9,25.4c10.8,8.7,21,18.3,31.7,26.9c9.3,7.4,20.9,11.5,31.4,16.7c13.7,6.8,26.8,9.7,41.8,9c21.4-1,40.8-3.7,61.3-10.4c10.9-3.5,18.9-11.3,28.5-17.8c5.4-3.7,10.4-6.7,14.8-11.5c1.9-2.1,3.7-5.5,6.5-6.5"></path><text style="fill: deepPink;font-size: 2em;"><textPath xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#myPath" startOffset="82.7498%"><tspan>文字溜溜板素材</tspan><animate attributeName="startOffset" from="0%" to="100%" begin="0s" dur="5s" repeatCount="indefinite" keyTimes="0;1" calcMode="spline" keySplines="0.1 0.2 .22 1"></animate></textPath></text></svg><section style="clear: both;"></section></section><p><br></p></section>',
        ]
      ],*/
    }
    
    //初始化
   var html='<div class="weixin-kingedit"><div class="c"><div class="c-2 editor"><textarea id="WeixinEdit" class="j-noresize"></textarea><a href="javascript:;" class="weixin-kingedit-okbtn">确定</a></div><div class="c-2 maker"><div class="main-content"><div class="left-content"></div><div class="right-menu"><div class="color-msg">调色板</div><div><input type="text" class="jscolor"></div><a class="close-btn">退出</a></div></div></div></div></div>';
    //alert(WeixinEdit.data["care"][0]);
    $("body").append(html);
    
    //添加菜单和内容
    $.each(WeixinEdit.data,function(name, data){
      $(".weixin-kingedit .right-menu").append('<a data-type="'+ name +'">'+ data[1] +'</a>');
      $(".weixin-kingedit .left-content").append('<div class="list-container '+ name +'"></div>')
      $.each(data[2],function(index,v){
        $(".weixin-kingedit ."+name).append('<div class="item" data-type="'+ name +'" data-id="'+ index +'"><p>'+ v +'</p></div>');
      });
    });
    
    //编辑器
    WeixinEdit.editor = KindEditor.create('#WeixinEdit', {
      allowFileManager : true,
      langType: 'zh-CN',
      resizeType:1,
      width:"100%",
      height:500,
      filterMode : false,
	  wellFormatMode:false,
      items:[
          'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
          'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
          'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
          'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
          'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
          'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
          'flash', 'media', 'txvideo', '|', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
          'anchor', 'link', 'unlink'
          ],
    });
    
    //菜单和内容事件绑定
    $(".weixin-kingedit .left-content .item").on("click",function(){
      $(this).parent().find(".selected").removeClass("selected");
      $(this).addClass("selected");
      
      var type=$(this).attr("data-type");
      var id=parseInt($(this).attr("data-id"));
      
      var html=$(this).html()+'<br>';
      WeixinEdit.editor.insertHtml(html);
    });
    
    $(".weixin-kingedit .right-menu a[data-type]").on("click",function(){
      var type=$(this).attr("data-type");
      $(this).parent().find(".on").removeClass("on");
      $(this).addClass("on");
      
      $(".weixin-kingedit .left-content .list-container").hide();
      $(".weixin-kingedit .left-content ."+type).show();
      
    });
    
     $(".weixin-kingedit .right-menu .close-btn,.weixin-kingedit-okbtn").on("click",function(){
      Editor.html(WeixinEdit.editor.html());
      $(".weixin-kingedit").hide();
    });
    
    $(".weixin-kingedit .jscolor").on("change",function(){
      var color='#'+$(this).val();
      $(".weixin-kingedit .change").each(function(){
        var styles=$(this).attr("change").split(',');
        for(var i=0;i<=styles.length-1;i++){
          $(this).css(styles[i],color);
        }
      });
    });
    
    $(".weixin-kingedit .left-content .list-container").eq(0).show();
    $(".weixin-kingedit .right-menu a[data-type='care']").addClass("on");
  }
  
  if(WeixinEdit._inited!=1){
    WeixinEdit._inited=1;
  }else{
    WeixinEdit.editor.html(Editor.html());
    $(".weixin-kingedit").show();
  }
}
WeixinEdit();