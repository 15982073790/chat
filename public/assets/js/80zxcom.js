var jsAjaxDialogBtn=$('.js-ajax-dialog-btn');
if (jsAjaxDialogBtn.length) {
    jsAjaxDialogBtn.on('click', function (e) {
        e.preventDefault();
        var that=this;
        $this   = $(that),
            href    = $this.data('href'),
            title    = $this.data('title'),
            refresh = $this.data('refresh'),
            msg     = $this.data('msg');
        layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '90%'],
            content: href //iframe的url
        });
    });
}
function chat2top(id,that){
    that=$(that);
    var istop=that.data('istop');
    $.ajax({
        url:'/admin/visiter/chat2top/visiter_id/'+id+'/istop/'+istop,
        dataType:"json",   //返回格式为json
        async:true,//请求是否异步，默认为异步，这也是ajax重要特性
        type:"POST",   //请求方式
        beforeSend:function(){
            //请求前的处理
            index = layer.load(2, {shade: false});
        },
        success:function(res){
            if(res.code){
                layer.msg(res.msg,{icon:1});
                that.data('istop',istop?0:1);
                that.text(istop?'取消置顶':'置顶对话');
                setclass=istop?that.addClass('layui-btn-normal').removeClass('layui-btn-danger'):that.addClass('layui-btn-danger').removeClass('layui-btn-normal');
            }else{
                layer.msg(res.msg,{icon:2});
            }
            //请求成功时处理
        },
        complete:function(){
            //请求完成的处理
            layer.close(index);
        },
        error:function(){
            //请求出错处理
        }
    });
}
//data-href="/admin/visiter/chat2top/id/'+user.id+'/istop/'+changetop+'"
var jsAjaxBtn=$('.js-ajax-btn');
if (jsAjaxBtn.length) {
    jsAjaxBtn.on('click', function (e) {
        alert(11);
        e.preventDefault();
        var that=this;var index;
        $this   = $(that),
            href    = $this.data('href'),
            title    = $this.data('title'),
            refresh = $this.data('refresh'),
            msg     = $this.data('msg');
        if(href===undefined || href==='')return;
        refresh    = refresh === undefined ? 0 : refresh;
        $.ajax({
            url:href,    //请求的url地址
            dataType:"json",   //返回格式为json
            async:true,//请求是否异步，默认为异步，这也是ajax重要特性
            type:"POST",   //请求方式
            beforeSend:function(){
                //请求前的处理
                index = layer.load(2, {shade: false});
            },
            success:function(res){
                if(res.code){
                    layer.msg(res.msg,{icon:1});
                    if(refresh){reloadPage(window);}
                }else{
                    layer.msg(res.msg,{icon:2});
                }
                //请求成功时处理
            },
            complete:function(){
                //请求完成的处理
                layer.close(index);
            },
            error:function(){
                //请求出错处理
            }
        });
        layer.open({
            type: 2,
            title: title,
            shadeClose: true,
            shade: 0.8,
            area: ['80%', '90%'],
            content: href //iframe的url
        });
    });
}
//重新刷新页面，使用location.reload()有可能导致重新提交
function reloadPage(win) {
    var location  = win.location;
    location.href = location.pathname + location.search;
}