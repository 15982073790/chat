function revoke(id,type) {
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/revokemsg",
        type:"post",
        data:{id:id,type:type},
        dataType:'json',
        success:function (res) {
            if(res.code == 0){
                layer.msg(res.msg,{icon:1,end:function(){
                        $("#xiaox_"+id).remove();
                    }});
            }else{
                layer.msg(res.msg,{icon:2});
            }
        }
    });
}
function getnow(data) {
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getchatnow",
        type:"post",
        data:{sdata:data},
        dataType:'json',
        success:function (res) {

            var a="";
            if(res.code == 0){
               getchat();
            }
        }
    });
}


//储存 频道
var chaarr = new Array();

//初始化 监听
var getonline = function () {
    getchat();
    $.cookie("time","");
    $(".conversation").empty();
};

window.onload = getonline();

// 获取访客状态
function getstatus(visiter_id) {
    $.ajax({
        url:YMWL_ROOT_URL+'/admin/set/getstatus',
        type:'post',
        data:{visiter_id:visiter_id},
        dataType:'json',
        success:function(res){
            if(res.code ==0){
                if(res.data){
                    $("#last_login_time").text(res.data.timestamp);
                    $("#login_times").text(res.data.login_times);
                    $("#name").val(res.data.name);
                    $(".ipdizhi").text(res.data.ip);
                    $("#tel").val(res.data.tel);
                    $("#comment").val(res.data.comment);
                    if(res.data.extends.os!==undefined){
                        $("#login_device").text(res.data.extends.os + ' ' + res.data.extends.browserName);
                    }
                  if(res.data.state == 'online'){
                    $("#v_state").text("在线");
                  }else{
                    $("#v_state").text("离线");
                  }
                    var data = res.data.area;
                  if(data !== ''){
                      var str = "";
                      str += data[0] + " 、";
                      str += data[1] + " 、";
                      str += data[2];
                      $(".iparea").text(str);
                  }
                }
            }
        }
    });
}

// 正在聊天的队列表
function getchat() {
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getchats",
        success: function (res) {
            let url = document.location.toString();
            let visiter_id;
            if(url.indexOf('=') > -1) {
                var arrUrl = url.split("=");
　　　　             visiter_id = arrUrl[1];
                $.ajax({
                    url:YMWL_ROOT_URL+"/admin/custom/opencs",
                    type: 'post',
                    data: {
                        visiter_id: visiter_id,
                    },
                    success: function (res) {
                        
                    }
                })
            }
            if (res.code == 0) {
                $('.clear-btn').show();
                $("#chat_list").empty();
                var sdata = $.cookie('cu_com');
                if (sdata) {
                    var json = $.parseJSON(sdata);
                    var debug = json.visiter_id;
                } else {
                    var debug = "";
                }
                var data = res.data;
                var a = '';
                var uname;
                let name;
                $.each(data, function (k, v) {
                    var str = JSON.stringify(v);
                    chat_data['visiter'+v.vid] =v;
                    v.visiter_name=v.visiter_name?v.visiter_name:'游客'+v.visiter_id;
                    uname=v.name?v.name:v.visiter_name;
                    if((v.name || v.tel) && msgreminder) {
                        name = "<span class='c_name'><span class='c_tag'>已留信息</span><span>" + uname + "</span></span>";
                    }else {
                        name = "<span class='c_name'>" + uname + "</span>";
                    }

                    if (v.state == 'online') {
                        var isicon_gray='';

                    }else{
                        var isicon_gray='icon_gray';
                    }
                    if (debug == v.visiter_id) {
                        $(".chatbox").removeClass('hide');
                        $(".no_chats").addClass('hide');
                        var isclick='onclick';
                    }else{
                        var isclick='';
                    }
                    if(v.count < 1){
                        var ishide='hide';
                    }else {
                        var ishide='';
                    }
                    if (v.count > 99) {
                        v.count = "99+";
                    }
                    b='<i class="layui-icon myicon hide" title="删除" style="font_weight:blod" onclick="cut(' + "'" + v.visiter_id + "'" + ')">&#x1006;</i>';
                    a += '<div id="v' + v.channel + '" class="visiter '+isclick+'" onmouseover="showcut(this)" onmouseout="hidecut(this)">'+b+'<span id="c' +v.channel + '" class="notice-icon '+ishide+'">'+v.count+'</span>';
                    a += "<div class='visit_content' onclick='choose(" +v.vid+ ")'><img class='am-radius v-avatar "+isicon_gray+"' id='img" + v.channel + "' src='" + v.avatar + "'  width='50px'>"+name+"<span class='c_time'>" + v.timestamp + "</span><div id='msg" + v.channel + "' class='newmsg'>"+v.content+"</div></div></div>";

                });
                $("#chat_list").append(a);

            } else {
                $("#chat_list").empty();
                $(".chatbox").addClass('hide');
                $(".no_chats").removeClass('hide');
                $.cookie('cu_com', "");
                $('.clear-btn').hide();
            }
            var count = res.all_unread_count;
            if(count > 0) {
                if(count > 99) {
                    count = '99+'
                }
                if($("#layout-west")[0].offsetWidth == 180) {
                    $(".notices").removeClass('hide')
                }else if($("#layout-west")[0].offsetWidth == 80) {
                    $(".notices-icon").removeClass('hide')
                }
                $(".notices").text(count)
            }else if(count == 0) {
                $(".notices").text('')
                $(".notices").addClass('hide')
                $(".notices-icon").addClass('hide')
            }
        },
        complete:function(){
            choose_lock = false;
        }
    });
}

