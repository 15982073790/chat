/**
 * Created by chenrui on 2017/7/10.
 */



var e={'羊驼':'emo_01','神马':'emo_02','浮云':'emo_03','给力':'emo_04','围观':'emo_05','威武':'emo_06','熊猫':'emo_07','兔子':'emo_08','奥特曼':'emo_09','囧':'emo_10','互粉':'emo_11','礼物':'emo_12','微笑':'emo_13','嘻嘻':'emo_14','哈哈':'emo_15','可爱':'emo_16','可怜':'emo_17','抠鼻':'emo_18','吃惊':'emo_19','害羞':'emo_20','调皮':'emo_21','闭嘴':'emo_22','鄙视':'emo_23','爱你':'emo_24','流泪':'emo_25','偷笑':'emo_26','亲亲':'emo_27','生病':'emo_28','太开心':'emo_29','白眼':'emo_30','右哼哼':'emo_31','左哼哼':'emo_32','嘘':'emo_33','衰':'emo_34','委屈':'emo_35','呕吐':'emo_36','打哈欠':'emo_37','抱抱':'emo_38','怒':'emo_39','问号':'emo_40','馋':'emo_41','拜拜':'emo_42','思考':'emo_43','汗':'emo_44','打呼':'emo_45','睡':'emo_46','钱':'emo_47','失望':'emo_48','酷':'emo_49','好色':'emo_50','生气':'emo_51','鼓掌':'emo_52','晕':'emo_53','悲伤':'emo_54','抓狂':'emo_55','黑线':'emo_56','阴险':'emo_57','怒骂':'emo_58','心':'emo_59','伤心':'emo_60'};

var faceon = function () {
    $(".wl_faces_main").empty();
    var str =""
    str +='<ul>';
    str +='<li><img title="羊驼" src="/upload/emoji/emo_01.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="神马" src="/upload/emoji/emo_02.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="浮云" src="/upload/emoji/emo_03.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="给力" src="/upload/emoji/emo_04.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="围观" src="/upload/emoji/emo_05.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="威武" src="/upload/emoji/emo_06.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="熊猫" src="/upload/emoji/emo_07.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="兔子" src="/upload/emoji/emo_08.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="奥特曼" src="/upload/emoji/emo_09.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="囧" src="/upload/emoji/emo_10.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="互粉" src="/upload/emoji/emo_11.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="礼物" src="/upload/emoji/emo_12.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="微笑" src="/upload/emoji/emo_13.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="嘻嘻" src="/upload/emoji/emo_14.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="哈哈" src="/upload/emoji/emo_15.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="可爱" src="/upload/emoji/emo_16.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="可怜" src="/upload/emoji/emo_17.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="抠鼻" src="/upload/emoji/emo_18.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="吃惊" src="/upload/emoji/emo_19.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="害羞" src="/upload/emoji/emo_20.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="调皮" src="/upload/emoji/emo_21.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="闭嘴" src="/upload/emoji/emo_22.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="鄙视" src="/upload/emoji/emo_23.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="爱你" src="/upload/emoji/emo_24.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="流泪" src="/upload/emoji/emo_25.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="偷笑" src="/upload/emoji/emo_26.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="亲亲 src="/upload/emoji/emo_27.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="生病" src="/upload/emoji/emo_28.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="太开心" src="/upload/emoji/emo_29.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="白眼" src="/upload/emoji/emo_30.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="右哼哼" src="/upload/emoji/emo_31.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="左哼哼" src="/upload/emoji/emo_32.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="嘘" src="/upload/emoji/emo_33.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="衰" src="/upload/emoji/emo_34.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="委屈" src="/upload/emoji/emo_35.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="呕吐" src="/upload/emoji/emo_36.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="打哈欠" src="/upload/emoji/emo_37.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="抱抱" src="/upload/emoji/emo_38.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="怒" src="/upload/emoji/emo_39.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="问号" src="/upload/emoji/emo_40.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="馋" src="/upload/emoji/emo_41.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="拜拜" src="/upload/emoji/emo_42.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="思考" src="/upload/emoji/emo_43.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="汗" src="/upload/emoji/emo_44.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="打呼" src="/upload/emoji/emo_45.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="睡" src="/upload/emoji/emo_46.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="钱" src="/upload/emoji/emo_47.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="失望" src="/upload/emoji/emo_48.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="酷" src="/upload/emoji/emo_49.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="好色" src="/upload/emoji/emo_50.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="生气" src="/upload/emoji/emo_51.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="鼓掌" src="/upload/emoji/emo_52.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="晕" src="/upload/emoji/emo_53.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="悲伤" src="/upload/emoji/emo_54.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="抓狂" src="/upload/emoji/emo_55.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="黑线" src="/upload/emoji/emo_56.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="阴险" src="/upload/emoji/emo_57.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="怒骂" src="/upload/emoji/emo_58.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="心" src="/upload/emoji/emo_59.gif" onclick="emoj(this)"/></li>';
    str +='<li><img title="伤心" src="/upload/emoji/emo_60.gif" onclick="emoj(this)"/></li>';
    str +="</ul>";
    $(".wl_faces_main").append(str);
    $(".tool_box").toggle();
}

