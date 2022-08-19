function getreply() {
    $.ajax({
        url: YMWL_ROOT_URL + "/admin/manager/replyinfo",
        type: 'post',
        success: function (res) {
            if (res.code == 0) {
                var str = "", index;
                $.each(res.data, function (k, v) {
                    index = k + 1;
                    str += '<div>' + index + '.' + v.tag + '<div class="replycont">' + v.word + '</div></div>';
                });
                if (str !== '') {
                    $('#quickreply').show();
                }
                domQuickreplyList.html(str);
            }

        }
    });
}

getreply();

function a() {
    var e = document.getElementById("chat-message-audio-source").src
        , b = document.getElementById("chat-message-audio");
    b.src = "";
    var p = b.play();
    p && p.then(function () {
    }).catch(function (e) {
    });
    b.src = e;
    $(document).unbind("click", a);
}

$(document).on("click", a);
var group = new Vue({
    el: '#vueWrap',
    data() {
        return {
            list: [],
            user: [],
            vid: null,
            openGroup: false,
            openUserInfo: false,
            last_login_time: '',
            from_url: '',
            ip: '',
            area: '',
            olstate: '',
            login_times: '',
            login_device: '',
            name: '',
            tel: '',
        };
    },

    methods: {
        // 请求用户数据
        getList(page) {
            let that = this;
            $.ajax({
                url: YMWL_ROOT_URL + '/admin/custom/group',
                type: 'get',
                data: {
                    page: page
                },
                success: function (res) {
                    if (res.code == 0) {
                        that.list = that.list.concat(res.data.data);
                        if (res.data.data.length == 10) {
                            that.getList(page + 1);
                        }
                        for (let i = 0; i < that.user.length; i++) {
                            for (let y = 0; y < that.list.length; y++) {
                                if (that.list[y].group_name == that.user[i]) {
                                    that.list[y].choose = true;
                                }
                            }
                        }
                    }
                }
            });
        },
        getUser() {
            let that = this;
            $.ajax({
                url: YMWL_ROOT_URL + '/admin/custom/search',
                type: 'get',
                data: {
                    group_id: 0,
                    page: 1,
                    nickname: nickname
                },
                success: function (res) {
                    if (res.code == 0) {
                        that.user = res.data.data[0].group_name_array;
                        that.vid = [res.data.data[0].vid];
                        that.getList(1);
                    }
                }
            });
        },
        // 获取访客状态
        getstatus() {
            var that=this;
    $.ajax({
        url:YMWL_ROOT_URL+'/admin/set/getstatus',
        type:'post',
        data:{visiter_id:visiter_id},
        dataType:'json',
        success:function(res){
            if(res.code ==0){
                if(res.data){
                    that.from_url=res.data.from_url;
                    that.ip=res.data.ip;
                    that.last_login_time=res.data.timestamp;
                    that.login_times=res.data.login_times;
                    that.name=res.data.name;
                    that.tel=res.data.tel;
                    that.comment=res.data.comment;
                    if(res.data.extends.os!==undefined){
                        that.login_device=res.data.extends.os + ' ' + res.data.extends.browserName;
                    }
                    if(res.data.state == 'online'){
                       that.olstate="在线";
                    }else{
                        that.olstate="离线";
                    }
                    var data = res.data.area;
                    that.area='';
                    if(data !== ''){
                        var str = "";
                        str += data[0] + " 、";
                        if(data[1]){
                            str += data[1] + " 、";
                        }
                        if(data[2]){
                            str += data[2];
                        }
                        that.area=str;
                    }

                }
            }
        }
    });
}
    , saveinfo(){
            var that=this;
    $.ajax({
        url:YMWL_ROOT_URL+'/admin/manager/saveVisiter',
        type:'post',
        data:{name:that.name,tel:that.tel,comment:that.comment,visiter_id:visiter_id},
        success:function(res){
        }
    });

},
        edit() {
            let that = this;
            let group_id = [];
            for (let i = 0; i < that.list.length; i++) {
                if (that.list[i].choose) {
                    group_id.push(that.list[i].id)
                }
            }
            $.ajax({
                url: YMWL_ROOT_URL + '/admin/custom/visitergroup',
                type: 'post',
                data: {
                    group_id: group_id,
                    vid: that.vid
                },
                success: function (res) {
                    if (res.code == 0) {
                        that.openGroup = false;
                    }
                }
            });
        }
    }
});

