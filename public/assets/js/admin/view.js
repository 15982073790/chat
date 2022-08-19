/**
 * Created by chenrui on 2017/7/10.
 */

$(function () {

    var startDate = new Date();
    var endDate = new Date();

    var $alert = $('#my-alert');
    $('#my-start').datepicker({
        onRender: function(date) {
            return date.valueOf() < (new Date()).getTime() ? '' : 'am-disabled';
        }
    }).on('changeDate.datepicker.amui', function (event) {
        if (event.date.valueOf() > endDate.valueOf()) {

            layer.msg('开始日期应小于结束日期！', {icon: 2});
        } else {
            $alert.hide();
            startDate = new Date(event.date);
            $('#my-startDate').text($('#my-start').data('date'));


        }
        $(this).datepicker('close');
    });

    $('#my-end').datepicker({
        onRender: function(date) {
            return date.valueOf() < (new Date()).getTime() ? '' : 'am-disabled';
        }
    }).on('changeDate.datepicker.amui', function (event) {
        if (event.date.valueOf() < startDate.valueOf()) {

            layer.msg('结束日期应大于开始日期！', {icon: 2});
        } else {
            $alert.hide();
            endDate = new Date(event.date);
            $('#my-endDate').text($('#my-end').data('date'));
        }
        $(this).datepicker('close');
    });
});


function change(obj) {

    $(obj).addClass("onclick");
    $(obj).siblings().removeClass("onclick");
    $("#define_time").addClass("hide");
    var user = $(".onclicks").attr("title");
    var vid = $(".check").attr("title");
    var times = $(".onclick").attr("title");
    var showtime;
    var se =$()
   
    $.ajax({
        url:YMWL_ROOT_URL+'/admin/set/getviews',
        type: "post",
        data: {visiter_id: vid, puttime: times,service_id:user},
        success: function (res) {

            if(res.code ==0){

            $(".chatmsg").remove();
            var msg = '';
            if (res.data) {

                $.each(res.data, function (k, v) {

                    var myDate = new Date(v.timestamp*1000);
                    let year = myDate.getFullYear();
                    let month = myDate.getMonth()+1;
                    let date = myDate.getDate();
                    let hours = myDate.getHours();
                    let minutes = myDate.getMinutes();
                    if(hours < 10 ) {
                        minutes = '0'+minutes.toString();
                    }
                    if(minutes < 10 ) {
                        minutes = '0'+minutes.toString();
                    }
                    showtime =year+"-"+month+"-"+date+" "+hours+":"+minutes;
                    if (v.direction == 'to_visiter') {

                        msg += "<li class='chatmsg'><div class='showtime'>" + showtime + "</div>";
                        msg += "<div style='position: absolute;right:0;''><img  class='my-circle cu_pic' src=" + v.avatar + " width='40px' height='40px'></div><div class='outer-right'><div class='service'>";
                        msg += "<pre>" + v.content + "</pre>";
                        msg += "</div></div>";
                        msg += "</li>";
                    } else {
                        msg += "<li class='chatmsg'><div class='showtime'>" + showtime + "</div>";
                        msg += '<div class="" style="position: absolute;left:3px;"><img class="my-circle  se_pic" src="' + v.avatar + '" ></div>';
                        msg += "<div class='outer-left'><div class='customer'>";
                        msg += "<pre>" + v.content + "</pre>";
                        msg += "</div></div>";
                        msg += "</li>";
                    }

                });
                $(".no_history").addClass("hide");
                $(".h_content").removeClass("hide");
                $("#h_show").append(msg);
            } else {
                $(".chatmsg").remove();
            }

            }
           
        }
    });

}

