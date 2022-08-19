
/**
 * Created by chenrui on 2017/7/10.
 */


var e={'羊驼':'emo_01','神马':'emo_02','浮云':'emo_03','给力':'emo_04','围观':'emo_05','威武':'emo_06','熊猫':'emo_07','兔子':'emo_08','奥特曼':'emo_09','囧':'emo_10','互粉':'emo_11','礼物':'emo_12','微笑':'emo_13','嘻嘻':'emo_14','哈哈':'emo_15','可爱':'emo_16','可怜':'emo_17','抠鼻':'emo_18','吃惊':'emo_19','害羞':'emo_20','调皮':'emo_21','闭嘴':'emo_22','鄙视':'emo_23','爱你':'emo_24','流泪':'emo_25','偷笑':'emo_26','亲亲':'emo_27','生病':'emo_28','太开心':'emo_29','白眼':'emo_30','右哼哼':'emo_31','左哼哼':'emo_32','嘘':'emo_33','衰':'emo_34','委屈':'emo_35','呕吐':'emo_36','打哈欠':'emo_37','抱抱':'emo_38','怒':'emo_39','问号':'emo_40','馋':'emo_41','拜拜':'emo_42','思考':'emo_43','汗':'emo_44','打呼':'emo_45','睡':'emo_46','钱':'emo_47','失望':'emo_48','酷':'emo_49','好色':'emo_50','生气':'emo_51','鼓掌':'emo_52','晕':'emo_53','悲伤':'emo_54','抓狂':'emo_55','黑线':'emo_56','阴险':'emo_57','怒骂':'emo_58','心':'emo_59','伤心':'emo_60'};

var types=function(){
    if($.cookie('type') == 1){
     //快捷键
document.getElementById("text_in").onkeydown = function (e) {
    e = e || window.event;

     if (e.ctrlKey && e.keyCode == 13) {
        $("#text_in").append("<div><br/></div>");
        var o = document.getElementById("text_in").lastChild;            
        var textbox = document.getElementById('text_in');
        var sel = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(textbox);
        range.collapse(false);
        if(o){
         range.setEndAfter(o);//
         range.setStartAfter(o);// 
        }
     
        sel.removeAllRanges();
        sel.addRange(range);

     }        

    if(!e.ctrlKey && e.keyCode == 13){
      var a=$('#text_in').val();
   
      var str=a.replace(/(^\s*)|(\s*$)/g,"");
      if(!str){  
        layer.msg('内容不能为空',{icon:3});
        $('#text_in').html('');
        return false;
      }

       send();
       e.returnValue = false;
       return false;
    }
};

}else{

    document.getElementById("text_in").onkeydown = function (e) {
    e = e || window.event;
    if (e.ctrlKey && e.keyCode == 13) {
        if ($('#text_in').html() == "" || $.cookie("service") == '' ) {
            layer.msg('请输入信息');
        } else {
            send();
        }
    }
 }
  
}

}


// 默认加载

var chaton = function () {
    var height =document.body.clientHeight;
    $("#chat_list").css("height",(height -110)+"px");
    $("#wait_list").css("height",(height-110)+"px");
    //判断当前有无排队人员
    getwait();
    getblacklist();
    $.cookie("hid","");
    var sdata = $.cookie("cu_com");
   

    if (sdata) {
        var jsondata = $.parseJSON(sdata);
        var chas = jsondata.channel;
        var cip = jsondata.ip;
        $("#customer").text(jsondata.visiter_name);
        var record =jsondata.from_url;
        if(record.search('http') != -1){
             var str="<a href='"+record+"' target='_blank'>"+record+"</a>";
         }else{
            var str=record
         }
       
        $(".record").html(str);
        $("#channel").text(jsondata.visiter_id);
        getstatus(chas);
        if ($.cookie(chas)){
            var str = $.cookie(chas);
            $(".iparea").text(str);
        } else {
            getip(cip, chas)
        }
        getdata(jsondata.visiter_id);
    } else {

        $("#channel").text(" ");
        $(".record").text(" ");
        $(".iparea").text(" ");
        $(".chatmsg").remove();
        $(".chatbox").addClass('hide');
        $(".no_chats").removeClass('hide');

    }


    types();
};
window.onload = chaton();

// 选择对象

function choose(vid) {
    var sdata = $.cookie("cu_com");

    if(sdata){
        var jsondata = $.parseJSON(sdata);
        var chas = jsondata.visiter_id;
    }else{
        var chas ="";
    }

 
   var data =chat_data['visiter'+vid];
   

    if(chas != data.visiter_id){
        
        $("#c"+data.visiter_id).addClass('hide');
        $.cookie("cu_com", JSON.stringify(data));
        $(".conversation").empty();
        chaton();
        $("#v"+data.visiter_id).addClass("onclick");
        $("#v"+data.visiter_id).siblings("div").removeClass("onclick");
        $(".chatbox").removeClass('hide');
        $(".no_chats").addClass('hide');
        getwatch(data.visiter_id);
        getnews(data.visiter_id);
        
    }
}