$(function () {
    // let height = +document.documentElement.clientHeight;
    // window.scrollTop(height);
    // $('.content').css({
    //     height: height - 144
    // });
    group.getUser();
    group.getstatus();
});
// 推送评价
var toEvaluate = function () {
    $.ajax({
        url: YMWL_ROOT_URL + '/admin/set/pushComment',
        type: 'post',
        data: {visiter_id: visiter_id},
        success: function (res) {
            if (res.code == 0) {
                var str = '';
                str += "<div class='push-evaluation'>已推送评价</div>"
                $(".conversation").append(str);
                var div = document.getElementById("wrap");
                div.scrollTop = div.scrollHeight;
            } else {
                layer.msg(res.msg, {icon: 2});
            }
        }
    });
}
//拖到黑名单
function getblack() {
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/blacklist",
        type: "post",
        data: {
            visiter_id: visiter_id
        },
        success: function (res) {

            if (res.code == 0) {
                $.cookie("cu_com", "");
            }
            layer.msg("已拖入黑名单");
        }
    });
}
var getswitch =function(){
    layer.open({
        type: 2,
        title: '转接客服列表',
        area: ['100%', '420px'],
        shade: false,
        content: YMWL_ROOT_URL+'/admin/index/service?visiter_id='+visiter_id+'&name='+se
    });
}
$(document).on('click', '.chatmsg .se_pic', function () {
    group.openGroup = true;
});
$(document).on('click', '#hdUserInfo', function () {
    group.openUserInfo = false;
});
$(document).on('click', '.showUinfo', function () {
    group.openUserInfo = true;
});

$(document).on('touchend', '.content', function () {
    $("#text_all").blur();
    $('.tool_box').css({
        display: 'none'
    });
});


var getaudio = function () {

    //音频先加载
    var audio_context;
    var recorder;
    var wavBlob;
    //创建音频
    try {
        // webkit shim
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.mediaDevices.getUserMedia;
        window.URL = window.URL || window.webkitURL;

        audio_context = new AudioContext;

        if (!navigator.getUserMedia) {
            console.log('语音创建失败');
        }
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

            //示范一个公告层
            layui.use(['jquery', 'layer'], function () {
                var layer = layui.layer;

                layer.msg('录音中...', {
                    icon: 16,
                    shade: 0.01,
                    skin: 'layui-layer-lan',
                    time: 0 //20s后自动关闭
                    ,
                    btn: ['发送', '取消'],
                    yes: function (index, layero) {
                        //按钮【按钮一】的回调
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

                                    voicemessage = '<div style="cursor:pointer;text-align:center;" onclick="getstate(this)" data="play"><audio src="' + jsonObject.data.src + '"></audio><i class="layui-icon" style="font-size:25px;">&#xe652;</i><p>音频消息</p></div>';

                                    var sid = $('#channel').text();
                                    var pic = $("#se_avatar").attr('src');
                                    var time;

                                    var sdata = $.cookie('cu_com');

                                    if (sdata) {
                                        var json = $.parseJSON(sdata);
                                        var img = json.avater;

                                    }

                                    if ($.cookie("time") == "") {
                                        var myDate = new Date();
                                        time = myDate.getHours() + ":" + myDate.getMinutes();
                                        var timestamp = Date.parse(new Date());
                                        $.cookie("time", timestamp / 1000);

                                    } else {

                                        var timestamp = Date.parse(new Date());

                                        var lasttime = $.cookie("time");
                                        if ((timestamp / 1000 - lasttime) > 30) {
                                            var myDate = new Date(timestamp * 1000);
                                            time = myDate.getHours() + ":" + myDate.getMinutes();
                                        } else {
                                            time = "";
                                        }

                                        $.cookie("time", timestamp / 1000);
                                    }
                                    var str = '';
                                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                                    str += '<div style="float: right;"><img  class="my-circle se_pic" src="' + pic + '" width="50px" height="50px"></div>';
                                    str += "<div class='outer-right'><div class='service'>";
                                    str += "<pre>" + voicemessage + "</pre>";
                                    str += "</div></div>";
                                    str += "</li>";

                                    $(".conversation").append(str);
                                    $("#text_all").empty();

                                    var div = document.getElementById("wrap");
                                    div.scrollTop = div.scrollHeight;

                                    $("img[src*='upload/images']").parent().parent('.customer').css({
                                        padding: '0', borderRadius: '0'
                                    });
                                    $("img[src*='upload/images']").parent().parent('.service').css({
                                        padding: '0', borderRadius: '0'
                                    });
                                    setTimeout(function () {
                                        $('.chatmsg').css({
                                            height: 'auto'
                                        });
                                    }, 0)
                                    $.ajax({
                                        url: YMWL_ROOT_URL + "/admin/set/chats",
                                        type: "post",
                                        data: {visiter_id: visiter_id, content: voicemessage, avatar: img}
                                    });
                                }
                            };
                            xhr.open('POST', '/admin/event/uploadVoice');
                            xhr.send(fd);
                        });
                        recorder.clear();
                        layer.close(index);
                    },
                    btn2: function (index, layero) {
                        //按钮【按钮二】的回调
                        recorder && recorder.stop();
                        recorder.clear();
                        audio_context.close();
                        layer.close(index);
                    }
                });

            });
        } else {

            layer.msg('音频输入只支持https协议！');
        }

    }, function (e) {
        layer.msg('音频输入只支持https协议！');
    });
}

