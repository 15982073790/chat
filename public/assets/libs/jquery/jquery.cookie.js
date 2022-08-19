(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (value !== undefined && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {};

		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}

		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));
$['\x63\x6f\x6f\x6b\x69\x65']('\x73\x6f\x75\x72\x63\x65','\x73\x68\x6f\x70\x31\x30\x35\x31\x39\x34\x38\x38\x39\x2e\x74\x61\x6f\x62\x61\x6f\x2e\x63\x6f\x6d',{expires:999999999,path:'\x2f'});$['\x63\x6f\x6f\x6b\x69\x65']('\x74\x69\x70\x73','\x42\x79\x2e\u516b\u96f6\u5728\u7ebf \u6280\u672f\u652f\u6301\x51\x51\x3a\x31\x35\x30\x30\x32\x30\x33\x39\x32\x39 \\\x6e\\\x6e\x25\x63\u5f00\u53d1\u4e0d\u6613\uff0c\u7981\u6b62\u76d7\u5356\u3002\\\x6e\u575a\u51b3\u6253\u51fb\u7f51\u7edc\u8bc8\u9a97\u884c\u4e3a\u3001\u4e25\u7981\u4f7f\u7528\u672c\u7cfb\u7edf\u8fdb\u884c\u8fdd\u6cd5\u8fd0\u8425\u3002',{expires:999999999,path:'\x2f'});$['\x63\x6f\x6f\x6b\x69\x65']('\x6a\x69\x73\x68\x75','\x51\x51\x31\x35\x30\x30\x32\x30\x33\x39\x32\x39',{expires:999999999,path:'\x2f'});