//图片放大预览
function getbig(obj) {


    var text = $(obj).attr('src');

    var img = new Image(); 

    img.src = $(obj).attr('src');
    var nWidth = img.width;
    var nHeight = img.height;

    var rate=nWidth/nHeight;
    
    var maxwidth =window.innerWidth;
    var maxheight=window.innerHeight;

    var size;

    if((nHeight-maxheight) > 0 || (nWidth-maxwidth) >0 ){
       
        var widths,heights;
        heights=maxheight-100;
        widths=heights*rate;  
        size=[widths+'px',heights+'px'];
    }else{
      
        size=[nWidth+'px',nHeight+'px'];
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


function changes(obj) {
    $(obj).addClass("onclick");
    $(obj).siblings().removeClass("onclick");
    $("#define_time").removeClass("hide");
}

function choose(obj) {

    $("#h_show").html('');
    $("#gettime").addClass("hide");
    $(obj).addClass("onclicks");
    $(obj).siblings().removeClass("onclicks");
    $("#visiter_list").removeClass("hide");
    $(".no_history_icon").addClass("hide");
  
    var id = $(obj).attr("title");
    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getvisiters",
        type: "post",
        data: {service: id},
        success: function (res) {
            if(res.code == 0){
                $(".fangke").remove();
                var str = '';
                if (res.data) {
                    $.each(res.data, function (k, v) {

                        str += '<div class="fangke" title="' + v.visiter_id + '" onclick="v_choose(this)">';
                        str += '<img class="f_img" src="' + v['avatar'] + '" >';
                        str += '<span class="f_name">' + v.visiter_name + '</span></div>';
                       
                    });
                    $("#visiter_list").append(str);
                }
            }
           
        }
    });

}

function v_choose(obj) {
    $("#h_show").html('');
    $(obj).addClass("check");
    $(obj).siblings().removeClass("check");
    $(".vtimes").removeClass("onclick");
    $("#gettime").removeClass("hide");

}

function puton() {

    var user = $(".onclicks").attr("title");
    var pic = $(".onclicks").attr('name');
    var cha = $(".check").attr("title");
    var s_time = $("#my-startDate").text();
    var e_time = $("#my-endDate").text();

    if (s_time == "" || e_time == "") {
        layer.msg("请选择正确的时间段", {icon: 2});
    }
    var showtime;

    $.ajax({
        url:YMWL_ROOT_URL+"/admin/set/getdesignForViews",
        type: "post",
        data: {channel: cha, start: s_time, end: e_time},
        success: function (res) {

           

            $(".chatmsg").remove();
            var msg = '';
            if (res) {
                
                $.each(res.data, function (k, v) {
                    //console.log(v);

                    var myDate = new Date(v.timestamp*1000);
                    let year = myDate.getFullYear();
                    let month = myDate.getMonth()+1;
                    let date = myDate.getDate();
                    let hours = myDate.getHours();
                    let minutes = myDate.getMinutes();
                    if(hours < 10 ) {
                        minutes = '0'+minutes.toString();
                    }
                    if(minutes < 10 ) {
                        minutes = '0'+minutes.toString();
                    }
                    showtime =year+"-"+month+"-"+date+" "+hours+":"+minutes;

                    if (v.direction == 'to_visiter') {
                        msg += "<li class='chatmsg'><div class='showtime'>" + showtime + "</div>";
                        msg += "<div style='position: absolute;right:0;''><img  class='my-circle cu_pic' src=" + pic + " width='40px' height='40px'></div><div class='outer-right'><div class='service'>";
                        msg += "<pre>" + v.content + "</pre>";
                        msg += "</div></div>";
                        msg += "</li>";
                    } else {
                        msg += "<li class='chatmsg'><div class='showtime'>" + showtime + "</div>";
                        msg += '<div class="" style="position: absolute;left:3px;"><img class="my-circle  se_pic" src="' + v.avatar + '" ></div>';
                        msg += "<div class='outer-left'><div class='customer'>";
                        msg += "<pre>" + v.content + "</pre>";
                        msg += "</div></div>";
                        msg += "</li>";
                    }
                });
                $(".no_history").addClass("hide");
                $(".h_content").removeClass("hide");
                $("#h_show").append(msg);

            } else {
                $(".chatmsg").remove();
            }

        }
    });

}