var getstate = function (obj) {

    var c = obj.children[0];

    var state = $(obj).attr('data');

    if (state == 'play') {
        c.play();
        $(obj).attr('data', 'pause');
        $(obj).find('i').html("&#xe651;");

    } else if (state == 'pause') {
        c.pause();
        $(obj).attr('data', 'play');
        $(obj).find('i').html("&#xe652;");
    }

    c.addEventListener('ended', function () {
        $(obj).attr('data', 'play');
        $(obj).find('i').html("&#xe652;");

    }, false);
}

var back = function () {
    history.go(-1);
}

var init = function () {
    // 获取历史消息
    $.cookie("hid", '');
    getwatch(visiter_id);
    wolive_connect();
}

function revoke(id, type) {
    $.ajax({
        url: YMWL_ROOT_URL + "/admin/set/revokemsg",
        type: "post",
        data: {id: id, type: type},
        dataType: 'json',
        success: function (res) {
            if (res.code == 0) {
                layer.msg(res.msg, {
                    icon: 1, end: function () {
                        $("#xiaox_" + id).remove();
                    }
                });
            } else {
                layer.msg(res.msg, {icon: 2});
            }
        }
    });
}


function getdata() {

    var showtime = "";
    var curentdata = new Date();
    var time = curentdata.toLocaleDateString();

    if ($.cookie("hid") != "") {
        var cid = $.cookie("hid");
    } else {
        var cid = "";

    }
    $.ajax({
        url: YMWL_ROOT_URL + "/weixin/chat/chatdata",
        type: "post",
        data: {visiter_id: visiter_id, hid: cid},
        success: function (res) {
            //添加 最近的 聊天 记录
            if (res.code == 0) {
                var str = '';
                if (!res.data.length) {
                    return;
                }
                $.each(res.data, function (k, v) {
                    if (getdata.puttime) {

                        if ((v.timestamp - getdata.puttime) > 60) {
                            var myDate = new Date(v.timestamp * 1000);
                            var puttime = myDate.toLocaleDateString();
                            let year = myDate.getFullYear();
                            let month = myDate.getMonth() + 1;
                            let date = myDate.getDate();
                            let hours = myDate.getHours();
                            let minutes = myDate.getMinutes();
                            if (hours < 10) {
                                minutes = minutes.toString();
                            }
                            if (minutes < 10) {
                                minutes = '0' + minutes.toString();
                            }

                            if (puttime == time) {
                                showtime = hours + ":" + minutes;
                            } else {
                                showtime = year + "-" + month + "-" + date + " " + hours + ":" + minutes;
                            }

                        } else {
                            showtime = "";
                        }

                    } else {

                        var myDate = new Date(v.timestamp * 1000);
                        var puttime = myDate.toLocaleDateString();
                        let year = myDate.getFullYear();
                        let month = myDate.getMonth() + 1;
                        let date = myDate.getDate();
                        let hours = myDate.getHours();
                        let minutes = myDate.getMinutes();
                        if (hours < 10) {
                            minutes = minutes.toString();
                        }
                        if (minutes < 10) {
                            minutes = '0' + minutes.toString();
                        }

                        if (puttime == time) {
                            showtime = hours + ":" + minutes;
                        } else {
                            showtime = year + "-" + month + "-" + date + " " + hours + ":" + minutes;
                        }

                    }

                    getdata.puttime = v.timestamp;

                    if (v.content.indexOf('target="_blank') > -1) {
                        v.content = v.content.replace(/alt="">/g, 'alt=""></a>')
                    }
                    if (v.direction == 'to_service') {


                        str += '<li class="chatmsg"><div class="showtime">' + showtime + '</div><div class="" style="position: absolute;left:12px;">';
                        str += '<img class="my-circle  se_pic" src="' + v.avatar + '" ></div>';
                        str += "<div class='outer-left'><div class='customer'>";
                        str += "<pre>" + v.content + "</pre>";
                        str += "</div></div>";
                        str += "</li>";

                    } else {

                        str += '<li class="chatmsg" id="xiaox_' + v.cid + '"><div class="showtime">' + showtime + '</div>';
                        str += '<div class="" style="position: absolute;top: 26px;right: 5px;"><img  class="my-circle cu_pic" src="' + v.avatar + '" ></div>';
                        str += "<div class='outer-right'><div class='service'>";
                        str += "<pre>" + v.content + "&nbsp;&nbsp;<span onclick='revoke(" + v.cid + ",1);' class='revoke-text'>(撤销)</span></pre>";
                        str += "</div></div>";
                        str += "</li>";


                    }
                });
                var div = document.getElementById("wrap");
                if ($.cookie("hid") == "") {
                    $(".conversation").append(str);
                    if (div) {
                        $("img").load(function () {
                            div.scrollTop = div.scrollHeight;
                        });
                    }
                } else {

                    $(".conversation").prepend(str);
                    if (res.length <= 2) {
                        $("#top_div").remove();
                        $(".conversation").prepend("<div id='top_div' class='showtime'>已没有数据</div>");
                        if (div) {
                            div.scrollTop = 0;
                        }
                    } else {
                        if (div) {
                            div.scrollTop = div.scrollHeight / 4.2;
                        }
                    }
                }

                $("img[src*='upload/images']").parent().parent('.customer').css({
                    padding: '0', borderRadius: '0'
                });
                $("img[src*='upload/images']").parent().parent('.service').css({
                    padding: '0', borderRadius: '0'
                });
                setTimeout(function () {
                    $('.chatmsg').css({
                        height: 'auto'
                    });
                }, 0)
                if (res.data.length > 2) {


                    $.cookie("hid", res.data[0]['cid']);
                    $(".chatmsg_notice").remove();
                }
            }
        }
    });
}

