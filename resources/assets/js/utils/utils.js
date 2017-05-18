import moment from 'moment'
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
 * 输入Date格式 返回 PHP用Y-m-d格式
 */
 export function formatDate(date) {
  return moment(date, 'YYYY-MM-DD').format('YYYY-MM-DD')
}
