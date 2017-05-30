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

 // export function getCookie(name) {
 // 	var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
 // 	if (arr != null) return unescape(arr[2]);
 // 	return null;
 // }

 export function isMobile (mobile) {
 	return /^1[3|4|5|7|8]\d{9}$/.test(mobile)
 }