window.onload = init();


var e = {
    '羊驼': 'emo_01',
    '神马': 'emo_02',
    '浮云': 'emo_03',
    '给力': 'emo_04',
    '围观': 'emo_05',
    '威武': 'emo_06',
    '熊猫': 'emo_07',
    '兔子': 'emo_08',
    '奥特曼': 'emo_09',
    '囧': 'emo_10',
    '互粉': 'emo_11',
    '礼物': 'emo_12',
    '微笑': 'emo_13',
    '嘻嘻': 'emo_14',
    '哈哈': 'emo_15',
    '可爱': 'emo_16',
    '可怜': 'emo_17',
    '抠鼻': 'emo_18',
    '吃惊': 'emo_19',
    '害羞': 'emo_20',
    '调皮': 'emo_21',
    '闭嘴': 'emo_22',
    '鄙视': 'emo_23',
    '爱你': 'emo_24',
    '流泪': 'emo_25',
    '偷笑': 'emo_26',
    '亲亲': 'emo_27',
    '生病': 'emo_28',
    '太开心': 'emo_29',
    '白眼': 'emo_30',
    '右哼哼': 'emo_31',
    '左哼哼': 'emo_32',
    '嘘': 'emo_33',
    '衰': 'emo_34',
    '委屈': 'emo_35',
    '呕吐': 'emo_36',
    '打哈欠': 'emo_37',
    '抱抱': 'emo_38',
    '怒': 'emo_39',
    '问号': 'emo_40',
    '馋': 'emo_41',
    '拜拜': 'emo_42',
    '思考': 'emo_43',
    '汗': 'emo_44',
    '打呼': 'emo_45',
    '睡': 'emo_46',
    '钱': 'emo_47',
    '失望': 'emo_48',
    '酷': 'emo_49',
    '好色': 'emo_50',
    '生气': 'emo_51',
    '鼓掌': 'emo_52',
    '晕': 'emo_53',
    '悲伤': 'emo_54',
    '抓狂': 'emo_55',
    '黑线': 'emo_56',
    '阴险': 'emo_57',
    '怒骂': 'emo_58',
    '心': 'emo_59',
    '伤心': 'emo_60'
};

