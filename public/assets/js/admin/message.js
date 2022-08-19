/*! amazeui-layer-extend v0.0.1 | by cbwfree | Licensed under MIT | 2016-07-25T18:19:33+0800 */
(function e(t, n, r) {
    function s(o, u) {
        if (!n[o]) {
            if (!t[o]) {
                var a = typeof require == "function" && require;
                if (!u && a) {
                    return a(o, !0)
                }
                if (i) {
                    return i(o, !0)
                }
                var f = new Error("Cannot find module '" + o + "'");
                throw f.code = "MODULE_NOT_FOUND", f
            }
            var l = n[o] = {exports: {}};
            t[o][0].call(l.exports, function (e) {
                var n = t[o][1][e];
                return s(n ? n : e)
            }, l, l.exports, e, t, n, r)
        }
        return n[o].exports
    }

    var i = typeof require == "function" && require;
    for (var o = 0; o < r.length; o++) {
        s(r[o])
    }
    return s
})({
    1: [function (_dereq_, module, exports) {
        (function (global) {
            var $ = (typeof window !== "undefined" ? window["jQuery"] : typeof global !== "undefined" ? global["jQuery"] : null);
            var UI = (typeof window !== "undefined" ? window["AMUI"] : typeof global !== "undefined" ? global["AMUI"] : null);
            var ly = (typeof window !== "undefined" ? window["layer"] : typeof global !== "undefined" ? global["layer"] : null);
            var message = message || {};
            message.tips = function (content, type, timeout) {
                var icon, status;
                switch (type) {
                    case"success":
                        status = "am-alert-success";
                        icon = "am-icon-check-circle";
                        break;
                    case"warning":
                        status = "am-alert-warning";
                        icon = "am-icon-exclamation-circle";
                        break;
                    case"error":
                        status = "am-alert-danger";
                        icon = "am-icon-minus-circle";
                        break;
                    default:
                        status = "am-alert-info";
                        icon = "am-icon-info-circle"
                }
                var html = [];
                html.push('<div class="am-alert am-block ' + status + '">');
                html.push('<i class="' + icon + '"></i>&nbsp;&nbsp;' + content || "未定义消息");
                html.push('<a href="javascript:;" class="close">×</a>');
                html.push("</div>");
                return ly.msg(html.join(""), {
                    skin: "layer-message-tips",
                    offset: 50,
                    time: timeout || 3000,
                    success: function ($dom, index) {
                        $dom.find(".close").one("click", function () {
                            ly.close(index)
                        })
                    }
                })
            };
            message.alert = function (content, title, onConfirm, center) {
                var options = {};
                options.shift = 0;
                options.offset = 100;
                options.skin = "layer-message-alert";
                options.title = '<span class="am-text-primary"><i class="am-icon-info-circle"></i> ' + (title || "温馨提示") + "</span>";
                content = '<div class="am-text-primary' + (center ? "" : " am-text-center") + '">' + content + "</div>";
                onConfirm = onConfirm || function () {
                    };
                return ly.alert(content, options, function (index) {
                    onConfirm();
                    ly.close(index)
                })
            };
            message.warning = function (content, title, onConfirm, center) {
                var options = {};
                options.offset = 100;
                options.skin = "layer-message-alert";
                options.title = '<span class="am-text-warning"><i class="am-icon-exclamation-circle"></i> ' + (title || "Waring") + "</span>";
                content = '<div class="am-text-warning' + (center ? "" : " am-text-center") + '">' + content + "</div>";
                onConfirm = onConfirm || function () {
                    };
                return ly.alert(content, options, function (index) {
                    onConfirm();
                    ly.close(index)
                })
            };
            message.success = function (content, title, onConfirm, center) {
                var options = {};
                options.offset = 100;
                options.skin = "layer-message-alert";
                options.title = '<span class="am-text-success"><i class="am-icon-check-circle"></i> ' + (title || "Success") + "</span>";
                content = '<div class="am-text-success' + (center ? "" : " am-text-center") + '">' + content + "</div>";
                onConfirm = onConfirm || function () {
                    };
                return ly.alert(content, options, function (index) {
                    onConfirm();
                    ly.close(index)
                })
            };
            message.error = function (content, title, onConfirm, center) {
                var options = {};
                options.offset = 100;
                options.skin = "layer-message-alert";
                options.title = '<span class="am-text-danger"><i class="am-icon-times-circle"></i> ' + (title || "Error") + "</span>";
                content = '<div class="am-text-danger' + (center ? "" : " am-text-center") + '">' + content + "</div>";
                onConfirm = onConfirm || function () {
                    };
                return ly.alert(content, options, function (index) {
                    onConfirm();
                    ly.close(index)
                })
            };
            message.confirm = function (content, title, onConfirm, center) {
                var options = {};
                options.offset = 100;
                options.skin = "layer-message-alert";
                options.title = '<span class="am-text-warning"><i class="am-icon-question-circle"></i> ' + (title || "您想整咋样?") + "</span>";
                content = '<div class="am-text-warning' + (center ? "" : " am-text-center") + '">' + content + "</div>";
                onConfirm = onConfirm || function () {
                    };
                return ly.confirm(content, options, function (index) {
                    onConfirm(1);
                    ly.close(index)
                }, function (index) {
                    onConfirm(0);
                    ly.close(index)
                })
            };
            module.exports = UI.message = message
        }).call(this, typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
    }, {}]
}, {}, [1]);