var emoj=function (obj) {
    
    var a=  $(obj).attr("title");
    var str=$("#text_in").val();
    var reg = new RegExp( '<' , "g" );
        str =str.replace(reg,'&lt;');

    var reg2 = new RegExp( '>' , "g" );     
        str =str.replace(reg2,'&gt;'); 
    var b = "";
    b += str+" face["+a+"]";
    $("#text_in").val(b);
    $(".tool_box").hide();

}

function put() {

    var value = $('input[name="upload"]').val();
    var index1=value.lastIndexOf(".");
    var index2=value.length;
    var suffix=value.substring(index1+1,index2);
    var debugs =suffix.toLowerCase();

    if (debugs == "jpg" || debugs == "gif" ||debugs == "png" ||debugs == "jpeg") {

        $("#picture").ajaxSubmit({
            url:YMWL_ROOT_URL+'/admin/event/upload',
            type: "post",
            dataType:'json',
            data:{visiter_id:visiter_id,business_id: business_id, avatar: pic,record: record,service_id:service_id},
            success: function (res) {
                if(res.code == 0){

                    var msg =res.data;
                    var time;

                    if($.cookie("itime") == ""){
                        var myDate = new Date();
                            time = myDate.getHours()+":"+myDate.getMinutes();
                        var timestamp = Date.parse(new Date());
                        $.cookie("itime",timestamp/1000);
                    
                    }else{

                        var timestamp = Date.parse(new Date());
                        var lasttime =$.cookie("itime");
                        if((timestamp/1000 - lasttime) >30){
                            var myDate =new Date(timestamp);
                            time = myDate.getHours()+":"+myDate.getMinutes();
                        }else{
                            time ="";
                        }

                        $.cookie("itime",timestamp/1000);
                    }

                    var str = '';
                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                    str += '<div class="" style="position: absolute;top: 29px;right:6px;"><img  class="my-circle cu_pic" src="' + pic + '" ></div>';
                    str += "<div class='outer-right' style='right:16%'><div class='customer'>";
                    str += "<pre>" + msg + "</pre>";
                    str += "</div></div>";
                    str += "</li>";

                    $(".conversation").append(str);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                }else{
                    layer.msg(res.msg,{icon:2});
                }

            }
        });

    } else {

        layer.msg("请选择图片", {icon: 2});
    }
}

// 文件上传
function putfile() {

    var value = $('input[name="folder"]').val();
    var sarr = value.split('\\');
    var name = sarr[sarr.length - 1];

    var arr = value.split(".");
    var debugs =arr[1].toLowerCase();
    if ( debugs == "js" ||  debugs == "css" ||  debugs == "html" ||  debugs == "php") {
        layer.msg("不支持该格式的文件", {icon: 2});
    } else {
        var myDate = new Date();
        var time =  myDate.getHours()+":"+myDate.getMinutes();
      
        $("#file").ajaxSubmit({
            url:YMWL_ROOT_URL+'/admin/event/uploadfile',
            type: 'post',
            dataType:'json',
            data:{visiter_id:visiter_id,business_id: business_id, avatar: pic,record: record,service_id:service_id},
            success: function (res) {
                if(res.code == 0){
                    var str = '';
                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                    str += '<div class="" style="float: right;"><img  class="my-circle cu_pic" src="' + pic + '" ></div>';
                    str += "<div class='outer-right' style='right: 15%;'><div class='customer'>";
                    str += "<pre>";
                    str += "<div><a href='" + res.data + "' style='display: inline-block;text-align: center;min-width: 70px;text-decoration: none;' download='" + name + "'><i class='layui-icon' style='font-size: 60px;'>&#xe61e;</i><br>" + name + "</a></div>";
                    str += "</pre>";
                    str += "</div></div>";
                    str += "</li>";

                    $(".conversation").append(str);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                    var msg = "<div><a href='" + res.data + "' style='display: inline-block;text-align: center;min-width: 70px;text-decoration: none;' download='" + name + "'><i class='layui-icon' style='font-size: 60px;'>&#xe61e;</i><br>" + name + "</a></div>";
                    var se = $('#services').text();
                    if(se){
                        var sid =$.cookie('services');
                    }

                  
                }else{
                    layer.msg(res.msg,{icon:2});
                }

            }
        });

    }
}


