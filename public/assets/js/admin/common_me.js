
function add(data,group) {


    var value = '';
    value += '<form id="info_form"><table>';
    value += '<tr class="sp_line"><td>头像:</td><td class="am-form-group am-form-file"><img id="imgs" class="am-circle" src=' + '"' + data.avatar + '"' + ' width="50" height="50"><button type="button" class="am-btn am-btn-default am-btn-sm">选择图片</button><input type="file" name="img_head" id="img_head" multiple></td></tr>'
    value += '<tr><td>用户名:</td><td><span>'+data.user_name+'</span></td></tr>';
    value += '<tr><td>昵称:</td><td><input type="text" class="am-form-field" id="nickname" name="nickname" value=' + '"' + data.nick_name + '"' + '></td></tr>';
    value += '<tr><td>手机:</td><td><input type="text" id="phone" name="phone" class="am-form-field"  value=' + '"' + data.phone + '"' + '></td></tr>';
    value += '<tr><td>邮箱:</td><td><input type="text" id="email" name="email" class="am-form-field" value=' + '"' + data.email + '"' + '></td></tr>';
    value += '<tr><td>网站id:</td><td><span> '+data.business_id+' </span><input type="text" name="id" class="hide" value=' + '"' + data.service_id + '"' + '></td></tr>';
    value += '<tr><td>客服分组:</td><td><select id="classification" name="groupid" style="width: 200px;height: 38px;border-color: #ddd; ">';
    if(data.groupid == 0){
        value +='<option value="0" selected="selected">通用客服</option>';
    }
    $.each(group,function(k,v){
       if(v.id == data.groupid){
          value +='<option value="'+v.id+'" selected="selected">'+v.groupname+'</option>';
       } else{
      
          value +='<option value="'+v.id+'">'+v.groupname+'</option>';
       }
    });
    value +='<option value="0">通用客服</option>';
    value +='</td></tr>';
    value += '</table></form>';
    value += '<script>';
    value += 'function getObjectURL(file){var url =null;if(window.createObjectURL != undefined){url =window.createObjectURL(file);} else if(window.URL!=undefined){url = window.URL.createObjectURL(file);} else if(window.webkitURL!=undefined){url = window.webkitURL.createObjectURL(file);}return url;}$("#img_head").change(function(){var objUrl =getObjectURL(this.files[0]);console.log("objUrl="+objUrl);if(objUrl){ $("#imgs").attr("src",objUrl);}});';
    value += '</script>';
    layer.open({
        type: 1,
        title: '个人信息表',
        area: ['600px', '500px'],
        content: value,
        btn: ['修改', '取消'],
        yes: function () {
            $("#info_form").ajaxSubmit({
                url:YMWL_ROOT_URL+"/admin/manager/update",
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




/**
 * 个人信息 的弹窗
 */

function showinfo(data,group) {


    var value = '';
    value += '<form id="info_form"><table>';
    value += '<div class="sp_line info_form_item"><label>头像</label><div class="am-form-group am-form-file"><img id="imgs" class="am-circle" src=' + '"' + data.avatar + '"' + ' width="50" height="50"><button type="button" class="am-btn am-btn-default choose-img am-btn-sm">重新上传</button><input type="file" name="img_head" id="img_head" multiple></div></div>'
    value += '<div class="info_form_item"><label>用户名</label><span><span>'+data.user_name+'</span></span></div>';
    value += '<div class="info_form_item"><label>OpenId</label><span><input type="text" id="open_id" name="open_id" class="am-form-field"  value=' + '"' + data.open_id + '"' + '></span></div>';
    value += '<div class="info_form_item"><label>昵称</label><span><input type="text" class="am-form-field" id="nickname" name="nickname" value=' + '"' + data.nick_name + '"' + '></span></div>';
    value += '<div class="info_form_item"><label>手机</label><span><input type="text" id="phone" name="phone" class="am-form-field"  value=' + '"' + data.phone + '"' + '></span></div>';
    value += '<div class="info_form_item"><label>邮箱</label><span><input type="text" id="email" name="email" class="am-form-field" value=' + '"' + data.email + '"' + '></span></div>';
    value += '<div class="info_form_item"><label>网站id</label><span><span> '+data.business_id+' </span><input type="text" name="id" class="hide" value=' + '"' + data.service_id + '"' + '></span></div>';
    value += '<div class="info_form_item"><label>客服分组</label><span><select id="classification" name="groupid">';
    if(data.groupid == 0){
        value +='<option value="0" selected="selected">通用客服</option>';
    }
    $.each(group,function(k,v){
       if(v.id == data.groupid){
          value +='<option value="'+v.id+'" selected="selected">'+v.groupname+'</option>';
       } else{
      
          value +='<option value="'+v.id+'">'+v.groupname+'</option>';
       }
    });
    value +='<option value="0">通用客服</option>';
    value +='</span></div>';
    value += '</table></form>';
    value += '<script>';
    value += 'function getObjectURL(file){var url =null;if(window.createObjectURL != undefined){url =window.createObjectURL(file);} else if(window.URL!=undefined){url = window.URL.createObjectURL(file);} else if(window.webkitURL!=undefined){url = window.webkitURL.createObjectURL(file);}return url;}$("#img_head").change(function(){var objUrl =getObjectURL(this.files[0]);console.log("objUrl="+objUrl);if(objUrl){ $("#imgs").attr("src",objUrl);}});';
    value += '</script>';
    layer.open({
        type: 1,
        title: '个人信息表',
        area: ['600px', 'auto'],
        content: value,
        btn: ['修改', '取消'],
        yes: function () {
            $("#info_form").ajaxSubmit({
                url:YMWL_ROOT_URL+"/admin/manager/update",
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
 
var modify =function(id){
    var str ='';
        str+='<form id="pass" class="passform"><table>';
        str+='<div class="info_form_item" style="height: 36px"><label style="width: 166px;margin-bottom:0">请输入原密码：</label><span><input class="am-form-field" type="password" id="old" name="oldpass"></span></div>';
        str+='<div class="info_form_item" style="height: 36px"><label style="width: 166px;margin-bottom:0">请输入新密码：</label><span><input class="am-form-field" type="password" id="new" name="newpass"></span></div>';
        str+='<div class="info_form_item" style="height: 36px"><label style="width: 166px;margin-bottom:0">再次输入新密码：</label><span><input class="am-form-field" type="password" id="new2" name="newpass2" ></span></div>';
        str+='<div><label style="width: 166px;margin-bottom:0" class="hide"><input class="hide" type="text" name="id" value="'+id+'"><label></div>'
        str+='</table></form>';

        layer.open({
           type:1,
           title:'修改密码',
           area:['600px','316px'],
           content:str,
           btn:['保存','取消'],
           yes:function(){

             $("#pass").ajaxSubmit({
                url:YMWL_ROOT_URL+"/admin/manager/modify",
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