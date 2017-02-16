$(function(){ 
  var swipe,
        isAnimate = false;
        shopImg = $('#shopImg'),
        itemLoader = $('#itemLoader'),
        swipeCb = function(obj, index){
            obj.find('.swipe_num span').eq(index).addClass('on').siblings().removeClass('on');
        };
    var selectIndex = 0;
    $('.shopBg_1').on('click', function(){
        selectIndex = parseInt($('.hd ul .on' ).text())-1;
		$(".header").css({opacity:0});
		$(".jjr_fxd").css({opacity:0});
        isAnimate = true;
        shopImg.addClass('show');
        //if(!swipe){
            swipe = new Swipe(shopImg[0], {
                cur: selectIndex,
                dir: 'horizontal',
                translateType: 'noScale',
                callback: function(index){
                    swipeCb(shopImg, index);
                }
            });
            //shopImg.find('div').each(function(){
            //    $(this).css('background-image', 'url('+ $(this).data('url') +')');
            //});
        //}
        swipeCb(shopImg, selectIndex);
    });

    shopImg.find('ul').on('webkitAnimationEnd', function(){
        isAnimate = false;
    });

    shopImg.on('click', function(){
        if(!isAnimate){
			$(".header").css({opacity:1});
			$(".jjr_fxd").css({opacity:1});
            $(this).removeClass('show');
		
        }
    });

    $('#infoDetail').on('click', function(){
		
        $(this)[!$(this).hasClass('show-all') ? 'addClass': 'removeClass']('show-all');
    });
});	