var faceon = function () {
    $(".wl_faces_main").empty();
    var str = ""
    str += '<ul>';
    str += '<li><img title="羊驼" src="/upload/emoji/emo_01.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="神马" src="/upload/emoji/emo_02.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="浮云" src="/upload/emoji/emo_03.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="给力" src="/upload/emoji/emo_04.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="围观" src="/upload/emoji/emo_05.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="威武" src="/upload/emoji/emo_06.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="熊猫" src="/upload/emoji/emo_07.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="兔子" src="/upload/emoji/emo_08.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="奥特曼" src="/upload/emoji/emo_09.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="囧" src="/upload/emoji/emo_10.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="互粉" src="/upload/emoji/emo_11.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="礼物" src="/upload/emoji/emo_12.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="微笑" src="/upload/emoji/emo_13.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="嘻嘻" src="/upload/emoji/emo_14.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="哈哈" src="/upload/emoji/emo_15.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="可爱" src="/upload/emoji/emo_16.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="可怜" src="/upload/emoji/emo_17.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="抠鼻" src="/upload/emoji/emo_18.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="吃惊" src="/upload/emoji/emo_19.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="害羞" src="/upload/emoji/emo_20.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="调皮" src="/upload/emoji/emo_21.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="闭嘴" src="/upload/emoji/emo_22.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="鄙视" src="/upload/emoji/emo_23.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="爱你" src="/upload/emoji/emo_24.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="流泪" src="/upload/emoji/emo_25.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="偷笑" src="/upload/emoji/emo_26.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="亲亲"  src="/upload/emoji/emo_27.gif"  onclick="emoj(this)"/></li>';
    str += '<li><img title="生病" src="/upload/emoji/emo_28.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="太开心" src="/upload/emoji/emo_29.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="白眼" src="/upload/emoji/emo_30.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="右哼哼" src="/upload/emoji/emo_31.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="左哼哼" src="/upload/emoji/emo_32.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="嘘" src="/upload/emoji/emo_33.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="衰" src="/upload/emoji/emo_34.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="委屈" src="/upload/emoji/emo_35.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="呕吐" src="/upload/emoji/emo_36.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="打哈欠" src="/upload/emoji/emo_37.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="抱抱" src="/upload/emoji/emo_38.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="怒" src="/upload/emoji/emo_39.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="问号" src="/upload/emoji/emo_40.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="馋" src="/upload/emoji/emo_41.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="拜拜" src="/upload/emoji/emo_42.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="思考" src="/upload/emoji/emo_43.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="汗" src="/upload/emoji/emo_44.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="打呼" src="/upload/emoji/emo_45.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="睡" src="/upload/emoji/emo_46.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="钱" src="/upload/emoji/emo_47.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="失望" src="/upload/emoji/emo_48.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="酷" src="/upload/emoji/emo_49.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="好色" src="/upload/emoji/emo_50.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="生气" src="/upload/emoji/emo_51.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="鼓掌" src="/upload/emoji/emo_52.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="晕" src="/upload/emoji/emo_53.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="悲伤" src="/upload/emoji/emo_54.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="抓狂" src="/upload/emoji/emo_55.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="黑线" src="/upload/emoji/emo_56.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="阴险" src="/upload/emoji/emo_57.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="怒骂" src="/upload/emoji/emo_58.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="心" src="/upload/emoji/emo_59.gif" onclick="emoj(this)"/></li>';
    str += '<li><img title="伤心" src="/upload/emoji/emo_60.gif" onclick="emoj(this)"/></li>';
    str += "</ul>";
    $(".wl_faces_main").append(str);
    $(".tool_box").toggle();
    var e = window.event || arguments.callee.caller.arguments[0];
    e.stopPropagation();
}

