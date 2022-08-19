
var json;
var win ='';
var localstroage = window.localStorage;

function getCookie(name)
{
var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
if(arr=document.cookie.match(reg))
return unescape(arr[2]);
else
return null;
}

var getkufu =function(){

    var ka=document.getElementById("workerman-kefu");
    var user_id =ka.getAttribute("visiter_id");
    var username =ka.getAttribute("visiter_name");
    var avatar =ka.getAttribute("avatar");
    var web=ka.getAttribute("href");
    var dom=ka.getAttribute("business_id");

    var content=ka.getAttribute("product");


    json ={link:web,user_id:user_id,name:username,avatar:avatar,web_id:dom,pro:content};
    ka.parentNode.removeChild(ka);

    var domain=window.location.href;

    var channel=localstroage.getItem('channel');

    if(channel == null){
      
        var time =(Date.now()).toString(36).substr(-6);
        localstroage.setItem('channel',time);
    }


    var str="";
    str+="<div class='kefu_box' onclick='jump()'>";
    str+="</div>";

    $("body").append(str);

    $(".kefu_box").css({"z-index":"9999","text-align":"center","width":"60px","height": "60px","cursor": "pointer",'background': 'url("'+web+'/assets/images/index/im.png") no-repeat',"position": "fixed","top":"60%","right":"5%","background-size": "cover"});


}

window.onload=getkufu;

// 判读pc
function IsPC() {
    var userAgentInfo = navigator.userAgent;
    var Agents = ["Android", "iPhone",
        "SymbianOS", "Windows Phone",
        "iPad", "iPod"];
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}


var jump =function(){

   var pros =json.pro;

   var web =json.link;
   var domain=json.web_id;
   var pic=json.avatar;
   var visiter=json.name;
   var visiter_id=json.user_id;

   if(!visiter_id){
    visiter_id=localstroage.getItem('channel');
   }
   
   var url=window.location.href;

   var isPC = IsPC();

   if(isPC){

       var str='';
       str+='<form id="myform" action="'+web+'/index?avatar='+pic+'&from_url='+url+'&visiter_name='+visiter+'&visiter_id='+visiter_id+'&business_id='+domain+'" method="post"  style="display:none;"  target="_blank">';
       str+='<input type="text" name="content" id="content">';
       str+='<input id="test_pro" type="submit" >';
       str+='</form>';

   }else{

       var str='';
       str+='<form id="myform" action="'+web+'/mobile?avatar='+pic+'&from_url='+url+'&visiter_name='+visiter+'&visiter_id='+visiter_id+'&business_id='+domain+'" method="post"  style="display:none;"  target="_blank">';
       str+='<input type="text" name="content" id="content">';
       str+='<input id="test_pro" type="submit" >';
       str+='</form>';
   }  

       $("#myform").remove();
       $("body").append(str);

     if(pros){
     
        $("#content").val(pros);
        $(document).ready(function(){
         $('#test_pro').trigger('click');
        });

         var json2 =$.parseJSON(pros);
         var id =getCookie('pro');
   
         if(!id){
             document.cookie="pro="+json2.pid+visiter_id;
         }
         
        if(isPC){

           var str='';
            str+='<a href="'+json2.url+'" target="_blank" style="display:block;width:480px;height:100%;position:relative;">';
            str+='<div style="width:30%;"><img src="'+json2.img+'" width="100px"></div>';
            str+='<div style="width:70%;position:absolute;right:0px;top:0px;"><p style="margin-top:15px;">'+json2.title+'<span style="position:absolute;right:20px;color:red;">'+json2.price+'</span><p>';
            str+='<p style="margin-top:15px;">'+json2.info+'</p>';
            str+='</div></a>';

        }else{

           var str='';
            str+='<a href="'+json2.url+'" target="_blank" style="display:block;width:480px;height:100%;position:relative;">';
            str+='<div style="width:30%;"><img src="'+json2.img+'" style="width:100px;"></div>';
            str+='<div style="width:65%;height:88%;position:absolute;right:0;top:0px;;overflow-y:hidden;word-break: break-all; word-wrap: break-word;"><p style="margin-top:5px;">'+json2.title+'<span style="position:absolute;right:10px;color:red;">'+json2.price+'</span><p>';
            str+='<p style="margin-top:5px;">'+json2.info+'</p>';
            str+='</div></a>';
        }

            if(json2.pid+visiter_id != id){

                 $.ajax({
                    url: web+"/admin/event/chat",
                    type: "post",
                    data: {visiter_id:visiter_id,content: str,business_id: domain, avatar: pic,record: url,debug:'product'},
                    dataType:'json',
                    success:function(res){

                        if(res.code != 0){
                            layer.msg(res.msg,{icon:2});
                        }
                      }          
                });
            }
     }else{

        $(document).ready(function(){
         $('#test_pro').trigger('click');
        });
     }     
}