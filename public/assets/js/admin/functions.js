/**
 * 解析Ajax请求获取的HTML内容
 * @param data
 * @returns {*}
 */
var parse_html = function (data) {
    var pattern = /<body[^>]*>((.|[\n\r])*)<\/body>/im;
    var matches = pattern.exec(data);
    if (matches) {
        return matches[1];
    }
    return data;
};
/**
 * 扩展函数库
 * Created by PhpStorm.
 * @version 2016-07-25 14:46:29
 * @author  cbwfree
 */

/**
 * 解析URL
 * @param {string} url 完整的URL地址
 * @returns {object} 自定义的对象
 */
function parse_url(url) {
    var a = document.createElement('a');
    a.href = url;
    return {
        protocol: a.protocol.replace(':', ''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: parse_url_params(a.search),
        file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
        path: a.pathname.replace(/^([^\/])/, '/$1')
    };
}

/**
 * 解析URL参数
 * @param query
 * @returns {{}}
 */
function parse_url_params(query) {
    var ret = {},
        seg = query.replace(/^\?/, '').split('&'),
        len = seg.length, i = 0, s;
    for (; i < len; i++) {
        if (!seg[i]) {
            continue;
        }
        s = seg[i].split('=');
        ret[s[0]] = s[1];
    }
    return ret;
}

/**
 * 构建URL
 * @param url
 * @param vars
 */
function build_url(url, vars) {
    var parse = parse_url(url),
        query = parse.params || {};
    (typeof vars == "string") ? vars = parse_url_params(vars) : '';
    $.each(vars, function (k, v) {
        query[k] = v;
    });
    return parse.path + "?" + $.param(query);
}

function notify(title, options, callback) {
    // 先检查浏览器是否支持
    if (!window.Notification || document.visibilityState != 'hidden') {
        return;
    }
    var notification;
    // 检查用户曾经是否同意接受通知
    if (Notification.permission === 'granted') {
        notification = new Notification(title, options); // 显示通知

    } else if (Notification.permission === 'default') {
        var promise = Notification.requestPermission();
    }

    if (notification && callback) {
        notification.onclick = function(event) {
            callback(notification, event);
        }
    }
}
if (window.Notification && Notification.permission === 'default') {
    var promise = Notification.requestPermission();
}