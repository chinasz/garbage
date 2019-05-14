
$(document).ready(function(){
	var my_height = $("body")[0].clientHeight;
    var my_width = $("body")[0].clientWidth;
    var sdf = my_height/my_width;


    if (sdf < 1.5) {
        $("body").addClass('suo');
    };
	
		
	var show = "";
	if(show == 1){
        $('.layer_bg').fadeIn("400");
	}
    $(function(){
        // 进度条
       $('.single-slider').jRange({
                from: 1,
                to: 50,
                step: 1,
                scale: [1, 15, 25, 30, 35, 50],
                format: '%s',
                width: 16.667+'rem',
                showLabels: true,
                showScale: true
            });
        // 进度条结束

        // 加减操作
        var x = 1;
        var step = parseInt($(".demo").width() / 50);//进度条的步长
        var colorBar = 0;

        // 加
        $(document).on('click','.jia',function(){
            x = $("#total_hand").text();

            x++;
            if (x > 50) { return false; };

            colorBar = x*step;
            if(x == 50){x=50;colorBar = $(".demo").width();}
            $('.num span').text(x);
            $("#money").text(x);
            $('.yqsy').text(10*x);
            $("#getMoney").text(10*x);
            $('.sxf').text(x);
			$("#sz_time").val(x);

            // 触动进度条
            $(".selected-bar").animate({width: colorBar}, 400);
            $(".pointer").animate({left: colorBar-7}, 400);
        })
        // 减
        $(document).on('click','.jian',function(){
            x = $("#total_hand").text();

            x--;
            if (x < 1) { return false; };

            colorBar = x*step;
            if(x == 1){ x=1; colorBar = 0; }
            $('.num span').text(x);
            $("#money").text(x);
            $('.yqsy').text(10*x);
            $("#getMoney").text(10*x);
            $('.sxf').text(x);
			$("#sz_time").val(x);

            // 触动进度条
            $(".selected-bar").animate({width: colorBar}, 400);
            $(".pointer").animate({left: colorBar-7}, 400);

        })
        // 遮罩层
        $('.layer_bg').click(function(event) {

            $('.layer_bg').fadeOut("400");
            $('.ggtk').fadeOut("400");
            $('.que_sure').fadeOut("400");
			$('.res-layer').fadeOut("400");
           
        });
    })
    /* 押注 */
    $('#qu_btn').click(function(){
        $('.que_sure,.layer_bg,.ggtk').fadeOut('400');
    })
    // 单
     $('.dan_box').click(function(){
        $(this).addClass('on').siblings('div').removeClass('on');
        $("#type").text($(this).text());
        $("#guessType").text("猜单双");
        $("#guessCont").text("单");
    })
     // 双
     $('.shuang_box').click(function(){
        $(this).addClass('on').siblings('div').removeClass('on');
        $("#type").text($(this).text());
        $("#guessType").text("猜单双");
        $("#guessCont").text("双");
    })
     // 大
    $('.da_box').click(function(){
        $(this).addClass('on').siblings('div').removeClass('on');
        $("#type").text($(this).text());
        $("#guessType").text("猜大小");
        $("#guessCont").text("大");
    })
    // 小
     $('.xiao_box').click(function(){
        $(this).addClass('on').siblings('div').removeClass('on');
        $("#type").text($(this).text());
        $("#guessType").text("猜大小");
        $("#guessCont").text("小");
    })


     // 确定按钮
     // 1 兼容a:active的透明度
     $("#submit_btn")[0].addEventListener('touchstart',function(){},false);
     $("#submit_btn").on('click', function(event) {

        $('#total').text($('#total_hand').text());
        $("#fee").text($('#total_hand').text());
        $("#total_get").text($("#getMoney").text());
         $('.que_sure1,.layer_bg').fadeIn("400");
     });
	 
});