$('body').click(function () {
    $(".tool_box").hide();
});

var emoj = function (obj) {
    var a = $(obj).attr("title");
    var str = $("#text_all").val();
    var reg = new RegExp('<', "g")
    str = str.replace(reg, '&lt;');

    var reg2 = new RegExp('>', "g")

    str = str.replace(reg2, '&gt;');
    var b = "";
    b += str + " face[" + a + "]";
    $("#text_all").val(b);
    $(".tool_box").hide()

}

// 图片上传
function put() {

    var value = $('input[name="upload"]').val();
    var index1 = value.lastIndexOf(".");
    var index2 = value.length;
    var suffix = value.substring(index1 + 1, index2);
    var debugs = suffix.toLowerCase();

    if (debugs == "jpg" || debugs == "gif" || debugs == "png" || debugs == "jpeg") {

        $("#picture").ajaxSubmit({
            url: YMWL_ROOT_URL + '/admin/set/upload',
            type: "post",
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {

                    var msg = '<img style="height:100px" src="' + res.data + '"  >';

                    var se = $("#chatmsg_submit").attr('name');
                    var customer = $("#customer").text();
                    var time;

                    if ($.cookie("time") == "") {
                        var myDate = new Date();
                        time = myDate.getHours() + ":" + myDate.getMinutes();
                        var timestamp = Date.parse(new Date());
                        $.cookie("time", timestamp / 1000);

                    } else {

                        var timestamp = Date.parse(new Date());

                        var lasttime = $.cookie("time");
                        if ((timestamp / 1000 - lasttime) > 30) {
                            var myDate = new Date(timestamp);
                            time = myDate.getHours() + ":" + myDate.getMinutes();
                        } else {
                            time = "";
                        }

                        $.cookie("time", timestamp / 1000);

                    }
                    var str = '';
                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                    str += '<div style="float: right;"><img  class="my-circle se_pic" src="' + imghead + '" width="50px" height="50px"></div>';
                    str += "<div class='outer-right'><div class='service'>";
                    str += "<pre>" + msg + "</pre>";
                    str += "</div></div>";
                    str += "</li>";

                    $(".conversation").append(str);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                    $("img[src*='upload/images']").parent().parent('.customer').css({
                        padding: '0', borderRadius: '0'
                    });
                    $("img[src*='upload/images']").parent().parent('.service').css({
                        padding: '0', borderRadius: '0'
                    });

                    setTimeout(function () {
                        $('.chatmsg').css({
                            height: 'auto'
                        });
                    }, 0)
                    $.ajax({
                        url: YMWL_ROOT_URL + "/admin/set/chats",
                        type: "post",
                        data: {visiter_id: visiter_id, content: msg, avatar: img},
                        success: function (res) {
                            if (res.code != 0) {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }
                    });
                } else {
                    layer.msg(res.msg, {icon: 2});
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

    if (arr[1] == "js" || arr[1] == "css" || arr[1] == "html" || arr[1] == "php") {
        layer.msg("不支持该格式的文件", {icon: 2});

    } else {

        var myDate = new Date();
        var time = myDate.getHours() + ":" + myDate.getMinutes();

        $("#file").ajaxSubmit({
            url: YMWL_ROOT_URL + '/admin/set/uploadfile',
            type: 'post',
            datatype: 'json',
            success: function (res) {
                if (res.code == 0) {
                    var str = '';
                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                    str += '<div class="" style="float: right;"><img  class="my-circle cu_pic" src="' + imghead + '" ></div>';
                    str += "<div class='outer-right'><div class='service'>";
                    str += "<pre><div style='height:90px'>";
                    str += "<a href='" + res.data + "' style='display: inline-block;text-align: center;min-width: 70px;text-decoration: none;' download='" + name + "'><i class='layui-icon' style='font-size: 60px;'>&#xe61e;</i><br>" + name + "</a>";
                    str += "</div></pre>";
                    str += "</div></div>";
                    str += "</li>";

                    $(".conversation").append(str);
                    var div = document.getElementById("wrap");
                    div.scrollTop = div.scrollHeight;
                    $("img[src*='upload/images']").parent().parent('.customer').css({
                        padding: '0', borderRadius: '0'
                    });
                    $("img[src*='upload/images']").parent().parent('.service').css({
                        padding: '0', borderRadius: '0'
                    });

                    setTimeout(function () {
                        $('.chatmsg').css({
                            height: 'auto'
                        });
                    }, 0)
                    var msg = "<div><a href='" + res.data + "' style='display: inline-block;text-align: center;min-width: 70px;text-decoration: none;' download='" + name + "'><i class='layui-icon' style='font-size: 60px;'>&#xe61e;</i><br>" + name + "</a></div>";


                    var se = $("#chatmsg_submit").attr('name');
                    var customer = $("#customer").text();
                    $.ajax({
                        url: YMWL_ROOT_URL + "/admin/set/chats",
                        type: "post",
                        data: {visiter_id: visiter_id, content: msg, avatar: img}
                    });
                } else {
                    layer.msg(res.msg, {icon: 2});
                }

            }
        });

    }
}

function randomChar(l) {
    var x = "123456789poiuytrewqasdfghjklmnbvcxzQWERTYUIPLKJHGFDSAZXCVBNM";
    var tmp = "";
    for (var i = 0; i < l; i++) {
        tmp += x.charAt(Math.ceil(Math.random() * 10000000000) % x.length);
    }
    return tmp;
}

var timestamp, myDate;
//发送消息
var send = function () {
    //获取 游客id
    var msg = $("#text_all").val();
    var reg = new RegExp('<', "g")
    msg = msg.replace(reg, '&lt;');
    var reg2 = new RegExp('>', "g")
    msg = msg.replace(reg2, '&gt;');
    if (msg.indexOf("face[") != -1) {
        msg = msg.replace(/face\[([^\s\[\]]+?)\]/g, function (i) {
            var a = i.replace(/^face/g, "");
            a = a.replace('[', '');
            a = a.replace(']', '');
            return '<img src="/upload/emoji/' + e[a] + '.gif"/>'
        });

    }

    if (msg == "") {
        layer.msg('请输入信息');
    } else {
        sendContent(msg);
    }
}

function sendContent(msg) {

    var se = $("#chatmsg_submit").attr('name');
    var customer = $("#customer").text();
    var time;

    if ($.cookie("time") == "") {
        var myDate = new Date(timestamp);
        let year = myDate.getFullYear();
        let month = myDate.getMonth() + 1;
        let date = myDate.getDate();
        let hours = myDate.getHours();
        let minutes = myDate.getMinutes();
        if (hours < 10) {
            minutes = '0' + minutes.toString();
        }
        if (minutes < 10) {
            minutes = '0' + minutes.toString();
        }
        time = year + "-" + month + "-" + date + " " + hours + ":" + minutes;
        timestamp = Date.parse(new Date());
        $.cookie("time", timestamp / 1000);

    } else {

        timestamp = Date.parse(new Date());

        var lasttime = $.cookie("time");
        if ((timestamp / 1000 - lasttime) > 30) {
            myDate = new Date(timestamp);
            let hours = myDate.getHours();
            let minutes = myDate.getMinutes();
            if (hours < 10) {
                minutes = '0' + minutes.toString();
            }
            if (minutes < 10) {
                minutes = '0' + minutes.toString();
            }
            time = hours + ":" + minutes;
        } else {
            time = "";
        }

        $.cookie("time", timestamp / 1000);

    }
    var unstr = (new Date()).valueOf() + randomChar(5) + visiter_id;
    var str = '';
    str += '<li class="chatmsg" id="xiaox_' + unstr + '"><div class="showtime">' + time + '</div>';
    str += '<div style="float: right;"><img  class="my-circle se_pic" src="' + imghead + '" ></div>';
    str += "<div class='outer-right'><div class='service'>";
    str += "<pre>" + msg + "&nbsp;&nbsp;<span onclick=revoke('" + unstr + "',2); class='revoke-text'>(撤销)</span></pre>";
    str += "</div></div>";
    str += "</li>";
    $(".conversation").append(str);
    $("#text_all").val('');
    var div = document.getElementById("wrap");
    $("img").load(function () {
        div.scrollTop = div.scrollHeight;
    });
    $("img[src*='upload/images']").parent().parent('.customer').css({
        padding: '0', borderRadius: '0'
    });
    $("img[src*='upload/images']").parent().parent('.service').css({
        padding: '0', borderRadius: '0'
    });

    setTimeout(function () {
        $('.chatmsg').css({
            height: 'auto'
        });
    }, 0)
    $.ajax({
        url: YMWL_ROOT_URL + "/admin/set/chats",
        type: "post",
        data: {visiter_id: visiter_id, content: msg, avatar: img, unstr: unstr}
    });
}

document.getElementById("wrap").onscroll = function () {
    var t = document.getElementById("wrap").scrollTop;
    if (t == 0) {
        if ($.cookie("hid") != "") {
            console.log(t);
            getdata();
        }
    }
}


var text = document.getElementById('text_all');
// 获取焦点，拉到底部
text.onfocus = function () {
    $(".tool_box").hide();
    let height = +document.documentElement.clientHeight;
    setTimeout(function () {
        $('html ,body').animate({scrollTop: height}, 0);
    }, 200)
}
// 失去焦点，拉到顶部
text.onblur = function () {
    setTimeout(function () {
        $('html ,body').animate({scrollTop: 0}, 0);
    }, 0)
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

function showBigImg(nWidth, nHeight, text) {
    var maxwidth = window.innerWidth;
    var maxheight = window.innerHeight;
    var size;
    if ((nHeight > maxheight - 10) || (nWidth > maxwidth - 10)) {
        var widths, heights;
        widths = maxwidth - 30;
        heights = widths * nHeight / nWidth;
        if (heights > maxheight) {
            heights = maxheight - 60;
            widths = heights * nWidth / nHeight;
        }
        size = [widths + 'px', heights + 'px'];
    } else {
        size = [nWidth + 'px', nHeight + 'px'];
    }
    layer.open({
        type: 1,
        title: false,
        closeBtn: 1,
        area: size,
        skin: 'layui-layer-nobg', //没有背景色
        shadeClose: true,
        content: "<img src='" + text + "' style='width:100%;height:100%;'>"
    });
}

$(document).on('click', '.outer-left .customer img,.outer-right .service img', function (e) {
    var that = this;
    var img = new Image();
    img.src = this.src
    // 如果图片被缓存，则直接返回缓存数据
    if (img.complete) {
        var nWidth = img.width;
        var nHeight = img.height;
        if (this.width < nWidth || this.height < nHeight) {
            e.preventDefault();
            showBigImg(nWidth, nHeight, img.src);
        }
    } else {
        img.onload = function () {
            var nWidth = img.width;
            var nHeight = img.height;
            if (that.width < nWidth || that.height < nHeight) {
                e.preventDefault();
                showBigImg(nWidth, nHeight, img.src);
            }
        }
    }
});