var SIGN_REGEXP = /([yMdhsm])(\1*)/g
var DEFAULT_PATTERN = 'yyyy-MM-dd'
function padding(s, len) {
  var len = len - (s + '').length
  for (var i = 0; i < len; i++) { s = '0' + s }
    return s
}
/**
 * 在指定元素加上倒计时
 * @param  {[type]} ele      [DOM元素]
 * @param  {[type]} seconds [倒计时的秒数]
 * @return {[type]}        [description]
 */
 export function countdown (ele, seconds) {
  var timer
  var originTxt = ele.innerHTML
  timer = setInterval(function () {
    seconds--
    if (seconds < 1) {
      ele.innerHTML = originTxt
      clearInterval(timer)
      ele.disabled = false
    } else {
      ele.innerHTML = '请等' + seconds.toString() + '秒'
      ele.disabled = true
    }
  }, 1000)
}
/**
 * 获取url中的http参数
 */
 export function getQueryStringByName (name) {
  var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i")
  var r = window.location.search.substr(1).match(reg)
  var context = ""
  if (r != null) {
    context = r[2]
  }
  reg = null
  r = null
  return context == null || context == "" || context == "undefined" ? "" : context
}

/**
 * 格式化日期，常用于datepicker
 */
 export default {
  formatDate: {
    format: function (date, pattern) {
      pattern = pattern || DEFAULT_PATTERN
      return pattern.replace(SIGN_REGEXP, function ($0) {
        switch ($0.charAt(0)) {
          case 'y': return padding(date.getFullYear(), $0.length)
          case 'M': return padding(date.getMonth() + 1, $0.length)
          case 'd': return padding(date.getDate(), $0.length)
          case 'w': return date.getDay() + 1
          case 'h': return padding(date.getHours(), $0.length)
          case 'm': return padding(date.getMinutes(), $0.length)
          case 's': return padding(date.getSeconds(), $0.length)
        }
      });
    },
    parse: function (dateString, pattern) {
      var matchs1 = pattern.match(SIGN_REGEXP)
      var matchs2 = dateString.match(/(\d)+/g)
      if (matchs1.length == matchs2.length) {
        var _date = new Date(1970, 0, 1)
        for (var i = 0; i < matchs1.length; i++) {
          var _int = parseInt(matchs2[i])
          var sign = matchs1[i]
          switch (sign.charAt(0)) {
            case 'y': _date.setFullYear(_int) 
            break
            case 'M': _date.setMonth(_int - 1)
            break
            case 'd': _date.setDate(_int)
            break
            case 'h': _date.setHours(_int)
            break
            case 'm': _date.setMinutes(_int)
            break
            case 's': _date.setSeconds(_int)
            break
          }
        }
        return _date;
      }
      return null;
    }
  } 
}
