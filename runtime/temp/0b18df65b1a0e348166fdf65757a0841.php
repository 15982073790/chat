<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"/data/wwwroot/dev/chat.profittravel.com/public/../application/admin/view/index/set.html";i:1620819528;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/header.html";i:1620746200;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/footer.html";i:1612957206;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head><title><?php echo $seo['business_name']; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Content-Language" content="zh-CN">
    <link rel="shortcut icon" href="/favicon.ico"/>
    <script>        YMWL_ROOT_URL = "<?php echo $baseroot; ?>";

    </script>
    <link href="/assets/libs/layer/skin/layer.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/libs/amaze/css/amazeui.min.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="/assets/libs/jquery/jquery.min.js?v=LK_DIY6.0.3"></script>
    <script src="/assets/libs/jquery/jquery.form.min.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/jquery/jquery.cookie.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/layer/layer.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/amaze/js/amazeui.min.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/layui/layui.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <link href="/assets/libs/layui/css/layui.css?v=LK_DIY6.0.3" rel="stylesheet">
    <link href="/assets/libs/bootstrap/bootstrap.min.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/common.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/admin.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/reload.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <script src="/assets/js/admin/functions.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <link href="/assets/css/admin/index.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/index_me.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <link href="/assets/css/admin/set.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
    <script src="/assets/js/admin/common_me.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/push/pusher.min.js?v=LK_DIY6.0.3" type="text/javascript"></script>
    <script src="/assets/libs/adapter.js?v=LK_DIY6.0.3"></script>
    <script type="application/javascript">        var mediaStreamTrack;
        var WEB_SOCKET_SWF_LOCATION = "/assets/libs/web_socket/WebSocketMain.swf";
        var WEB_SOCKET_DEBUG = true;
		var WEB_SOCKET_SUPPRESS_CROSS_DOMAIN_SWF_ERROR = true;
        var chat_data =Array();
        var record;
        var choose_lock = false;
        var myTitle = document.title;
        var msgreminder = <?php echo config('setting.msgreminder'); ?>;
        var config ={
          'app_key':'<?php echo $app_key; ?>',
          'whost':'<?php echo $whost; ?>',
          'value':<?php echo $value; ?>,
          'wport':<?php echo $wport; ?>        };
        function titleBlink(){
        record++;

        if(record === 3){
             record =1;
        }
         
        if(record === 1){
            document.title='【 】'+myTitle;
        }

        if(record === 2){
            document.title='【消息】'+myTitle;
        }

        if(record > 3){
              getwaitnum();
             return;
        }

          setTimeout("titleBlink()",500);//调节时间，单位毫秒。
        }

        layui.use('element', function () {
           var element = layui.element;
        });
        var wolive_connect =function () {
            pusher = new Pusher('<?php echo $app_key; ?>', {
                encrypted: <?php echo $value; ?>                , enabledTransports: ['ws']
                , wsHost: '<?php echo $whost; ?>'
                , <?php echo $port; ?>: <?php echo $wport; ?>                , authEndpoint: YMWL_ROOT_URL + '/admin/login/auth'
                ,disableStats: true
         });

            var web = "<?php echo $arr['business_id']; ?>";
            var value ="<?php echo $arr['service_id']; ?>";
            // 私人频道
            var channelme = pusher.subscribe("ud" + value);
            channelme.bind("on_notice", function (data) {
                if(data.message.type == 'change'){
                    layer.msg(data.message.msg);
                }
                getchat();
                getwait();
            });

            channelme.bind("on_chat", function (data) {
               $.cookie("cu_com",'');
               layer.msg('该访客被删除');
               getchat();
            });
            // 公共平道
            var channelall = pusher.subscribe("all" + web);
            channelall.bind("on_notice", function (data) {
                if(<?php echo $arr['groupid']; ?> == 0 || <?php echo $arr['groupid']; ?> == data.message.groupid){
                    layer.msg(data.message.msg, {offset: "20px"});
                }

                if(<?php echo $arr['groupid']; ?> != data.message.groupid){
                 
                     layer.msg('该用户向其他分组咨询！', {offset: "20px"});
                }

                  getwait();
                  getchat();
                
            });
            
            var channel =pusher.subscribe("kefu" + value);
            // 发送一个推送
            channel.bind("callbackpusher",function(data){
                $.post("<?php echo url('admin/set/callback', '', true, true); ?>",data,function(res){
                })
            });

            // 接受视频请求
            channel.bind("video",function (data) {
                getchat();
                var msg = data.message;
                var cha = data.channel;
                var cid = data.cid;
                var avatar =data.avatar;
                var username =data.username;
                layer.open({
                    type: 1,
                    title: '申请框',
                    area: ['260px', '160px'],
                    shade: 0.01,
                    fixed: true,
                    btn: ['接受', '拒绝'],
                    content: "<div style='position: absolute;left:20px;top:15px;'><img src='"+avatar+"' width='40px' height='40px' style='border-radius:40px;position:absolute;left:5px;top:5px;'><span style='width:100px;position:absolute;left:70px;top:5px;font-size:13px;overflow-x: hidden;'>"+username+"</span><div style='width:90px;height:20px;position:absolute;left:70px;top:26px;'>"+msg+"</div></div>",
                    yes: function () {
                        layer.closeAll('page');
                        var str='';
                        str+='<div class="videos">';
                        str+='<video id="localVideo" autoplay></video>';
                        str+='<video id="remoteVideo" autoplay class="hidden"></video></div>';


                        layer.open({
                          type:1
                          ,title: '视频'
                          ,shade:0
                          ,closeBtn:1
                          ,area: ['440px', '378px']
                          ,content:str
                          ,end:function(){

                           
                             mediaStreamTrack.getTracks().forEach(function (track) {
                                track.stop();
                            });
            
                          }
                      });
                        try{
                          connenctVide(cid);
                         }catch(e){
                             console.log(e);
                             return;
                         }
                        
                    },
                    btn2:function(){
                        var sid = $('#channel').text();
                        $.ajax({
                            url:YMWL_ROOT_URL+'/admin/set/refuse',
                            type:'post',
                            data:{channel:cha}
                        });

                        layer.closeAll('page');
                    }
                });
            });

            channel.bind('bind-wechat',function(data){
                layer.open({
                    content: data.message
                    ,btn: ['确定']
                    ,yes: function(index, layero){
                        location.reload();
                    }
                    ,cancel: function(){
                        return false;
                    }
                });
            });


            channel.bind('getswitch',function(data){
                layer.alert(data.message);
                getchat();
            });

            // 接受拒绝视频请求
            channel.bind("video-refuse",function (data) {
                layer.alert(data.message);
                layer.closeAll('page');
            });
            // 接受消息
            channel.bind("cu-event", function (data) {
                if("<?php echo $voice; ?>" == 'open'){
                    audioElementHovertree = document.createElement('audio');
                    audioElementHovertree.setAttribute('src', "<?php echo $voice_address; ?>");
                    audioElementHovertree.setAttribute('autoplay', 'autoplay');
                }
                var debug, portrait,showtime;
                var cdata = $.cookie("cu_com");

                if (cdata) {
                    var json = $.parseJSON(cdata);
                    debug = json.visiter_id;
                    portrait = json.avatar;
                } else {
                    debug = "";

                }

                if($.cookie("time") == ""){
                    time =data.message.timestamp;
                    $.cookie("time",time);
                    var mydate =new Date(time*1000);
                    showtime =mydate.getHours()+":"+mydate.getMinutes();
                }else{
                    time =$.cookie("time");
                    if((data.message.timestamp - time) >60){
                        var mydate =new Date(data.message.timestamp*1000);
                        showtime =mydate.getHours()+":"+mydate.getMinutes();
                    }else{
                        showtime ="";
                    }
                    $.cookie("time",data.message.timestamp);
                }
                var msg = '';
                msg += '<li class="chatmsg"><div class="showtime">' +showtime+ '</div><div style="position: absolute;left:3px;">';
                msg += '<img class="my-circle  se_pic" src="' + portrait + '" width="50px" height="50px"></div>';
                msg += "<div class='outer-left'><div class='customer'>";
                msg += "<pre>" + data.message.content + "</pre>";
                msg += "</div></div>";
                msg += "</li>";
                var str = data.message.content;
                if (data.message.visiter_id == debug) {
                    $(".conversation").append(msg);
                    getwatch(data.message.visiter_id);


                    str.replace(/<img [^>]*src=['"]([^'"]+)[^>]*>/gi, function (match, capture) {

                    var pos = capture.lastIndexOf("/");
                    var value = capture.substring(pos + 1);

                    if (value.indexOf("emo") == 0) {
                        str = data.message.content;
                    } else {
                        str = '[图片]';
                    }
                });

                str = str.replace(/<div><a[^<>]+><i>.+?<\/i>.+?<\/a><\/div>/,'[文件]');
                str = str.replace(/<a[^<>]+>.+?<\/a>/,'[超链接]');
                str =str.replace(/<img src=['"]([^'"]+)[^>]*>/gi,'[图片]');

                $("#msg" + data.message.channel).html(str);
             
                var div = document.getElementById("wrap");

                } 
                getnow(data.message);
                if(div){
                    div.scrollTop = div.scrollHeight;
                }
                $("#notices-icon").removeClass('hide');

                console.log(data);
                notify(data.message.visiter_name || '新访客', {
                    body: str,
                    icon: data.message.avatar
                }, function(notification) {
                    //可直接打开通知notification相关联的tab窗口
                    window.focus();
                    notification.close();
                    console.log('#v'+data.message.channel+' .visit_content');
                    $('#v'+data.message.channel+' .visit_content').trigger('click');
                    // $('#v'+data.message.channel+' .visit_content').trigger('click');
                });
            });


            // 通知 游客离线
            channel.bind("logout", function (data) {

                //表示访客离线
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }

                if (chas == data.message.chas) {
                    //头像变灰
                    $("#v_state").text("离线");
                }

                $("#img" + data.message.chas).addClass("icon_gray");
                getchat();

            });

            channel.bind("geton", function (data) {

                //表示访客在线
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }
                if (chas == data.message.chas) {
                    //头像变亮
                    $("#img" + data.message.chas).removeClass("icon_gray");
                    $("#v_state").text("在线");
                }

                $("#img" + data.message.chas).removeClass("icon_gray");
                getchat();

            });

            pusher.connection.bind('state_change', function(states) {
              
                if(states.current == 'unavailable' || states.current == "disconnected" || states.current == "failed" ){

                        pusher.unsubscribe("kefu" + value);
                        pusher.unsubscribe("all" + web);
                        pusher.unsubscribe("ud" + value);
            
                    if (typeof pusher.isdisconnect == 'undefined') {
                     pusher.isdisconnect = true;

                     pusher.disconnect();
                     delete pusher;
                     
                      window.setTimeout(function(){
                         wolive_connect();
                      },1000);
                    }

                 
                    $(".profile").text('离线');
                }
            });

            pusher.connection.bind('connected', function() {
                $(".profile").text('在线');
            });
        }


        function showpage(obj){

            var value =$(obj).attr("name");
            var key =$(obj).attr("id");
            layer.tips(value, '#'+key,{tips: [4, '#2F4050']});
        }




    </script>
    <script type="text/javascript" src="/assets/libs/web_socket/swfobject.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/libs/web_socket/web_socket.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript" src="/assets/js/admin/online.js?v=LK_DIY6.0.3"></script>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="/assets/css/admin/ymwl_common.css?v=LK_DIY6.0.3" type="text/css" rel="stylesheet"/>