function getbig(obj) {
    var text = $(obj).attr('src');
    layer.open({
        type: 1,
        title: false,
        closeBtn: 0,
        area: ['300px','300px'],
        skin: 'layui-layer-nobg', //没有背景色
        shadeClose: true,
        content: "<img src='" + text + "' style='width:100%;height:100%'>"
    });

}

function getdata(){

    var showtime="";
     if($.cookie("services")){
         var se = $.cookie("services");
     }else{
        var se =0;
     }
   
    var curentdata =new Date();
    var time =curentdata.toLocaleDateString();
    var cmin =curentdata.getMinutes();

        if($.cookie("cid") != "" ){
            var cid =$.cookie("cid");
        }else{
            var cid ="";
        }

        $.ajax({
            url:YMWL_ROOT_URL+"/admin/event/chatdata",
            type: "post",
            data: {hid:cid,vid:visiter_id,business_id:business_id,service_id:se},
            dataType:'json',
            success: function (res) {
                //添加 最近的 聊天 记录
                if(res.code == 0){
                var str = '';
               
                $.each(res.data, function (k, v) {
                    if(getdata.puttime){

                        if((v.timestamp -getdata.puttime) > 60){
                            var myDate = new Date(v.timestamp*1000);
                            var puttime =myDate.toLocaleDateString();
                            if(puttime == time){
                                showtime =myDate.getHours()+":"+myDate.getMinutes();

                            }else{
                                showtime =myDate.getFullYear()+"-"+(myDate.getMonth()+1)+"-"+myDate.getDate()+" "+myDate.getHours()+":"+myDate.getMinutes();
                            }

                        }else{
                            showtime="";
                        }

                    }else{

                        var myDate = new Date(v.timestamp*1000);
                        var puttime =myDate.toLocaleDateString();
                        if(puttime == time){
                            showtime =myDate.getHours()+":"+myDate.getMinutes();
                        }else{
                            showtime =myDate.getFullYear()+"-"+(myDate.getMonth()+1)+"-"+myDate.getDate()+" "+myDate.getHours()+":"+myDate.getMinutes();
                        }
                    }
                    getdata.puttime = v.timestamp;

                    if (v.direction == 'to_service') {

                        str += '<li class="chatmsg"><div class="showtime">' + showtime + '</div>';
                        str += '<div class="" style="position: absolute;top: 29px;right: 6px;"><img class="my-circle" src="' + v.avatar + '" ></div>';
                        str += "<div class='outer-right' style='right:16%;'><div class='customer'>";
                        str += "<pre>" + v.content + "</pre>";
                        str += "</div></div>";
                        str += "</li>";

                    } else {
                            str += '<li class="chatmsg" id="xiaox_'+v.cid+'"><div class="showtime">' + showtime + '</div><div style="position: absolute;left:8px">';
                            str += '<img  class="my-circle  se_pic" src="' + v.avatar + '" ></div>';
                            str += "<div class='outer-left' style='right:4%;'><div class='service'>";
                            str += "<pre>" + v.content + "</pre>";
                            str += "</div></div>";
                            str += "</li>";
                        
                    }
                });

                var div = document.getElementById("wrap");
                if($.cookie("cid") == ""){

                    $(".conversation").append(str);

                    if(div){
                        div.scrollTop = div.scrollHeight;
                    }
                }else{
                    $(".conversation").prepend(str);
                    if(res.data.length <= 2){
                        $("#top_div").remove();
                        $(".conversation").prepend("<div id='top_div' class='showtime'>已没有数据</div>");
                        if(div){
                            div.scrollTop =0;
                        }
                    }else {
                        if(div){
                            div.scrollTop =div.scrollHeight/3;
                        }
                    }
                }

                if(res.data.length >2){
                   
                    $.cookie("cid",res.data[0]['cid']);
                }

                
              }
            }
        });
}
// 通知 客服
var init = function () {
  $.cookie("cid",'');
  wolive_connect();
  getdata();
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/event/notice",
        type: 'post',
        data: {visiter_id:visiter_id, visiter_name: visiter, business_id: business_id, from_url: record, avatar: pic,groupid:gid},
        success: function (res) {
            if(res.code == 0){
                data =res.data;
                $("#img_head").attr('src',data.avatar);
                $("#services").text(data.nick_name).attr('data',data.service_id);
                console.log('触发postMessage发送问候语');
                window.parent.postMessage({type:'greeting',data:data},'*');
                if(data.state == 'online'){
                    $("#img_head").removeClass("icon_gray");
                }else{
                    $("#img_head").addClass("icon_gray");
                }
                // 问候语
                 msg = '';
                    msg += '<li class="chatmsg" style="margin-bottom: 45px;"><div style="position: absolute;top:16px;left:10px;">';
                    msg += '<img  class="my-circle" src="' + data.avatar + '" ></div>';
                    msg += "<div class='outer-left' style='right:4%;top:13px;'><div class='service'>";
                    msg += "<pre>" + data.content + "</pre>";
                    msg += "</div></div>";
                    msg += "</li>";
                    $(".conversation").append(msg);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                    $.cookie("services",data.service_id);
                service_id =data.service_id;
            }else if(res.code == 1){
                layer.msg(res.msg,{icon:2});
            }else if(res.code == 2){
                $("#img_head").attr("src","/assets/images/index/workerman_logo.png");
                $("#services").text("");
                var num= getnums(business_id);
                // 告知客服在排队
                var msg='';
                msg+='<li class="chatmsg_notice"><div style="position: absolute;left:3px;top:17px">';
                msg+='<img  class="my-circle" src="'+YMWL_ROOT_URL+'/assets/images/index/workerman_logo.png" ></div>';
                msg+="<div class='outer-left' style='right:4%;top:13px;'><div class='service'>";
                msg+="<pre>通知 ： 现在还有"+num+" 人在排队，请等待 ....</pre>";
                msg+="</div></div>";
                msg+="</li>";
                $(".conversation").append(msg);
                 $.cookie("services",'');
            }else if(res.code == 3){
                layer.msg(res.msg,{icon:2,end:function(){
                      window.location.href = url + "/index/message?business_id=" + business_id;
                }});
                $.cookie("services",'');
            }else if(res.code == 4){
                var data =res.data;
                $("#services").text(data.nick_name);
                $("#img_head").attr('src',data.avatar).addClass("icon_gray");
                layer.open({
                    title:'提示框',
                    area: ['300px', '180px'],
                    content:'该客服离线中，是否转接其他客服？',
                    btn:['是','否'],
                    yes:function(){
                        $.ajax({
                            url:YMWL_ROOT_URL+'/admin/event/getchangekefu',
                            type:'post',
                            data:{visiter_id:visiter_id,business_id:business_id},
                            success:function(res){
                                if(res.code == 0){

                                 layer.msg('转接中....',{icon:3,end:function(){
                                    location.reload();
                                 }});
                              }
                           }
                        });
                    },
                    btn2:function(){
                        layer.close();
                    }
                });
            }
        }
    });


    getquestion(business_id);


    $.cookie("itime","");

}

