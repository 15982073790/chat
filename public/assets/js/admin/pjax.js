/*! amazeui-pjax v0.0.1 | by cbwfree | Licensed under MIT | 2016-07-25T18:19:33+0800 */
(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(_dereq_,module,exports){
(function (global){
    'use strict';

    var $ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);
    var UI = (typeof window !== "undefined" ? window['AMUI'] : typeof global !== "undefined" ? global['AMUI'] : null);

    var pjax = pjax || {};

    pjax.defaults = {
        bind: '[data-pjax]',
        render: '#layout-main',             // 渲染输出容器
        container: '#container',            // 动画容器
        animation: [
            'am-animation-slide-right',     // 右侧划入
            'am-animation-slide-bottom',    // 底部划入
            'am-animation-slide-top'        // 顶部划入
        ],
        error: function(xhr){},
        success: function(response, replace){},
        before: function(html){},
        complete: function(){}
    };

    pjax.listen = function(options){
        pjax.defaults = $.extend({}, pjax.defaults, options);
        $(window).on('popstate', function(){
            pjax.render(history.state.html);
        });
        $("body").on("click", pjax.defaults.bind, function(){
            pjax.request($(this).data('pjax') || $(this).attr('href'), false);
            return false;
        });
        //F5刷新当前Tab & 操作快捷键
        $(document).keydown(function(e){
            var key = e.keyCode || e.which;
            if( key == 116 ){
                pjax.reload();
                return false;
            }
        });
    };

    pjax.request = function(url, replace){
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
            headers: { "X-PJAX": true },
            beforeSend: function(){
                UI.progress.start();
            },
            complete: function(){
                UI.progress.done();
            },
            error: function(xhr, type){
                pjax.defaults.error(type, xhr, url);
            },
            success: function(response) {
                response = response || {};
                response.url = url;
                pjax.defaults.success(response, typeof replace == 'undefined' ? false : replace);
            }
        });
    };

    pjax.reload = function(){
        pjax.request(history.state['url'] || location.href, true);
    };

    pjax.render = function(html){
        var $render = $(pjax.defaults.render);
        pjax.defaults.before(html);
        $render.empty().html(html);
        if (pjax.defaults.animation) {
            var $container = $("#container");
            var animation = pjax.defaults.animation[Math.floor((Math.random() * pjax.defaults.animation.length))];
            $container
                .addClass(animation)
                .one(UI.support.animation.end, function() {
                    $container.removeClass(animation);
                });
        }
        pjax.defaults.complete();
    };

    pjax.display = function(state, replace){
        state = state || {};
        state['_rand'] = Math.random();
        if(replace === true) {
            history.replaceState(state, state.title || document.title, state.url);
        }else{
            history.pushState(state, state.title || document.title, state.url);
        }
        pjax.render(state.html);
    };

    pjax.location = function() {
        return (history.state && history.state['node']) || location.href;
    };

    pjax.redirect = function(url, refresh) {
        refresh = refresh || true;
        if (url && typeof url == "string" && url.indexOf('javascript:') != 0) {
            pjax.request(url, false);
        } else if (refresh) {
            pjax.reload();
        }
    };

    module.exports = UI.pjax = pjax;

}).call(this, typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{}]},{},[1]);