function showcut(obj){
    $(obj).children('i').removeClass('hide');
}

function hidecut(obj){
    $(obj).children('i').addClass('hide');
}

//获取队列的实时数据
function getwait() {

    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getwait",
        dataType:'json',
        success: function (res) {

            if (res.code == 0) {
              
                $("#wait_list").empty();
                $("#waitnum").addClass('hide');
                if (!res.data.length) {
                    return;
                }
                var a = "";
                $.each(res.data, function (k, v) {
                    v.visiter_name=v.visiter_name?v.visiter_name:'游客'+v.visiter_id;
                    var uname=v.name?v.name:v.visiter_name;
                    if(v.state == "online"){
                        a += '<div class="waiter">';
                        a += '<img id="img'+v.visiter_id+'" class="am-radius w-avatar v-avatar" src="' + v.avatar + '" width="50px" height="50px"><span class="wait_name">' + uname + '</span>';
                        a += "<div class='newmsg'>"+v.groupname+"</div>";
                        a += '<i class="mygeticon " title="认领" onclick="get(' + "'" + v.visiter_id + "'" + ')"></i></div>';
                    }else{
                        a += '<div class="waiter">';
                        a += '<img id="img'+v.visiter_id+'"  class="am-radius w-avatar v-avatar icon_gray"  src="' + v.avatar + '" width="50px" height="50px"><span class="wait_name">' + uname + '</span>';
                        a += "<div class='newmsg'>"+v.groupname+"</div>";
                        a += '<i class="mygeticon " title="认领" onclick="get(' + "'" + v.visiter_id + "'" + ')"></i></div>';
                    }
                });
                $("#wait_list").append(a);

                $("#notices-icon").removeClass('hide');
                $("#waitnum").removeClass('hide');
                $("#waitnum").text(res.num);
                document.title ="【有客户等待】"+myTitle;


            } else {

                document.title =myTitle;
            }
        }
    });

}


//获取黑名单
function getblacklist() {

    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getblackdata",
        dataType:'json',
        success: function (res) {

            if (res.code == 0) {
              
                $("#black_list").empty();
                var data = res.data;
                var a = "";
                $.each(data, function (k, v) {

                    a += '<div class="visiter"><img class="am-radius v-avatar" src="' + v.avatar + '">';
                    a += ' <span style="font-size: 14px;color: #555555;line-height: 80px;margin-left: 82px">' + v.visiter_name + '</span><div style="position:absolute;right:0;top:30px;cursor: pointer;" onclick="recovery(' + "'" + v.visiter_id + "'" + ')"><img src="'+YMWL_ROOT_URL+'/assets/images/admin/B/delete.png"></img></div></div>';
                });

                $("#black_list").append(a);
            } else {

                $("#black_list").empty();
            }
        }
    });
}