</head>
<body>
<div id="layout-west">
    <div id="layout-menus">
        <div id="layout-menus-info"><a href="javascript:;"><span class="info"
                                                                 style="color: #ffffff;font-size: 16px;vertical-align:middle;display:table-cell;position: static;left: 0"><img
                            src="<?php echo !empty($seo['logo']) ? $seo['logo'] : '/assets/images/index/workerman_logo.png'; ?>"
                            class="am-circle" style="margin: 0 10px 0 20px;"><?php echo $seo['business_name']; ?></span></a>
        </div>
        <div id="layout-menus-lists">
            <ul id="group-menus-main" class="group-menus am-list "><?php if ($part == "首页"): ?>
                    <li class="menu-item" id="title1" onmouseover="showpage(this)" name="系统首页"
                        style="background: #ffffff;color: #353535"><a href="<?php echo url('admin/index/index'); ?>"><i
                                class="layui-icon">&#xe638;</i><span style="color: #353535" class="info">系统首页</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title1" onmouseover="showpage(this)" name="系统首页"><a
                            href="<?php echo url('admin/index/index'); ?>"><i class="layui-icon">&#xe638;</i><span
                                style="color: #fff" class="info">系统首页</span></a></li><?php endif;
                if ($part == "客户咨询"): ?>
                    <li class="menu-item" id="title2" onmouseover="showpage(this)" name="客户咨询"
                        style="background:#ffffff;color: #353535"><span class="notices hide"></span><span
                            class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i
                                class="layui-icon">&#xe606;</i><span style="color: #353535" class="info">客户咨询</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title2" onmouseover="showpage(this)" name="客户咨询">
                    <!-- <i id="notices-icon" class="hide"></i> --><span class="notices hide"></span><span
                            class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i
                                class="layui-icon">&#xe606;</i><span style="color: #fff" class="info">客户咨询</span></a>
                    </li><?php endif;
                if ($arr['level'] != 'service'): if ($part == "历史记录"): ?>
                    <li class="menu-item" id="title7" onmouseover="showpage(this)" name="历史记录"
                        style="background: #ffffff;"><a href="<?php echo url('admin/manager/view'); ?>"><i
                                class="layui-icon">&#xe62a;</i><span style="color: #353535" class="info">历史记录</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title7" onmouseover="showpage(this)" name="历史记录"><a
                            href="<?php echo url('admin/manager/view'); ?>"><i class="layui-icon">&#xe62a;</i><span
                                style="color: #fff" class="info">历史记录</span></a></li><?php endif; endif;
                if ($part == "评价管理"): ?>
                    <li class="menu-item" id="title3" onmouseover="showpage(this)" name="评价管理"
                        style="background: #ffffff;"><a href="<?php echo url('admin/evaluate/index'); ?>"><i
                                class="layui-icon">&#xe67b;</i><span style="color: #353535" class="info">评价管理</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title3" onmouseover="showpage(this)" name="评价管理"><a
                            href="<?php echo url('admin/evaluate/index'); ?>"><i class="layui-icon">&#xe67b;</i><span
                                style="color: #fff" class="info">评价管理</span></a></li><?php endif;
                if ($part == "客户管理"): ?>
                    <li class="menu-item" id="title8" onmouseover="showpage(this)" name="客户管理"
                        style="background: #ffffff;"><a href="<?php echo url('admin/custom/index'); ?>"><i
                                class="layui-icon">&#xe770;</i><span style="color: #353535" class="info">客户管理</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title8" onmouseover="showpage(this)" name="客户管理"><a
                            href="<?php echo url('admin/custom/index'); ?>"><i class="layui-icon">&#xe770;</i><span
                                style="color: #fff" class="info">客户管理</span></a></li><?php endif;
                if ($part == "接入方法"): ?>
                    <li class="menu-item" id="title9" onmouseover="showpage(this)" name="接入方法"
                        style="background: #ffffff;"><a href="<?php echo url('admin/index/front'); ?>"><i
                                class="layui-icon">&#xe64d; </i><span style="color: #353535"
                                                                      class="info">接入方法</span></a></li><?php else: ?>
                    <li class="menu-item" id="title9" onmouseover="showpage(this)" name="接入方法"><a
                            href="<?php echo url('admin/index/front'); ?>"><i class="layui-icon">&#xe64d;</i><span
                                style="color: #fff" class="info">接入方法</span></a></li><?php endif;
                if ($part == '系统设置'): ?>
                    <li class="menu-item" id="title10" onmouseover="showpage(this)" name="系统设置"
                        style="background: #ffffff;"><a href="<?php echo url('admin/index/set'); ?>"><i
                                class="layui-icon">&#xe620;</i><span style="color: #353535" class="info">系统设置</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title10" onmouseover="showpage(this)" name="系统设置"><a
                            href="<?php echo url('admin/index/set'); ?>"><i class="layui-icon">&#xe620;</i><span
                                style="color: #fff" class="info">系统设置</span></a></li><?php endif; ?>
                <li class="menu-item <?php if ($part == '登录日志') echo 'bg-white'; ?>" onmouseover="showpage(this)"><a
                            href="<?php echo url('admin/service/loginlog'); ?>"><i
                                class="layui-icon layui-icon-log"></i><span style="color: #fff" class="info">登录日志</span></a>
                </li><?php echo hook('adminleftmenuhook', ['part' => $part]); ?></ul>
        </div>
    </div>
