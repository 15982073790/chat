<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:90:"/data/wwwroot/dev/chat.profittravel.com/public/../application/admin/view/manager/info.html";i:1619138110;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/header.html";i:1620746200;s:81:"/data/wwwroot/dev/chat.profittravel.com/application/admin/view/public/footer.html";i:1612957206;}*/ ?>
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
    <style>    .head {
            margin: 10px 0px;
            height: 40px;
            line-height: 40px;
            background: #ddd;
            display: flex;
            justify-content: space-between;
        }

        .head .title {
            padding: 0px 15px;
        }

        .handler .back .img {
            width: 16px;
            height: 16px;
            margin: 6px 0 0 16px;
            float: left;
            background: url("/assets/images/admin/B/back-system.png");
        }

        .changepwd .layui-layer-ico {
            height: 24px;
            width: 24px;
            background: url("/assets/images/admin/B/cha.png") no-repeat;
        }

        .changepwd .layui-layer-content {
            padding: 0 16px;
            margin-right: 20px;
        }

        .recycle {
            float: left;
            margin-left: 40px;
            width: 20px;
            height: 20px;
            background: url("/assets/images/admin/B/delete.png") no-repeat;
            cursor: pointer;
        }

        .copybtn:focus {
            outline: 0;
        }

        #container {
            background-color: #fff;
        }
    </style>
    <div id="container" style="overflow-y: auto;padding-bottom: 42px"><h2 class="page-header"
                                                                          style="font-size: 18px;color: #555555;">
            ?????????????????????</h2>
        <div class="handler"><a href="<?php echo url('admin/index/set'); ?>">
                <div class="back"><i class="img"></i><span>????????????</span></div>
            </a><a onclick="add()" class="" data-pjax>
                <div class="button"> +????????????
                </div>
            </a></div>
        <div style="height:40px;line-height:40px;padding-left:16px;color: #555555;font-size: 14px;border-radius: 8px;background: #F5FAFF;margin: 20px 0 20px 0;">
            ?????????????????????<?php echo $count; ?>
            ???????????????????????? <?php echo !empty($business['max_count']) ? $business['max_count'] : '?????????'; ?></div>
        <table class="layui-table">
            <colgroup>
                <col>
                <col>
                <col width="200">
                <col width="260">
                <col width="337">
            </colgroup>
            <thead>
            <tr>
                <th>?????????</th>
                <th>??????</th>
                <th>????????????</th>
                <th>??????id</th>
                <th>??????</th>
            </tr>
            </thead>
            <tbody><?php if (is_array($lister) || $lister instanceof \think\Collection || $lister instanceof \think\Paginator): $i = 0;
                $__LIST__ = $lister;
                if (count($__LIST__) == 0) : echo ""; else: foreach ($__LIST__ as $key => $admins): $mod = ($i % 2);
                    ++$i; ?>
                    <tr>
                    <td><?php echo $admins['user_name']; ?></td>
                    <td><?php echo $admins['nick_name']; ?></td>
                    <td><?php echo $admins['groupname']; ?></td>
                    <td><?php echo $admins['business_id']; ?></td>
                    <td><a href='javascript:showinfo(<?php echo $admins['json']; ?>,<?php echo $group; ?>)'><img
                                    src="/assets/images/admin/A/edit.png" alt="??????" title="??????"></a><a
                                href='javascript:copyhref("<?php echo $admins['personal']; ?>","<?php echo $admins['personalwechat']; ?>")'
                                class="copybtn"><img src="/assets/images/admin/A/copy.png" alt="??????????????????" title="??????????????????"></a><?php if ($admins['level'] != 'super_manager'): ?>
                            <a href='javascript:modifyCustom(<?php echo $admins['service_id']; ?>)'><img
                                        src="/assets/images/admin/A/change.png" alt="????????????" title="????????????"></a><a
                                href='javascript:sdelete(<?php echo $admins['service_id']; ?>)'><img
                                        src="/assets/images/admin/A/delete.png" alt="??????" title="??????"></a><?php endif;
                        if (!empty($admins['open_id'])): ?><a
                            href='javascript:wechat("<?php echo $admins['open_id']; ?>","<?php echo $admins['service_id']; ?>")'>
                                <img src="/assets/images/admin/B/wechat.png" alt="???????????????" title="???????????????"></a><?php endif;
                        if ($admins['offline_first'] == 1): ?><a
                            href='javascript:setofflinefirst(<?php echo $admins['service_id']; ?>,0)'><img
                                        src="/assets/images/admin/B/offline-disabled.png" alt="??????????????????" title="??????????????????">
                            </a><?php else: ?><a
                            href='javascript:setofflinefirst(<?php echo $admins['service_id']; ?>,1)'><img
                                        src="/assets/images/admin/B/offline-enable.png" alt="????????????" title="????????????">
                            </a><?php endif; ?></td></tr><?php endforeach; endif; else: echo "";endif; ?></tbody>
        </table><?php echo $page; ?></div>
    <script src="/assets/js/platform/clipboard.min.js?v=LK_DIY6.0.3"></script>
    <script type="text/javascript">    var sdelete = function (id) {

        layer.open({
            type: 1,
            title: '??????',
            area: ['260px', '180px'],
            content: "<div><center><p style='font-size:15px;margin:33px auto;'>?????????????????????</p></center></div>",
            btn: ['??????', '??????'],
            yes: function () {
                $.ajax({
                    url:YMWL_ROOT_URL+"/admin/manager/delete",
                    type: "post",
                    data: {id: id},
                    success: function (res) {
                        if(res.code == 0){
                             layer.msg(res.msg,{time:2000,end:function () {
                                location.href = "<?php echo url('admin/manager/info'); ?>";
                            }});
                        }               
                    }
                });
            }
        });
    };

    var wechat = function (open_id,service_id) {

        layer.open({
            type:2,
            title:'??????????????????',
            area:['800px','300px'],
            content:YMWL_ROOT_URL + '/admin/manager/wechat/open_id/'+open_id + '/service_id/' + service_id,
        });

    };

    var copyhref = function (href,wechat) {

        var copyhtml = '<div id="hrefs" style="padding: 30px 0 0 75px;position:relative">\n' +
            '            <button type="button" class="btn copybtn" data-clipboard-text= ' + href + ' style="outline:none;background: #25c16f; height: 32px;margin: 0 10px; border-radius: 16px;color: white; ;">????????????</button>\n' +
            '            <button type="button" class="btn copybtn" data-clipboard-text= ' + wechat + ' style="outline:none;background: #25c16f; height: 32px;margin: 0 10px; border-radius: 16px;color: white; ">?????????????????????</button>\n' +
            '        </div>';


        var clipboard = new ClipboardJS('.copybtn');
        clipboard.on('success', function(e) {
            layer.msg('????????????', {icon: 1});
            e.clearSelection();
        });
        clipboard.on('error', function(e) {
            layer.msg('?????????????????????????????????????????????????????????');
        });

        layer.open({
            type:1,
            title:'??????????????????',
            area:['400px','150px'],
            content:copyhtml,
        });

    };

    function modifyCustom(id)
    {
        var html  ='';
        html +='<form id="pass" class="layui-form" style="margin-top:30px;">';
        html +='<div class="layui-form-item"><label class="layui-form-label" style="font-size: 13px;color: #999999;width: 125px;">???????????????:</label>';
        html +='<div class="layui-input-block">';
        html +=' <input type="text" id="newpass" name="newpass" placeholder="??????????????????" autocomplete="off" class="layui-input" style="width:260px;"><input class="hide" type="text" name="id" value="'+id+'"></div>';
        html +='</div></form>';

        layer.open({
            skin: 'changepwd',
            type:1,
            title:'????????????',
            area:['501px','213px'],
            content:html,
            btn:['??????','??????'],
            yes:function(){

                $("#pass").ajaxSubmit({
                    url:YMWL_ROOT_URL+"/admin/manager/changePwd",
                    type: 'post',
                    success: function (res) {
                        if(res.code == 0){

                            layer.msg(res.msg, {icon: 1,time:2000,end:function () {
                                    location.reload();
                                }});

                        }else{
                            layer.msg(res.msg, {icon: 0});
                        }

                    }
                });


            }
        });
    }

    function setofflinefirst(id,type) {
        $.ajax({
            url:YMWL_ROOT_URL+'/admin/set/setofflinefirst',
            type: 'post',
            data: {id: id,type:type},
            success: function (res) {
                if (res.code == 0) {
                    if(res.data){
                        layer.msg('????????????', {icon: 1,time:2000,end:function () {
                                location.reload();
                            }});
                    }

                }
            }
        });
    }


    function add(){
    layer.open({
        type:2,
        title:'????????????',
        area:['600px','600px'],
        content:YMWL_ROOT_URL+'/admin/manager/add'
    });
}


    </script>
</div>
<script type="text/javascript" src="/assets/js/video.js?v=LK_DIY6.0.3"></script>
<script>    wolive_connect();

</script>
<script type="text/javascript" src="/assets/js/80zxcom.js?v=LK_DIY6.0.3"></script>
</body>
</html>