//获取ip的详细信息
var getip = function (cip) {
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getipinfo",
        type: "get",
        data: {
            ip: cip
        },
        dataType:'json',
        success: function (res) {

            if(res.code == 0){
                var data = res.data;
                var str = "";
                str += data[0] + " 、";
                str += data[1] + " 、";
                str += data[2];
                $(".iparea").text(str);
                $(".iparea").text(res.data.ip);
            }
           
        }
    })
};

//标记已看消息
function getwatch(cha) {
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getwatch",
        type: "post",
        data: {visiter_id: cha}
    });
}

//获取最近历史消息
function getdata(cha) {

    var avatver;
    var sdata = $.cookie("cu_com");
    if (sdata) {
        var jsondata = $.parseJSON(sdata);
        avatver = jsondata.avatar;
    }
    var showtime;
    var curentdata =new Date();
    var time =curentdata.toLocaleDateString();
    // var cmin =curentdata.getMinutes();
    if($.cookie("hid") != "" ){
        var cid =$.cookie("hid");
    }else{
        var cid ="";
    }
  
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/chatdata",
        type: "post",
        data: {
            visiter_id: cha,hid:cid
        },
        dataType:'json',
        success: function (res) {
            // alert(res);
            if (res.code == 0) {
                getwatch(cha);
                var se = $("#chatmsg_submit").attr("name");
                var str = "";
                var data = res.data;
                var user = res.user;
                var mindata = null
                if(res.data.length >0){
                    mindata = data[0].cid;
                } else {
                    mindata = null;
                }
                var pic = $("#se_avatar").attr('src');
                user.visiter_name=user.visiter_name?user.visiter_name:'游客'+user.visiter_id;
                var uname=user.name?user.name:user.visiter_name;
                str += '<div class="chatbox-name"><div class="chatbox-info">';
                str += '<div style="float:left;width:auto;margin-right:5px">'+uname+'</div>';
                str += '<div class="group-list">';
                str += '<div class="group-list-left">';
                for(let i = 0;i < user.group_name_array.length;i++) {
                    str += '<span class="group-item" style="background-color: '+user.bgcolor_array[i]+'">'+user.group_name_array[i]+'</span>'
                }
                str += '</div><div class="group-list-left"><img src="/assets/style1/img/add.png" alt="" class="editusergroup" data-vid="'+user.vid+'"></div><div class="group-list-right">';
                changetop=user.istop?0:1;
                changetips=user.istop?'取消置顶':'置顶对话';
                btnClass=user.istop?'layui-btn-normal':'layui-btn-danger';

                str +='<button class="layui-btn '+btnClass+' chat2top js-ajax-btn"  onclick="chat2top(\''+user.visiter_id+'\',this)" data-istop="'+changetop+'">'+changetips+'</button>';
                str +='</div></div></div></div>';
                $.each(data, function (k, v) {
                    console.log(v);
                    if (v.cid < mindata) {
                        mindata = v.cid;
                    }

                    if(getdata.puttime){
                        if((v.timestamp -getdata.puttime) > 60){
                            var myDate = new Date(v.timestamp*1000);
                            var puttime =myDate.toLocaleDateString();
                            let year = myDate.getFullYear();
                            let month = myDate.getMonth()+1;
                            let date = myDate.getDate();
                            let hours = myDate.getHours();
                            let minutes = myDate.getMinutes();
                            if(hours < 10 ) {
                                minutes = minutes.toString();
                            }
                            if(minutes < 10 ) {
                                minutes = '0'+minutes.toString();
                            }
                            
                            if(puttime == time){
                                showtime =hours+":"+minutes;
                            }else{
                                showtime =year+"-"+month+"-"+date+" "+hours+":"+minutes;
                            }

                        }else{
                            
                            showtime = "";
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

                    if(v.content.indexOf('target="_blank') > -1) {
                        v.content = v.content.replace(/alt="">/g,'alt=""></a>')
                    }
                    if (v.direction == 'to_visiter') {
                        str += '<li class="chatmsg" id="xiaox_'+v.cid+'"><div class="showtime">'+showtime+'</div>';
                        str += '<div class="" style="position: absolute;top: 26px;right: 0;"><img class="my-circle cu_pic" src="' + pic + '" ></div>';
                        str += "<div class='outer-right'><div class='service'>";
                        str += "<pre>" + v.content + "&nbsp;&nbsp;<span onclick='revoke("+v.cid+",1);' class='revoke-text'>(撤销)</span></pre>";
                        str += "</div></div>";
                        str += "</li>";
                    } else{
                        str += '<li class="chatmsg"><div class="showtime">' +showtime+ '</div><div class="" style="position: absolute;left:0;">';
                        str += '<img class="my-circle  se_pic" src="' + avatver + '" ></div>';
                        str += "<div class='outer-left'><div class='customer'>";
                        str += "<pre>" + v.content + "</pre>";
                        str += "</div></div>";
                        str += "</li>";
                    }
                });

                var div = document.getElementById("wrap");
                if($.cookie("hid") == ""){
                 
                    $(".conversation").append(str);

                    if(div){          
                        $("img").load(function(){
                            div.scrollTop = div.scrollHeight;
                        });
                    }
                }else{
                    $(".conversation").prepend(str);
                    if(res.data.length <= 2){

                        $("#top_div").remove();
                        $(".conversation").prepend("<div id='top_div' class='showtime'>已没有数据</div>");
                        if(div){
                            div.scrollTop =0;
                        }

                    }else{
                        if(div){
                            div.scrollTop = div.scrollHeight / 3.3;
                        }
                    }
                }
                $("img[src*='upload/images']").parent().parent('.customer').css({
                    padding: '0',borderRadius: '0'
                });
                $("img[src*='upload/images']").parent().parent('.service').css({
                    padding: '0',borderRadius: '0'
                });
                $("img[src*='data:image/']").parent().parent('.customer').css({
                    padding: '0',borderRadius: '0'
                });
                $("img[src*='data:image/']").parent().parent('.service').css({
                    padding: '0',borderRadius: '0'
                })
                setTimeout(function(){
                    $('.chatmsg').css({
                        height: 'auto'
                    });

                },100)
                if(res.data.length >0){
                    $.cookie("hid",mindata);

                }

            }
        }
    });
}
$(document).on('click','.editusergroup',function (){
    var vid=$(this).data('vid');
    $.get(YMWL_ROOT_URL+"/admin/vgroup/user_group_list/vid/"+vid,function(res) {
        layer.open({
            skin: 'group',
            type: 1,
            title: '设置分组',
            area: ['300px', 'auto'],
            content: res,
            btn: ['确认', '取消'],
            yes: function (index, layero) {
               /* var s='';
                $('input[name="aihao"]:checked').each(function(){
                    s+=$(this).val()+',';
                });
                if (s.length > 0) {
                    //得到选中的checkbox值序列
                    s = s.substring(0,s.length - 1);
                }*/
                let group_id = [];
                var obj = document.getElementsByName("group");
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].checked)
                        group_id.push(obj[i].value);
                }
                if (group_id.length > 0) {
                    $.ajax({
                        url: YMWL_ROOT_URL+'/admin/custom/visitergroup',
                        type: 'post',
                        data: {
                            group_id: group_id,
                            vid: vid,
                        },
                        dataType: 'json',
                        success: function (res) {
                            if (res.code == 0) {

                                layer.closeAll();
                                layer.msg(res.msg, {icon: 1},function (){
                                    window.location.reload();
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }
                    });
                }
            }
        });
    });

})