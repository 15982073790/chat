<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:89:"/data/wwwroot/dev/chat.profittravel.com/public/../application/admin/view/index/chats.html";i:1620185392;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/header.html";i:1620746200;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/footer.html";i:1612957206;}*/ ?>
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
            document.title='??? ???'+myTitle;
        }

        if(record === 2){
            document.title='????????????'+myTitle;
        }

        if(record > 3){
              getwaitnum();
             return;
        }

          setTimeout("titleBlink()",500);//??????????????????????????????
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
            // ????????????
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
               layer.msg('??????????????????');
               getchat();
            });
            // ????????????
            var channelall = pusher.subscribe("all" + web);
            channelall.bind("on_notice", function (data) {
                if(<?php echo $arr['groupid']; ?> == 0 || <?php echo $arr['groupid']; ?> == data.message.groupid){
                    layer.msg(data.message.msg, {offset: "20px"});
                }

                if(<?php echo $arr['groupid']; ?> != data.message.groupid){
                 
                     layer.msg('?????????????????????????????????', {offset: "20px"});
                }

                  getwait();
                  getchat();
                
            });
            
            var channel =pusher.subscribe("kefu" + value);
            // ??????????????????
            channel.bind("callbackpusher",function(data){
                $.post("<?php echo url('admin/set/callback', '', true, true); ?>",data,function(res){
                })
            });

            // ??????????????????
            channel.bind("video",function (data) {
                getchat();
                var msg = data.message;
                var cha = data.channel;
                var cid = data.cid;
                var avatar =data.avatar;
                var username =data.username;
                layer.open({
                    type: 1,
                    title: '?????????',
                    area: ['260px', '160px'],
                    shade: 0.01,
                    fixed: true,
                    btn: ['??????', '??????'],
                    content: "<div style='position: absolute;left:20px;top:15px;'><img src='"+avatar+"' width='40px' height='40px' style='border-radius:40px;position:absolute;left:5px;top:5px;'><span style='width:100px;position:absolute;left:70px;top:5px;font-size:13px;overflow-x: hidden;'>"+username+"</span><div style='width:90px;height:20px;position:absolute;left:70px;top:26px;'>"+msg+"</div></div>",
                    yes: function () {
                        layer.closeAll('page');
                        var str='';
                        str+='<div class="videos">';
                        str+='<video id="localVideo" autoplay></video>';
                        str+='<video id="remoteVideo" autoplay class="hidden"></video></div>';


                        layer.open({
                          type:1
                          ,title: '??????'
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
                    ,btn: ['??????']
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

            // ????????????????????????
            channel.bind("video-refuse",function (data) {
                layer.alert(data.message);
                layer.closeAll('page');
            });
            // ????????????
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
                        str = '[??????]';
                    }
                });

                str = str.replace(/<div><a[^<>]+><i>.+?<\/i>.+?<\/a><\/div>/,'[??????]');
                str = str.replace(/<a[^<>]+>.+?<\/a>/,'[?????????]');
                str =str.replace(/<img src=['"]([^'"]+)[^>]*>/gi,'[??????]');

                $("#msg" + data.message.channel).html(str);
             
                var div = document.getElementById("wrap");

                } 
                getnow(data.message);
                if(div){
                    div.scrollTop = div.scrollHeight;
                }
                $("#notices-icon").removeClass('hide');

                console.log(data);
                notify(data.message.visiter_name || '?????????', {
                    body: str,
                    icon: data.message.avatar
                }, function(notification) {
                    //?????????????????????notification????????????tab??????
                    window.focus();
                    notification.close();
                    console.log('#v'+data.message.channel+' .visit_content');
                    $('#v'+data.message.channel+' .visit_content').trigger('click');
                    // $('#v'+data.message.channel+' .visit_content').trigger('click');
                });
            });


            // ?????? ????????????
            channel.bind("logout", function (data) {

                //??????????????????
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }

                if (chas == data.message.chas) {
                    //????????????
                    $("#v_state").text("??????");
                }

                $("#img" + data.message.chas).addClass("icon_gray");
                getchat();

            });

            channel.bind("geton", function (data) {

                //??????????????????
                var cdata = $.cookie("cu_com");
                var chas;
                if (cdata) {
                    var jsondata = $.parseJSON(cdata);
                    chas = jsondata.channel;
                }
                if (chas == data.message.chas) {
                    //????????????
                    $("#img" + data.message.chas).removeClass("icon_gray");
                    $("#v_state").text("??????");
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

                 
                    $(".profile").text('??????');
                }
            });

            pusher.connection.bind('connected', function() {
                $(".profile").text('??????');
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
            <ul id="group-menus-main" class="group-menus am-list "><?php if ($part == "??????"): ?>
                    <li class="menu-item" id="title1" onmouseover="showpage(this)" name="????????????"
                        style="background: #ffffff;color: #353535"><a href="<?php echo url('admin/index/index'); ?>"><i
                                class="layui-icon">&#xe638;</i><span style="color: #353535" class="info">????????????</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title1" onmouseover="showpage(this)" name="????????????"><a
                            href="<?php echo url('admin/index/index'); ?>"><i class="layui-icon">&#xe638;</i><span
                                style="color: #fff" class="info">????????????</span></a></li><?php endif;
                if ($part == "????????????"): ?>
                    <li class="menu-item" id="title2" onmouseover="showpage(this)" name="????????????"
                        style="background:#ffffff;color: #353535"><span class="notices hide"></span><span
                            class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i
                                class="layui-icon">&#xe606;</i><span style="color: #353535" class="info">????????????</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title2" onmouseover="showpage(this)" name="????????????">
                    <!-- <i id="notices-icon" class="hide"></i> --><span class="notices hide"></span><span
                            class="notices-icon hide"></span><a href="<?php echo url('admin/index/chats'); ?>"><i
                                class="layui-icon">&#xe606;</i><span style="color: #fff" class="info">????????????</span></a>
                    </li><?php endif;
                if ($arr['level'] != 'service'): if ($part == "????????????"): ?>
                    <li class="menu-item" id="title7" onmouseover="showpage(this)" name="????????????"
                        style="background: #ffffff;"><a href="<?php echo url('admin/manager/view'); ?>"><i
                                class="layui-icon">&#xe62a;</i><span style="color: #353535" class="info">????????????</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title7" onmouseover="showpage(this)" name="????????????"><a
                            href="<?php echo url('admin/manager/view'); ?>"><i class="layui-icon">&#xe62a;</i><span
                                style="color: #fff" class="info">????????????</span></a></li><?php endif; endif;
                if ($part == "????????????"): ?>
                    <li class="menu-item" id="title3" onmouseover="showpage(this)" name="????????????"
                        style="background: #ffffff;"><a href="<?php echo url('admin/evaluate/index'); ?>"><i
                                class="layui-icon">&#xe67b;</i><span style="color: #353535" class="info">????????????</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title3" onmouseover="showpage(this)" name="????????????"><a
                            href="<?php echo url('admin/evaluate/index'); ?>"><i class="layui-icon">&#xe67b;</i><span
                                style="color: #fff" class="info">????????????</span></a></li><?php endif;
                if ($part == "????????????"): ?>
                    <li class="menu-item" id="title8" onmouseover="showpage(this)" name="????????????"
                        style="background: #ffffff;"><a href="<?php echo url('admin/custom/index'); ?>"><i
                                class="layui-icon">&#xe770;</i><span style="color: #353535" class="info">????????????</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title8" onmouseover="showpage(this)" name="????????????"><a
                            href="<?php echo url('admin/custom/index'); ?>"><i class="layui-icon">&#xe770;</i><span
                                style="color: #fff" class="info">????????????</span></a></li><?php endif;
                if ($part == "????????????"): ?>
                    <li class="menu-item" id="title9" onmouseover="showpage(this)" name="????????????"
                        style="background: #ffffff;"><a href="<?php echo url('admin/index/front'); ?>"><i
                                class="layui-icon">&#xe64d; </i><span style="color: #353535"
                                                                      class="info">????????????</span></a></li><?php else: ?>
                    <li class="menu-item" id="title9" onmouseover="showpage(this)" name="????????????"><a
                            href="<?php echo url('admin/index/front'); ?>"><i class="layui-icon">&#xe64d;</i><span
                                style="color: #fff" class="info">????????????</span></a></li><?php endif;
                if ($part == '????????????'): ?>
                    <li class="menu-item" id="title10" onmouseover="showpage(this)" name="????????????"
                        style="background: #ffffff;"><a href="<?php echo url('admin/index/set'); ?>"><i
                                class="layui-icon">&#xe620;</i><span style="color: #353535" class="info">????????????</span></a>
                    </li><?php else: ?>
                    <li class="menu-item" id="title10" onmouseover="showpage(this)" name="????????????"><a
                            href="<?php echo url('admin/index/set'); ?>"><i class="layui-icon">&#xe620;</i><span
                                style="color: #fff" class="info">????????????</span></a></li><?php endif; ?>
                <li class="menu-item <?php if ($part == '????????????') echo 'bg-white'; ?>" onmouseover="showpage(this)"><a
                            href="<?php echo url('admin/service/loginlog'); ?>"><i
                                class="layui-icon layui-icon-log"></i><span style="color: #fff" class="info">????????????</span></a>
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
            //????????????????????????????????????????????????
            content: '?????????????????????OPENID????????????????????????????????????????????????'
            ,btn: ['????????????','??????']
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
                                title:'????????????,???????????????',
                                type: 1,
                                content: '<img style="width: 200px;height: 200px;" src="'+res.data+'"/>',
                            });
                        }
                    }
                });
                return false;
            },
            btn2: function(index, layero){
            //??????????????????????????????

            //return false ?????????????????????????????????????????????
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
                            <span class="profile">??????<i class="am-icon-caret-down"></i></span><?php else: ?><span
                                class="profile">??????<i class="am-icon-caret-down"></i></span><?php endif; ?></a>
                    <ul class="am-dropdown-content">
                        <li><a href='javascript:showinfo(<?php echo $data; ?>,<?php echo $group; ?>)'><img
                                        src="/assets/images/admin/B/person.png" alt="">????????????</a></li>
                        <li><a href='javascript:modify(<?php echo $arr["service_id"]; ?>)'><img
                                        src="/assets/images/admin/B/change-password.png" alt="">????????????</a>
                        </li><?php if ($arr['level'] == 'super_manager' && $referer): ?>
                            <li><a href="<?php echo url('platform/index/index'); ?>"><img
                                        src="/assets/images/admin/B/back-system.png" alt="">????????????</a></li><?php endif; ?>
                        <li><a href="<?php echo url('admin/login/logout', ['business_id' => $arr['business_id']]); ?>"
                               class="safe-exit"><img src="/assets/images/admin/B/quit.png" alt="">????????????
                            </a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <script type="text/javascript" src="/assets/libs/webrtc/recorder.js?v=LK_DIY6.0.3"></script>
    <div id="container" style="background-color: #f7f7f7;overflow: hidden">
        <div class="all_content" style="overflow-y: hidden;">
            <section class=""
                     style="width:20%;height:100%;position:absolute;border-top-left-radius: 8px;left:0px;background: #F7F7F7;min-width: 240px;">
                <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief"
                     style="margin:0px;position: absolute;top:0px;width: 100%;">
                    <ul class="layui-tab-title" style="height: 50px;border: 0;">
                        <li class="layui-this" style="width: 50%;color: #555555;height: 50px;line-height: 55px;">
                            ????????????<span class="line"></span></li>
                        <li style="width: 50%;color: #555555;height: 50px;line-height: 55px;">????????????
                            <div id="waitnum" class="notice-icon hide"
                                 style="position: absolute;top:0px;line-height: 18px;font-size: 8px;"></div>
                            <span class="line"></span></li>
                    </ul>
                    <div class="layui-tab-content" style="padding: 0px;">
                        <!--<img onclick="clearList()" class="clear-btn" src="/assets/images/index/clear.png"></img><span class="reply-border clear"></span><span class="reply-about clear">??????????????????</span>-->
                        <div class="layui-tab-item  layui-show" id="chat_list"
                             style="width: 100%;overflow-y: auto;"></div>
                        <div class="layui-tab-item" id="wait_list" style="width: 100%;overflow-y: auto;"></div>
                    </div>
                </div>
            </section>
            <section
                    style="width:52%;height:100%;position: absolute;left: 20%;background: #F7F7F7;padding: 0 9px;min-width: 600px;">
                <div class="no_chats"><i class="no_chats-pic"></i></div>
                <div class="chatbox hide" style="width: 100%;height: 100%;padding-bottom: 242px">
                    <div id="wrap" style="width: 100%;height:100%;overflow-y: auto;background-color: #fff">
                        <ul class="conversation"></ul>
                    </div>
                    <script type="text/javascript">                    window.onresize = function(){
                        var height =document.body.clientHeight;
                        $("#chat_list").css("height",(height -110)+"px");
                        $("#wait_list").css("height",(height-110)+"px");
                    }

                    document.getElementById("wrap").onscroll = function(){
                        var t =  document.getElementById("wrap").scrollTop;
                        if( t == 0 ) {
                            var sdata = $.cookie("cu_com");
                            var jsondata = $.parseJSON(sdata);
                            var chas = jsondata.visiter_id;
                            if($.cookie("hid") != ""){
                                getdata(chas);
                            }
                        }
                    }


                    function info(){
                        layer.tips("????????????????????????????????????????????????", "#paste", {tips: [1, '#9EC6EA']});
                    }


                    </script>
                    <div class="footer">
                        <div class="tool_box">
                            <div class="wl_faces_content">
                                <div class="wl_faces_main">
                                    <ul>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_01.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_02.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_03.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_04.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_05.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_06.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_07.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_08.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="?????????" src="/upload/emoji/emo_09.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_10.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_11.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_12.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_13.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_14.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_15.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_16.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_17.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_18.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_19.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_20.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_21.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_22.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_23.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_24.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_25.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_26.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_27.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_28.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="?????????" src="/upload/emoji/emo_29.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_30.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="?????????" src="/upload/emoji/emo_31.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="?????????" src="/upload/emoji/emo_32.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_33.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_34.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_35.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_36.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="?????????" src="/upload/emoji/emo_37.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_38.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_39.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_40.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_41.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_42.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_43.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_44.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_45.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_46.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_47.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_48.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_49.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_50.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_51.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_52.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_53.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_54.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_55.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_56.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_57.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_58.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="???" src="/upload/emoji/emo_59.gif"/></a>
                                        </li>
                                        <li><a href="javascript:;"><img title="??????" src="/upload/emoji/emo_60.gif"/></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="msg-input">
                            <div class="input-box"><textarea id="text_in" class="edit-ipt"
                                                             style="overflow-y: auto; font-weight: normal; font-size: 14px; overflow-x: hidden; word-break: break-all; font-style: normal; outline: none;padding: 5px;border:none;border-radius: 10px;border: 1px solid #ddd;"
                                                             contenteditable="true" hidefocus="true"
                                                             tabindex="0"></textarea></div>
                            <div class="msg-toolbar-footer grey12"><a onclick="send()" class="layui-btn msg-send-btn">
                                    ??????
                                </a><a id="showinfo" class="showinfo">
                                    <div style="height: 24px;border-left: 1px solid #FFF;margin-top: 8px;padding: 7px 15px">
                                        <img src="/assets/images/admin/B/up-menu.png" alt=""></div>
                                    <!-- <i class='triangle'  style="margin-top: 21px;"></i> --></a></div>
                        </div>
                        <div class="msg-toolbar" style="background: #fff;border: none;"><a id="face_icon"
                                                                                           onclick="faceon()"><img
                                        src="/assets/images/admin/B/smile.png" alt="??????" title="??????"></a><a>
                                <form id="picture" enctype="multipart/form-data">
                                    <div class="am-form-group am-form-file"><img src="/assets/images/admin/B/photo.png"
                                                                                 alt=""><input type="file" name="upload"
                                                                                               onchange="put()"/></div>
                                </form>
                            </a><a>
                                <form id="file" enctype="multipart/form-data">
                                    <div class="am-form-group am-form-file"><img src="/assets/images/admin/B/file.png"
                                                                                 alt=""><input type="file" name="folder"
                                                                                               onchange="putfile()"/>
                                    </div>
                                </form>
                            </a><?php if ($type == 'open'): ?><a onclick="getvideo()"><img
                                        src="/assets/images/admin/B/blacklist.png" alt=""></a><?php endif;
                            if ($atype == 'open'): ?><a onclick="getaudio()"><i class="layui-icon"
                                                                                style="font-size: 22px;cursor: pointer;">&#xe688;</i></a><?php endif; ?>
                            <a href="javascript:getblack()"><img src="/assets/images/admin/B/blacklist.png" alt="???????????????"
                                                                 title="???????????????"></a><a onclick="getswitch()"><img
                                        src="/assets/images/admin/B/transfer.png" alt="????????????" title="????????????"></a><a
                                    onclick="gethistory()"><img src="/assets/images/admin/B/record.png" alt="????????????"
                                                                title="????????????"></a><a onclick="toEvaluate()"><img
                                        src="/assets/images/admin/B/toEvaluate.png" alt="????????????" title="????????????"></a><a
                                    onmouseover="info()" id="paste"
                                    style="position:absolute; right:134px;bottom:30px;width: 120px;"><img
                                        src="/assets/images/admin/B/screen.png" alt=""> ??????????????????</a></div>
                    </div>
                </div><!-- ?????? -->
                <div id='fuceng' class="hide"
                     style="background: #f7f7f7;height: 68px;position: absolute;bottom: 114px;right: 20px;z-index: 9999;border-radius: 8px;padding: 8px 0">
                    <ul style="width: 100%;height: 60px;">
                        <li class="fuceng-li" onclick="choosetype(this)" name='1'><img id='type1'
                                                                                       class="layui-icon selecte-icon"
                                                                                       src="/assets/images/admin/B/selected.png"
                                                                                       alt=""><span>???Enter??????????????????Ctrl+Enter??????</span>
                        </li>
                        <li class="fuceng-li selected-li" onclick="choosetype(this)" name='2'><img id='type2'
                                                                                                   class="layui-icon selecte-icon"
                                                                                                   src="/assets/images/admin/B/selected.png"
                                                                                                   alt=""><span>???Ctrl+Enter??????????????????Enter??????</span>
                        </li>
                    </ul>
                </div>
            </section>
            <section class="chatinfo">
                <div class="layui-tab layui-tab-brief" lay-filter="docDemoTabBrief"
                     style="margin: 0;height: 100%;background-color: #fff;position: relative;">
                    <ul class="layui-tab-title" style="height: 50px;border-bottom: 0;background-color: #f7f7f7">
                        <li class="layui-this" style="width: 33%;height: 50px;line-height: 50px;color: #555555">
                            ????????????<span class="line"></span></li>
                        <li style="width: 33%;height: 50px;line-height: 50px;color: #555555">?????????<span
                                    class="line"></span></li>
                        <li style="width: 34%;height: 50px;line-height: 50px;color: #555555">????????????<span
                                    class="line"></span></li>
                    </ul>
                    <div class="layui-tab-content" style="padding: 16px;height: 100%">
                        <div class="layui-tab-item layui-show">
                            <div class="" style="margin:24px;color: #555555;">
                                <div style="font-size: 16px;">????????????</div>
                                <div style="margin-top: 12px;"> ?????????<span class="record"></span></div>
                                <div style="margin-top: 14px; font-size: 12px;"> ?????????<span class="ipdizhi"></span> ???<span
                                            class="iparea"></span>???
                                </div>
                                <div style="margin-top: 14px;"> ?????????<span id="v_state" style="font-size: 10px;"></span>
                                </div>
                                <div style="margin-top: 14px;"> ?????????????????????<span
                                            id="last_login_time" style="font-size: 10px;"></span></div>
                                <div style="margin-top: 14px;"> ???????????????<span
                                            id="login_times" style="font-size: 10px;"></span></div>
                                <div style="margin-top: 14px;"> ???????????????<span
                                            id="login_device" style="font-size: 10px;"></span></div>
                                <div style="margin-top: 14px;"> ?????????<input type="text" id="name" placeholder="???????????????????????????"
                                                                          class="layui-input" onblur="saveinfo()"/>
                                </div>
                                <div style="margin-top: 14px;"> ?????????<input type="text" id="tel" placeholder="???????????????????????????"
                                                                          class="layui-input" onblur="saveinfo()"/>
                                </div>
                                <div style="margin-top: 14px;"> ?????????<textarea id="comment" placeholder="????????????????????????????????????"
                                                                             class="layui-input" onblur="saveinfo()"
                                                                             style="height: 50px;"></textarea></div>
                            </div>
                        </div>
                        <div class="layui-tab-item" id='black_list'
                             style="width: 100%;overflow-y: auto;padding: 0px;"></div>
                        <div class="layui-tab-item" id='word_list' style="width: 100%;height: 100%; overflow-y: auto;">
                            <div id='quit_reply'></div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script type="text/javascript">    function toEvaluate() {
        var data = $.cookie("cu_com");
        var jsondata = $.parseJSON(data);
        $.ajax({
            url:YMWL_ROOT_URL + '/admin/set/pushComment',
            type:'post',
            data:{visiter_id:jsondata.visiter_id},
            success:function(res){
                if(res.code == 0){
                    var str = '';
                    str += "<div class='push-evaluation'>???????????????</div>"
                    $(".conversation").append(str);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                } else {
                    layer.msg(res.msg, {icon: 2});
                }
            }
        });
    }

    function saveinfo(){
        var data = $.cookie("cu_com");
        var jsondata = $.parseJSON(data);
        var name=$("#name").val();
        var tel=$('#tel').val();
        var comment=$("#comment").val();
        $.ajax({
          url:YMWL_ROOT_URL+'/admin/manager/saveVisiter',
          type:'post',
          data:{name:name,tel:tel,comment:comment,visiter_id:jsondata.visiter_id},
          success:function(res){
            if(res.code == 0){
                getchat();
            }
          }
        });
        
    }


    function show(){
        let text = $('.manager-reply').text();
        if(text == '??????????????????') {
            $('.manager-reply').text('????????????')
        }else {
            $('.manager-reply').text('??????????????????')
        }
        $('.del-reply').toggle();
    }

    function clearList() {
        layer.open({
            type: 1,
            area: ['360px', '180'],
            title:'',
            content: '<div style="text-align:center;margin: 50px 0 30px;font-size:14px;">?????????????????????????????????</div>',
            btn: ['??????', '??????'],
            yes:function(res){
                  $.ajax({
                    url:YMWL_ROOT_URL+"/admin/set/clear",
                    type: "post",
                    data: {
                        id: "<?php echo $arr['service_id']; ?>"
                    },
                    success: function (res) {
                        if (res.code ==0) {
                            layer.msg(res.msg,{icon:2,offset:'20px'});
                            layer.closeAll();
                            $('.clear-btn').hide();
                            location.reload();
                        }
                    }
                });
            }
        });
    }


    function addreply(id){
        $('.del-reply').hide();
        $('.manager-reply').text('??????????????????')
        layer.open({
            type: 2,
            skin:"tablist",
            title: '??????????????????',
            area: ['800px', '620px'],
            content: YMWL_ROOT_URL + '/admin/popups/quickreply/id/'+id
        });
    }

    function close(id){
        $.ajax({
            url:YMWL_ROOT_URL+'/admin/manager/delreply',
            type:'post',
            data:{id:id},
            success:function(res){
                if(res.code ==0){
                    layer.msg(res.msg,{icon:1,end:function(){
                        
                         $("#reply"+id).remove();
                    }});
                }
            }
        })
    }




    function getOs() {
        var OsObject = "";

        if (isFirefox = navigator.userAgent.indexOf("Firefox") > 0) {
            return "Firefox";
        }
    }

 function showDiv(){
       
       $("#fuceng").toggleClass('hide');
    }


    $(function (){

    $("#showinfo").on('click',function(){

        showDiv();

        $(document).one("click", function () {
        
         $("#fuceng").addClass('hide');

        }); 
        event.stopPropagation();//????????????????????????
    });

   $("#fuceng").click(function (event) 
    {
        event.stopPropagation();//????????????????????????
        
    });
 });
   


   

    function choosetype(obj){
        $(obj).addClass('selected-li');
        $(obj).siblings().removeClass('selected-li')
        var type =$(obj).attr('name');
        $.cookie('type',type);
        $("#fuceng").addClass('hide');

        types();
    }


    //??????qq???????????????
    (function () {
        var imgReader = function (item) {
            var blob = item.getAsFile(),
                reader = new FileReader();
            // ???????????????????????????????????????
            reader.onload = function (e) {
                var msg = '';
                msg += "<img   src='" + e.target.result + "'>";


                    var sdata = $.cookie('cu_com');
                    if (sdata) {
                        var json = $.parseJSON(sdata);
                        var img = json.avater;
                    }

                    var sid = $('#channel').text();
                    var se = $("#chatmsg_submit").attr('name');
                    var customer = $("#customer").text();
                    var pic = $("#se_avatar").attr('src');
                    var time;

                    if($.cookie("time") == ""){
                        var myDate = new Date();
                        let hours = myDate.getHours();
                        let minutes = myDate.getMinutes();
                        if(hours < 10 ) {
                            minutes = '0'+minutes.toString();
                        }
                        if(minutes < 10 ) {
                            minutes = '0'+minutes.toString();
                        }
                            time = hours+":"+minutes;
                        var timestamp = Date.parse(new Date());
                        $.cookie("time",timestamp/1000);

                    }else{

                        var timestamp = Date.parse(new Date());

                        var lasttime =$.cookie("time");
                        if((timestamp/1000 - lasttime) >30){
                            var myDate =new Date(timestamp);
                            let hours = myDate.getHours();
                            let minutes = myDate.getMinutes();
                            if(hours < 10 ) {
                                minutes = '0'+minutes.toString();
                            }
                            if(minutes < 10 ) {
                                minutes = '0'+minutes.toString();
                            }
                                time = hours+":"+minutes;
                        }else{
                            time ="";
                        }

                        $.cookie("time",timestamp/1000);

                    }
                    var str = '';
                    str += '<li class="chatmsg""><div class="showtime">' + time + '</div>';
                    str += '<div style="float: right;"><img  class="my-circle se_pic" src="' + pic + '" width="50px" height="50px"></div>';
                    str += "<div class='outer-right'><div class='service' style='padding:0;border-radius:0;max-height:100px'>";
                    str += "<pre>" + msg + "</pre>";
                    str += "</div></div>";
                    str += "</li>";

                    $(".conversation").append(str);
                    $("#text_in").empty();

                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                    setTimeout(function(){
                        $('.chatmsg').css({
                            height: 'auto'
                        });
                    },0)
                    $.ajax({
                        url:YMWL_ROOT_URL+"/admin/set/chats",
                        type: "post",
                        data: {visiter_id:sid,content: msg, avatar: img}
                    });
                    
                
            };
            // ????????????
            reader.readAsDataURL(blob);
        };
        document.getElementById('text_in').addEventListener('paste', function (e) {
            // ?????????????????????????????????????????????????????????
            var clipboardData = e.clipboardData,
                i = 0,
                items, item, types;

            if (clipboardData) {
                items = clipboardData.items;
                if (!items) {
                    return;
                }
                item = items[0];
                // ????????????????????????????????????
                types = clipboardData.types || [];
                for (; i < types.length; i++) {
                    if (types[i] === 'Files') {
                        item = items[i];
                        break;
                    }
                }
                // ???????????????????????????
                if (item && item.kind === 'file' && item.type.match(/^image\//i)) {
                    imgReader(item);
                }
            }
        });
    })();

  
    // ????????????
    var getvideo =function(){

        var sid = $('#channel').text();
        var pic = $("#se_avatar").attr('src');

        var times = (new Date()).valueOf();
        var se = $("#se").text();
        //??????
        $.ajax({
            url:YMWL_ROOT_URL+'/admin/set/apply',
            type: 'post',
            data: {id: sid,channel: times,avatar:pic,name:se},
            success:function(res){
                if(res.code !=0){
                    layer.msg(res.msg,{icon:2,offset:'20px'});
                }else{
                   
                    var str='';
                    str+='<div class="videos">';
                    str+='<video id="localVideo" autoplay></video>';
                    str+='<video id="remoteVideo" autoplay class="hidden"></video></div>';


                      layer.open({
                          type:1
                          ,title: '??????'
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
                         connenctVide(times);
                     }catch(e){
                         console.log(e);
                         return;
                     }

                }
            }

        });
        
      
    }




    //
    var gethistory=function(){

       var sdata = $.cookie("cu_com");
       var jsondata = $.parseJSON(sdata);
       var vid =jsondata.visiter_id;
        layer.open({
            type: 2,
            title: '???????????????????????????',
            area: ['600px', '500px'],
            content: YMWL_ROOT_URL+'/admin/index/history?visiter_id='+vid
        });

    }

    var getaudio =function(){

          //???????????????
                var audio_context;
                var recorder;
                var wavBlob;
                //????????????
                try {
                    // webkit shim
                    window.AudioContext = window.AudioContext || window.webkitAudioContext;
                    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.mediaDevices.getUserMedia;
                    window.URL = window.URL || window.webkitURL;

                    audio_context = new AudioContext;

                    if (!navigator.getUserMedia) {
                        console.log('??????????????????');
                    }
                    ;
                } catch (e) {
                    console.log(e);
                    return;
                }
                navigator.getUserMedia({audio: true}, function (stream) {
                    var input = audio_context.createMediaStreamSource(stream);
                    recorder = new Recorder(input);

                    var falg = window.location.protocol;
                    if (falg == 'https:') {
                        recorder && recorder.record();

                        //?????????????????????
                        layui.use(['jquery', 'layer'], function () {
                            var layer = layui.layer;

                            layer.msg('?????????...', {
                                icon: 16
                                , shade: 0.01
                                , skin: 'layui-layer-lan'
                                , time: 0 //20s???????????????
                                , btn: ['??????', '??????']
                                , yes: function (index, layero) {
                                    //??????????????????????????????
                                    recorder && recorder.stop();
                                    recorder && recorder.exportWAV(function (blob) {
                                        wavBlob = blob;
                                        var fd = new FormData();
                                        var wavName = encodeURIComponent('audio_recording_' + new Date().getTime() + '.wav');
                                        fd.append('wavName', wavName);
                                        fd.append('file', wavBlob);

                                        var xhr = new XMLHttpRequest();
                                        xhr.onreadystatechange = function () {
                                            if (xhr.readyState == 4 && xhr.status == 200) {
                                                jsonObject = JSON.parse(xhr.responseText);

                                                voicemessage = '<div style="cursor:pointer;text-align:center;" onclick="getstate(this)" data="play"><audio src="'+jsonObject.data.src+'"></audio><i class="layui-icon" style="font-size:25px;">&#xe652;</i><p>????????????</p></div>';

                                                    var sid = $('#channel').text();
                                                    var pic = $("#se_avatar").attr('src');
                                                    var time;

                                                    var sdata = $.cookie('cu_com');

                                                    if (sdata) {
                                                        var json = $.parseJSON(sdata);
                                                        var img = json.avater;

                                                    }

                                                    if($.cookie("time") == ""){
                                                        var myDate = new Date();
                                                        let hours = myDate.getHours();
                                                        let minutes = myDate.getMinutes();
                                                        if(hours < 10 ) {
                                                            minutes = '0'+minutes.toString();
                                                        }
                                                        if(minutes < 10 ) {
                                                            minutes = '0'+minutes.toString();
                                                        }
                                                            time = hours+":"+minutes;
                                                        var timestamp = Date.parse(new Date());
                                                        $.cookie("time",timestamp/1000);

                                                    }else{

                                                        var timestamp = Date.parse(new Date());

                                                        var lasttime =$.cookie("time");
                                                        if((timestamp/1000 - lasttime) >30){
                                                            var myDate =new Date(timestamp*1000);
                                                            let hours = myDate.getHours();
                                                            let minutes = myDate.getMinutes();
                                                            if(hours < 10 ) {
                                                                minutes = '0'+minutes.toString();
                                                            }
                                                            if(minutes < 10 ) {
                                                                minutes = '0'+minutes.toString();
                                                            }
                                                                time = hours+":"+minutes;
                                                        }else{
                                                            time ="";
                                                        }

                                                        $.cookie("time",timestamp/1000);
                                                    }
                                                var str = '';
                                                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                                                    str += '<div style="float: right;"><img  class="my-circle se_pic" src="' + pic + '" width="50px" height="50px"></div>';
                                                    str += "<div class='outer-right'><div class='service'>";
                                                    str += "<pre>" +  voicemessage + "</pre>";
                                                    str += "</div></div>";
                                                    str += "</li>";

                                                    $(".conversation").append(str);
                                                    $("#text_in").empty();

                                                    var div = document.getElementById("wrap");
                                                    div.scrollTop = div.scrollHeight;
                                                    $(".chatmsg").css({
                                                        height: 'auto'
                                                    });
                                                    $.ajax({
                                                        url:YMWL_ROOT_URL+"/admin/set/chats",
                                                        type: "post",
                                                        data: {visiter_id:sid,content:  voicemessage, avatar: img}
                                                    });
                                            }
                                        };
                                        xhr.open('POST', '/admin/event/uploadVoice');
                                        xhr.send(fd);
                                    });
                                    recorder.clear();
                                    layer.close(index);
                                }
                                , btn2: function (index, layero) {
                                    //??????????????????????????????
                                    recorder && recorder.stop();
                                    recorder.clear();
                                    audio_context.close();
                                    layer.close(index);
                                }
                            });

                        });
                    } else {
                        
                            layer.msg('?????????????????????https?????????');
                        
                    }


                }, function (e) {
                     layer.msg(e);
                });


    }

var getstate =function(obj){
       
       var c=obj.children[0];
 
       var state=$(obj).attr('data');
   
       if(state == 'play'){
         c.play();
         $(obj).attr('data','pause');
         $(obj).find('i').html("&#xe651;");
       
       }else if(state == 'pause'){
          c.pause();
         $(obj).attr('data','play');
          $(obj).find('i').html("&#xe652;");
       }

        c.addEventListener('ended', function () {  
         $(obj).attr('data','play');
         $(obj).find('i').html("&#xe652;");
        
       }, false);
    }

    var getswitch =function(){

    var sdata = $.cookie("cu_com");
    var jsondata = $.parseJSON(sdata);
    var sid = jsondata.visiter_id;

    var se = $("#se").text();

      layer.open({
            type: 2,
            title: '??????????????????',
            area: ['400px', '420px'],
            shade: false,
            content: YMWL_ROOT_URL+'/admin/index/service?visiter_id='+sid+'&name='+se
        });
    }


    </script>
    <script type="text/javascript" src="/assets/js/admin/chat.js?v=LK_DIY6.0.3"></script>
</div>
<script type="text/javascript" src="/assets/js/video.js?v=LK_DIY6.0.3"></script>
<script>    wolive_connect();

</script>
<script type="text/javascript" src="/assets/js/80zxcom.js?v=LK_DIY6.0.3"></script>
</body>
</html>