//拖到黑名单
function getblack() {
    var data = $.cookie("cu_com");
    var vid;
    if (data) {
        var jsondata = $.parseJSON(data);
        vid = jsondata.visiter_id
    }
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/blacklist",
        type: "post",
        data: {
            visiter_id: vid
        },
        success: function (res) {
            
            if (res.code == 0) {
                $.cookie("cu_com", "");
            }

            layer.msg("已拖入黑名单", {offset: "20px"});
            getchat();
            getblacklist();
        }
    });
}


//发送消息
var send = function () {
    //获取 游客id
    var msg = $("#text_in").val();


    var reg = new RegExp( '<' , "g" )
        msg =msg.replace(reg,'&lt;');

    var reg2 = new RegExp( '>' , "g" )     
        msg =msg.replace(reg2,'&gt;'); 

    if(msg.indexOf("face[")!=-1){

       msg=msg.replace(/face\[([^\s\[\]]+?)\]/g,function (i) {
         var a = i.replace(/^face/g, "");
             a=a.replace('[','');
             a=a.replace(']','');  
         return '<img src="/upload/emoji/'+e[a]+'.gif"/>'
      });

    }
    
    var sdata = $.cookie('cu_com');
    if (sdata) {
        var json = $.parseJSON(sdata);
        var img = json.avater;
    }
    if (msg == "") {
        layer.msg('请输入信息');
    } else {
        var sid = $('#channel').text();
        var se = $("#chatmsg_submit").attr('name');
        var customer = $("#customer").text();
        var pic = $("#se_avatar").attr('src');
        var time;

        if($.cookie("time") == ""){
            var myDate = new Date();
                time = myDate.getHours()+":"+myDate.getMinutes();
            var timestamp = Date.parse(new Date());
            $.cookie("time",timestamp/1000);

        }else{

            var timestamp = Date.parse(new Date());

            var lasttime =$.cookie("time");
            if((timestamp/1000 - lasttime) >30){
                var myDate =new Date(timestamp);
                time = myDate.getHours()+":"+myDate.getMinutes();
            }else{
                time ="";
            }

            $.cookie("time",timestamp/1000);

        }
    
        var str = '';
        str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
        str += '<div style="float: right;"><img  class="my-circle se_pic" src="' + pic + '" width="50px" height="50px"></div>';
        str += "<div class='outer-right'><div class='service'>";
        str += "<pre>" + msg + "</pre>";
        str += "</div></div>";
        str += "</li>";

        $(".conversation").append(str);
        $("#text_in").val('');


        var div = document.getElementById("wrap");
        div.scrollTop = div.scrollHeight;

        $.ajax({
            url:YMWL_ROOT_URL+"/admin/set/chats",
            type: "post",
            data: {visiter_id:sid,content: msg, avatar: img}
        });
    }
}

// 认领
function get(id) {
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/get",
        type: "post",
        data: {visiter_id: id},
        success: function (res) {
            layer.msg("认领成功", {offset: "20px"});
            getwait();
            getchat();
        }
    });
}

//表情
var faceon = function () {
    $(".tool_box").toggle();
}

//获取表情图片
$(".wl_faces_main img").click(function () {
    var a = $(this).attr("title");
    var str=$("#text_in").val();
    var reg = new RegExp( '<' , "g" )
        str =str.replace(reg,'&lt;');

    var reg2 = new RegExp( '>' , "g" )     

        str =str.replace(reg2,'&gt;'); 
    var b = "";
    b += str+" face["+a+"]";
    $("#text_in").val(b);
    $(".tool_box").hide()
});


//删除对象

function cut(id) {

    var data = $.cookie("cu_com");
    var visiter_checked;
    if (data) {
        var jsondata = $.parseJSON(data);
        visiter_checked = jsondata.visiter_id;
    }
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/deletes",
        type: "post",
        data: {
            visiter_id: id
        },
        dataType:'json',
        success: function (res) {

          if(res.code == 0){
            if (visiter_checked == id) {
               
                $(".chatbox").addClass('hide');
                $(".no_chats").removeClass('hide');
            }
            // 删除修改
            getblacklist();  
          }      
        }
    });
}

//删除cookie方法
function delCookie(name) {
    var date = new Date();
    date.setTime(date.getTime() - 10000);
    document.cookie = name + "=a; expires=" + date.toGMTString()
};