</div>
<script type="text/javascript">    var width =document.body.clientWidth;

    var changeall =function(){
        $("#layout-west").css("width","160px");
        $("#layout-center").css({"position":"absolute","left":"160px","width":(width-160)+"px"});
        $(".info").removeClass("hide");
        $(".west_foot2").removeClass("hide");
        $(".west_foot1").addClass("hide");
        if($(".notices")[0].textContent > 0) {
            $('.notices').removeClass("hide");
            $('.notices-icon').addClass("hide");     
        }
    }
    var changesmall =function(){
        if($(".notices")[0].textContent > 0) {
            $('.notices').removeClass("hide");
        }
    }
    setInterval(function(){
        changesmall();
    },1000);
    if (<?php echo $is_bind_wechat; ?>) {
        var index = layer.open({
            //您还未绑定微信，无法接受模板消息
            content: '当前账户未设置OPENID，无法接受模板消息，是否前去绑定'
            ,btn: ['前去绑定','忽略']
            ,yes: function(index, layero){
                $.ajax({
                    url: "/admin/index/qrcode.html",
                    dataType: 'json',
                    success: function (res) {
                        layer.close(index);
                        if(res.code){
                            layer.msg(res.msg);
                        }else{
                            layer.open({
                                title:'扫码关注,绑定公众号',
                                type: 1,
                                content: '<img style="width: 200px;height: 200px;" src="'+res.data+'"/>',
                            });
                        }
                    }
                });
                return false;
            },
            btn2: function(index, layero){
            //按钮【按钮二】的回调

            //return false 开启该代码可禁止点击该按钮关闭
            }
            ,cancel: function(){

            }
        });
    }

