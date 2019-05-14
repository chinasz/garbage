(function() {
	function change() {
		var font = document.documentElement.clientWidth / 375 * 50;
		document.documentElement.style.fontSize = font + 'px';
	};
	window.addEventListener('resize', change, false);
	change();
})();
// rem 单位换算 *不可删除


$('#menuLeft .menu-left-li').click(function() {
	var i = $(this).index();
	$('#menuLeft .menu-left-li').eq(i).addClass('on').siblings().removeClass('on');
	var t = $('#menuRight').scrollTop() - $(window).width() / 375 * 50 * 1;
	$('#menuRight').scrollTop($('#menuRight .content-item').eq(i).offset().top + t);
})
//左边联动

var heightArr = [];
for (var i = 0; i < $('#menuRight .content-item').length; i++) {
	heightArr.push($('#menuRight .content-item').eq(i).offset().top);
}
// 元素相对窗口顶部偏移量存成数组


$('#menuRight').scroll(function() {
	var t = $(this).scrollTop();

	for (var i in heightArr) {
		if ((t + $(window).width() / 375 * 50 * 1) >= heightArr[i]) {
			$('#menuLeft .menu-left-li').eq(i).addClass('on').siblings().removeClass('on');
		}
	}

	$('#menuLeft').stop().animate({
		scrollTop: $('#menuLeft .menu-left-li').height() * ($('#menuLeft .menu-left-li.on').index() + 0.5) - ($(
			'#menuLeft').height() / 2)
	}, 200)
	// 左侧按钮位置垂直居中
})
//右边联动