//文件上传
function putfile() {

    var value = $('input[name="folder"]').val();
    var sarr = value.split('\\');
    var name = sarr[sarr.length - 1];
    var arr = value.split(".");

    if (arr[1] == "js" || arr[1] == "css" || arr[1] == "html" || arr[1] == "php") {
        layer.msg("不支持该格式的文件", {icon: 2});

    } else {

        var myDate = new Date();
        var time =  myDate.getHours()+":"+myDate.getMinutes();
        var pic = $("#se_avatar").attr('src');
        $("#file").ajaxSubmit({
            url:YMWL_ROOT_URL+'/admin/set/uploadfile',
            type: 'post',
            datatype:'json',
            success: function (res) {
                if(res.code == 0){
                    var str = '';
                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                    str += '<div class="" style="float: right;"><img  class="my-circle cu_pic" src="'+pic+'" ></div>';
                    str += "<div class='outer-right'><div class='service'>";
                    str += "<pre><div>";
                    str += "<a href='" + res.data + "' style='display: inline-block;text-align: center;min-width: 70px;text-decoration: none;' download='" + name + "'><i class='layui-icon' style='font-size: 60px;'>&#xe61e;</i><br>" + name + "</a>";
                    str += "</div></pre>";
                    str += "</div></div>";
                    str += "</li>";

                    $(".conversation").append(str);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                    var sdata = $.cookie('cu_com');

                    if (sdata) {
                        var json = $.parseJSON(sdata);
                        var img = json.avater;
                    }

                    var msg = "<div><a href='" + res.data + "' style='display: inline-block;text-align: center;min-width: 70px;text-decoration: none;' download='" + name + "'><i class='layui-icon' style='font-size: 60px;'>&#xe61e;</i><br>" + name + "</a></div>";

                    var sid = $('#channel').text();
                    var se = $("#chatmsg_submit").attr('name');
                    var customer = $("#customer").text();
                    $.ajax({
                        url:YMWL_ROOT_URL+"/admin/set/chats",
                        type: "post",
                        data: {visiter_id:sid,content: msg, avatar: img}
                    });
                }else{
                    layer.msg(res.msg,{icon:2});
                }

            }
        });

    }
}


//图片上传

function put() {

    var value = $('input[name="upload"]').val();
    var index1=value.lastIndexOf(".");
    var index2=value.length;
    var suffix=value.substring(index1+1,index2);
    var debugs =suffix.toLowerCase();

    if (debugs == "jpg" || debugs == "gif" ||debugs == "png" ||debugs == "jpeg") {

        $("#picture").ajaxSubmit({
            url:YMWL_ROOT_URL+'/admin/set/upload',
            type: "post",
            dataType:'json',
            success: function (res) {
               if(res.code == 0){
                
                    var sdata = $.cookie('cu_com');
                    if (sdata) {
                        var json = $.parseJSON(sdata);
                        var img = json.avater;
                    }

                    var msg = '<img src="' + res.data +'"  >';
                    var sid = $('#channel').text();
                    var se = $("#chatmsg_submit").attr('name');
                    var customer = $("#customer").text();
                    var pic = $("#se_avatar").attr('src');
                    var time;

                    if($.cookie("time") == ""){
                        var myDate = new Date();
                            time = myDate.getHours()+":"+myDate.getMinutes();
                        var timestamp = Date.parse(new Date());
                        $.cookie("time",timestamp/1000);

                    }else{

                        var timestamp = Date.parse(new Date());

                        var lasttime =$.cookie("time");
                        if((timestamp/1000 - lasttime) >30){
                            var myDate =new Date(timestamp);
                            time = myDate.getHours()+":"+myDate.getMinutes();
                        }else{
                            time ="";
                        }

                        $.cookie("time",timestamp/1000);

                    }
                    var str = '';
                        str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                        str += '<div style="float: right;"><img  class="my-circle se_pic" src="' + pic + '" width="50px" height="50px"></div>';
                        str += "<div class='outer-right'><div class='service'>";
                        str += "<pre>" + msg + "</pre>";
                        str += "</div></div>";
                        str += "</li>";

                    $(".conversation").append(str);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                
                   $.ajax({
                        url:YMWL_ROOT_URL+"/admin/set/chats",
                        type: "post",
                        data: {visiter_id:sid,content: msg, avatar: img},
                        success:function(res){
                             if(res.code != 0){
                                layer.msg(res.msg,{icon:2});
                            }
                        }
                    });
               }else{
                   layer.msg(res.msg,{icon:2});
               }
            }
        });

    } else {

        layer.msg("请选择图片", {icon: 2});
    }
}

//图片放大预览
function getbig(obj) {
    var text = $(obj).attr('src');
    // alert(text);
    layer.open({
        type: 1,
        title: false,
        closeBtn: 0,
        area: '400px',
        skin: 'layui-layer-nobg', //没有背景色
        shadeClose: true,
        content: "<img src='" + text + "' width='100%' height='100%'>"
    });
}