</script>
<div id="layout-center">
    <div id="layout-north"><a id="layout-logo" style="font-size: 1.2em;"><i
                    class="am-icon-bookmark"></i>&nbsp;<?php echo $part; ?></a>
        <ul id="layout-tools" class="am-nav am-nav-pills">
            <li>
                <div class="am-dropdown " data-am-dropdown><a class="am-dropdown-toggle" href="javascript:;"
                                                              data-am-dropdown-toggle><img id="se_avatar"
                                                                                           src="<?php echo $arr['avatar']; ?>"
                                                                                           alt="..." class="am-circle"
                                                                                           width="28px"
                                                                                           height="28px"><span
                                class="handle" id="se"><?php echo $arr['nick_name']; ?></span><span id="channel"
                                                                                                    style="display: none;"></span><span
                                id="customer" style="display: none;"></span><?php if ($arr['state'] == 'online'): ?>
                            <span class="profile">在线<i class="am-icon-caret-down"></i></span><?php else: ?><span
                                class="profile">离线<i class="am-icon-caret-down"></i></span><?php endif; ?></a>
                    <ul class="am-dropdown-content">
                        <li><a href='javascript:showinfo(<?php echo $data; ?>,<?php echo $group; ?>)'><img
                                        src="/assets/images/admin/B/person.png" alt="">个人资料</a></li>
                        <li><a href='javascript:modify(<?php echo $arr["service_id"]; ?>)'><img
                                        src="/assets/images/admin/B/change-password.png" alt="">修改密码</a>
                        </li><?php if ($arr['level'] == 'super_manager' && $referer): ?>
                            <li><a href="<?php echo url('platform/index/index'); ?>"><img
                                        src="/assets/images/admin/B/back-system.png" alt="">返回系统</a></li><?php endif; ?>
                        <li><a href="<?php echo url('admin/login/logout', ['business_id' => $arr['business_id']]); ?>"
                               class="safe-exit"><img src="/assets/images/admin/B/quit.png" alt="">安全退出
                            </a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <style>    .part {
            width: 96%;
            margin-top: 20px;
        }

        .person-setting {
            height: 208px;
        }

        .system-setting {
            height: 208px;
        }

        .part-item {
            width: 120px;
            height: 120px;
            position: relative;
            cursor: pointer;
            float: left;
            margin-left: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            /*        box-shadow: 6px 11px 41px -28px #a99de7;23c771
                    background-image: linear-gradient(230deg, #759bff, #843cf6);*/
            box-shadow: 6px 11px 41px -28px #1dc46c;
            background-image: linear-gradient(230deg, #72dca5, #03ca62);
        }

        .part-item:first-child {
            margin-left: 40px;
        }

        .part-item:hover {
            background-image: linear-gradient(230deg, #03ca62, #72dca5);
            box-shadow: 0 0 8px 2px rgba(0, 0, 0, .1);
        }

        .part-item .part-szico {
            margin-top: 20px;
            font-size: 50px;
            position: absolute;
            left: 35px;
            color: #fff;
        }


        .part-item span {
            position: absolute;
            bottom: 10px;
            width: 120px;
            color: #fff;
            text-align: center;
        }

        .page-header {
            border-bottom: 0;
            margin-left: 40px;
        }
    </style>
    <div id="container" style="overflow-y: auto">
        <div class="layui-fluid">
            <div class="layui-col-sm12">
                <div class="layui-card">
                    <div class="layui-card-header" style="font-size:20px;"> 个人设置
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space15" style="background-color: #fff;">
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="part"><a href="<?php echo url('admin/index/custom'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe63a;</i><span>首次对话问候语</span>
                            </div>
                        </a></div>
                </div>
            </div>
        </div><?php if ($user['level'] != 'service'): ?>
        <div class="layui-fluid">
            <div class="layui-col-sm12">
                <div class="layui-card">
                    <div class="layui-card-header" style="font-size:20px;"> 系统设置
                    </div>
                </div>
            </div>
            <div class="layui-row layui-col-space15" style="background-color: #fff;">
                <div class="layui-col-sm12">
                    <div style="margin-top: 20px;"><a href="<?php echo url('admin/index/setup'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe716;</i><span>通用基础设置</span>
                            </div>
                        </a><a href="<?php echo url('admin/manager/info'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe61f;</i><span>客服添加与管理</span>
                            </div>
                        </a><a href="<?php echo url('admin/manager/group'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe610;</i><span>客服分组管理</span>
                            </div>
                        </a><a href="<?php echo url('admin/evaluate/setting'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe600;</i><span>客服评价设置</span>
                            </div>
                        </a><a href="<?php echo url('admin/index/question'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe615;</i><span>常见问题管理</span>
                            </div>
                        </a><a href="<?php echo url('admin/rest/setting'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe637;</i><span>上下班设置</span></div>
                        </a><a href="<?php echo url('admin/index/tablist'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe632;</i><span>TAB栏编辑</span>
                            </div>
                        </a><a href="<?php echo url('admin/index/template'); ?>">
                            <div class="part-item"><i class="layui-icon part-szico">&#xe677;</i><span>客服微信对接</span>
                            </div>
                        </a></div>
                </div><?php endif; ?></div>
        </div>
        <script type="text/javascript" src="/assets/js/video.js?v=LK_DIY6.0.3"></script>
        <script>    wolive_connect();

        </script>
        <script type="text/javascript" src="/assets/js/80zxcom.js?v=LK_DIY6.0.3"></script>
</body>
</html>