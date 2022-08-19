$(function () {
    $.ajaxSetup({
        timeout: 45000, dataType: "json", error: function (xhr, type) {
            var msg = [];
            msg.push("[Error Code: " + xhr.status + "]");
            msg.push(xhr.statusText);
            $.AMUI.message.error(msg.join("&nbsp;"), "请求出错")
        }
    });
    layer.config({scrollbar: false, shift: 1, moveType: 1, closeBtn: false, maxWidth: 540});
    $.AMUI.pjax.listen({
        success: function (response, replace) {
            if (response.code == 1) {
                $.AMUI.pjax.display({
                    url: response.url,
                    title: "",
                    html: parse_html(response.data),
                    node: $("#layout-menus-lists .menu-item.am-active > a").attr("href") || response.url
                }, replace)
            } else {
                if (response.code == 100) {
                    $.AMUI.message.warning(response.msg, "登录失效", function () {
                        location.href = response.data
                    })
                } else {
                    $.AMUI.message.error(response.msg)
                }
            }
        }, error: function (type, xhr, url) {
            var html = [];
            html.push('<ol id="location" class="am-breadcrumb am-breadcrumb-slash">');
            html.push('<li><i class="am-icon-home"></i> 首页</li>');
            html.push('<li class="am-active">' + xhr.statusText + " " + xhr.status + "</li>");
            html.push('<li class="am-fr"><a href="javascript:;" onclick="$.AMUI.pjax.reload();" title="刷新"><i class="am-icon-refresh"></i>刷新(F5)</a></li>');
            html.push('<li class="am-fr"><a href="javascript:;" onclick="window.open($.AMUI.pjax.location());" title="新窗口打开"><i class="am-icon-external-link"></i>新窗口打开</a></li>');
            html.push("</ol>");
            html.push('<iframe id="container" name="container" style="padding:0;width:100%;"></iframe>');
            $.AMUI.pjax.display({
                url: url,
                title: "",
                html: html.join(""),
                node: $("#layout-menus-lists .menu-item.am-active > a").attr("href") || url
            }, true);
            container.document.write(xhr.responseText)
        }, before: function () {
            destroy_extend("#layout-main")
        }, complete: function () {
            apply_extend("#layout-main")
        }
    });
    $("#layout-nav").on("click", "a", function () {
        var $this = $(this);
        var $group = $($(this).data("groupMenus"));
        $("#layout-menus-lists").find("> ul").addClass("am-hide");
        $group.removeClass("am-hide").addClass("am-animation-slide-right").one($.AMUI.support.animation.end, function () {
            $group.removeClass("am-animation-slide-right")
        });
        $("#layout-nav").find("li").removeClass("am-active");
        $this.parent().addClass("am-active")
    });
    $("#layout-menus-lists").on("click", "a", function () {
        $("#layout-menus-lists").find("li").removeClass("am-active");
        $(this).closest(".menu-group").addClass("am-active");
        $(this).closest(".menu-item").addClass("am-active")
    });
    $("body").on("click", ".safe-exit", function () {
        var _this = this;
        $.AMUI.message.confirm("您确认要退出登录吗?", "安全退出", function (ok) {
            if (ok) {
                location.href = _this.href
            }
        });
        return false
    }).on("reset", "[data-validator]", function () {
        $(this).validator("destroy");
        $(this).validator()
    }).on("submit", "[data-ajax-submit]", function () {
        var $form = $(this);
        var $submit = $form.find("[type=submit]");
        var $button = $form.find(".am-btn:not([type=submit])");
        $.ajax({
            type: $form.attr("method") || "post",
            url: $form.attr("action"),
            data: $form.serialize(),
            dataType: "json",
            beforeSend: function () {
                $submit.attr("data-am-loading", "{spinner: 'circle-o-notch', loadingText: '加载中...'}").button("loading");
                $button.attr("disabled", true);
                $.AMUI.progress.start()
            },
            success: function (res) {
                if (res.code == 1) {
                    $.AMUI.message.tips(res.msg, "success");
                    $.AMUI.pjax.redirect(res["url"])
                } else {
                    $.AMUI.message.tips(res.msg, "error")
                }
            },
            complete: function () {
                $submit.button("reset");
                $button.attr("disabled", false);
                $.AMUI.progress.done()
            }
        });
        return false
    });
    validate();
    select_menus();
    apply_extend("#layout-main")
});
function apply_extend(container) {
    var $container = $(container);
    $container.find("[data-validator]").each(function () {
        $(this).validator()
    });
    $container.find("[data-datetime]").each(function () {
        var data = $(this).data();
        $(this).datetimepicker({
            showClose: true,
            showClear: true,
            showTodayButton: true,
            widgetParent: "body",
            format: data["datetime"] || "YYYY-MM-DD HH:mm:ss",
            minDate: data["min"] || false,
            maxDate: data["max"] || false,
            disabledDates: data["disabled"] || false,
            enabledDates: data["enabled"] || false
        })
    });
    $container.find("[data-select]").each(function () {
        var $this = $(this);
        var remote = $this.data("remote");
        var options = {};
        if (remote) {
            options.ajax = {
                url: remote, dataType: "json", delay: 250, cache: true, data: function (params) {
                    return {query: params.term}
                }, processResults: function (data) {
                    return {results: data.data || []}
                }
            }
        }
        $(this).select2(options)
    });
    console.log("[extends] apply ...")
}
function select_menus() {
    var url = (history.state && history.state["node"]) || location.href;
    var pathinfo = parse_url(url).path;
    if (!pathinfo || pathinfo == "/") {
        return false
    }
    var $nav = $("#layout-nav");
    var $menus = $("#layout-menus-lists");
    var $dom = $menus.find("a").filter("[href='" + pathinfo + "']:first");
    if ($dom.length) {
        var $group = $dom.closest(".group-menus");
        $nav.find("li").removeClass("am-active");
        $menus.find("> ul").addClass("am-hide");
        $menus.find("li").removeClass("am-active");
        $dom.closest(".sub-menus").addClass("am-in");
        $dom.closest(".menu-group").addClass("am-active");
        $dom.closest(".menu-item").addClass("am-active");
        $group.removeClass("am-hide");
        $nav.find("[data-group-menus='#" + $group.attr("id") + "']").parent().addClass("am-active")
    }
}
function page_search() {
    $.AMUI.pjax.request(build_url(location.href, $("#page-search").serialize()));
    return false
};