window.onload = init();

function getquestion(business_id){
     $.ajax({
       url:YMWL_ROOT_URL+'/admin/event/getquestion',
       type:'post',
       data:{business_id:business_id},
       success:function(res){
          if(res.code == 0){
             var str='';
                str += '<li class="chatmsg"><div style="position: absolute;left:3px;top:17px">';
                str += '<img  class="my-circle" src="/assets/images/index/workerman_logo.png" ></div>';
                str += "<div class='outer-left' style='right:4%;top:13px;'><div class='service'>";
                str += "<pre>" ;

                str+='<p style="font-weight:blod;">我猜你想问：</p>'
              var num ='';
              $.each(res.data,function(k,v){
                  var a = JSON.stringify(v);
                  if(num){
                    num =num+1;
                  }else{
                    num =1
                  }
                
                  str+='<div class="question" onclick="getanswer('+v.qid+')"><span>'+num+'. </span> '+v.question+'</div>';                 
              });

                str += "</pre></div></div>";
                str += "</li>";

              $(".conversation").append(str);
              var div = document.getElementById("wrap");
              div.scrollTop = div.scrollHeight;
          }
       }
    });
}


function getanswer(id){

        $.ajax({
            url:YMWL_ROOT_URL+'/admin/event/getanswer',
            type:'post',
            data:{qid:id},
            success:function(res){

                if(res.code == 0){
                    var str = '';
                        str += '<li class="chatmsg">';
                        str += '<div  style="position: absolute;top: 29px;right: 6px;"><img  class="my-circle" src="' + pic + '" ></div>';
                        str += "<div class='outer-right' style='right:16%;top:24px;'><div class='customer'>";
                        str += "<pre>" + res.data.question + "</pre>";
                        str += "</div></div>";
                        str += "</li>";
                        $(".conversation").append(str);

                    var msg = '';
                        msg += '<li class="chatmsg"></div><div style="position: absolute;left:3px;top:17px">';
                        msg += '<img  class="my-circle se_pic" src="/assets/images/index/workerman_logo.png" ></div>';
                        msg += "<div class='outer-left'  style='right:4%;top:13px;'><div class='service'>";
                        msg += "<pre><div class='markdown-body ' style='width:' >" +res.data.answer+ "</div></pre>";
                        msg += "</div></div>";
                        msg += "</li>";
                        $(".conversation").append(msg);
                        var div = document.getElementById("wrap");
                        div.scrollTop = div.scrollHeight;
                 }
            }
        });
}



// 获取排队的数量
function getnums(id){
    var value ="";
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/event/getwaitnum",
        type:"post",
        async: false,
        data:{business_id:id,groupid:gid},
        success:function(res){
            value =res;
        }
    });
    return value;
}
// 发送消息

var send = function () {
    //获取 游客id
    var msg = $("#text_in").val();
    var reg = new RegExp( '<' , "g" )
    var msg2 =msg.replace(reg,'&lt;');

    var reg2 = new RegExp( '>' , "g" )     
        msg2 =msg2.replace(reg2,'&gt;'); 

    if(msg2.indexOf("face[")!=-1){

       msg2=msg2.replace(/face\[([^\s\[\]]+?)\]/g,function (i) {
         var a = i.replace(/^face/g, "");
             a=a.replace('[','');
             a=a.replace(']','');  
         return '<img src="/upload/emoji/'+e[a]+'.gif"/>'
      });

    }
    
  
    if (msg == '' || $.cookie("service") == '') {
        layer.msg('请输入信息');
    } else {
        var time;

        if($.cookie("itime") == ""){
            var myDate = new Date();
                time = myDate.getHours()+":"+myDate.getMinutes();
            var timestamp = Date.parse(new Date());
            $.cookie("itime",timestamp/1000);
        
        }else{

            var timestamp = Date.parse(new Date());
            var lasttime =$.cookie("itime");
            if((timestamp/1000 - lasttime) >30){
                var myDate =new Date(timestamp);
                time = myDate.getHours()+":"+myDate.getMinutes();
            }else{
                time ="";
            }

            $.cookie("itime",timestamp/1000);
        }

        var str = '';
        str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
        str += '<div style="position: absolute;top: 29px;right: 6px;"><img  class="my-circle cu_pic" src="' + pic + '" ></div>';
        str += "<div class='outer-right' style='right:16%;'><div class='customer'>";
        str += "<pre>" + msg2 + "</pre>";
        str += "</div></div>";
        str += "</li>";

        $(".conversation").append(str);
        $("#text_in").val('');
        var div = document.getElementById("wrap");
        div.scrollTop = div.scrollHeight;

        $.ajax({
            url:YMWL_ROOT_URL+"/admin/event/chat",
            type: "post",
            data: {visiter_id:visiter_id,content: msg2,business_id: business_id, avatar: pic,record: record,service_id:service_id},
            dataType:'json',
            success:function(res){
                if(res.code != 0){
                    layer.msg(res.msg,{icon:2});
                }
            }
        });
    }
}