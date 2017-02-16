(function(){
    var utils = {
            addEvent: function(el, type, fn, capture){
                el.addEventListener(type, fn, !!capture);
            },
            removeEvent: function(el, type, fn, capture){
                el.removeEventListener(type, fn, !!capture);
            },
            winW: $(window).width(),
            winH: $(window).height(),
            noop: function(){}
        },
        support = (window.Modernizr && Modernizr.touch === true) || (function () {
            return !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
        })(),
        eventName = {
            start: support ? 'touchstart' : 'mousedown',
            move: support ? 'touchmove' : 'mousemove',
            end: support ? 'touchend' : 'mouseup'
        };


    function Swipe(el, opts){
        this.el = typeof el === 'string' ? document.querySelector(el) : el;
        this.opts = opts || {};
        this.isVertical = opts.dir == 'vertical' ? true : false;
        this.cur = opts.cur;
        this.getSlides = opts.getSlides;
        this.success = opts.success || function(){};
        this.callback = opts.callback;

        if(!this.el){
            throw Error('element is not defined..');
            return;
        }
        this.init();
    }

    Swipe.prototype = {
        init: function(){
            this.slides = this.getSlides ? this.getSlides.call(this) : this.el.querySelectorAll('li');
            $(this.slides[this.cur]).addClass('cur').siblings().removeClass('cur');
            if(this.slides.length > 1) utils.addEvent(this.el, eventName.start, this);
            this.success.call(this.el);
        },
        start: function(e){
            if(e.target.tagName == 'A') return;
            if(this.moveing){
                e.preventDefault();
                return false;
            }

            var touches = support ? e.touches[0] : e;
        
            this.data = {
                startX: touches.pageX,
                startY: touches.pageY,
                distX: 0, // 移动距离
                distY: 0,
                time: +new Date
            }

            utils.addEvent(this.el, eventName.move, this);
            utils.addEvent(this.el, eventName.end, this);
        },
        move: function(e){
            var touches = support ? e.touches[0] : e,
                data = this.data;
            
            data.distX = touches.pageX - data.startX;
            data.distY = touches.pageY - data.startY;

            this.translate(this.isVertical ? data.distY : data.distX, this.isVertical ? utils.winH : utils.winW);

            e.preventDefault();
        },
        end: function(e){
            this.animate();
            
            utils.removeEvent(this.el, eventName.move, this);
            utils.removeEvent(this.el, eventName.end, this);
        },
        reset: function(){
            console.log('reset');
        },
        animate: function(){
            var _this = this,
                data = this.data,
                duration = +new Date - data.time
                curObj = $(this.slides[this.cur]),
                nextObj = $(this.nextSlide);
                isRevert = duration < 50 || Math.abs(this.getDisValue(data.distX, data.distY)) < 60; // 确定是否触发上一个或下一个幻灯片

            $(this.nextSlide).addClass('animated');
            curObj.addClass('animated');

            if(this.isVertical){
                this.translate((data.distY > 0 ? 1 : -1 ) * utils.winH, utils.winH, isRevert)
            }else{
                this.translate((data.distX > 0 ? 1 : -1 ) * utils.winW, utils.winW, isRevert);
            }

            this.moveing = true;
            setTimeout(function(){
                if(!isRevert) _this.cur = _this.nextIndex;
                _this.moveing = false;

                $.each(_this.slides, function(i, slide){
                    $(slide).removeAttr('style').removeAttr('class')
                });
                (isRevert ? curObj : nextObj).removeAttr('style').attr('class', 'cur');

                _this.callback(_this.cur);
                
            }, 150);
        },
        getDisValue: function(x, y){
            return this.isVertical ? y : x;
        },
        /* 
         * dis: 鼠标移动距离 
         * win: 根据滑动方向得到的window的width/height
         * isRevert: 是否需要复位
        */
        translate: function(dis, win, isRevert){
            // var index = this.cur + ((this.dir == 'vertical' ? distY : distX) < 0 ? +1 : -1 );
            var isVertical = this.isVertical,
                slideLength = this.slides.length,
                dir = dis < 0 ? 1 : -1 ,
                getValue = function(n, translateType){
                    var v = n || (dir * win + (isRevert ? 0 : dis));
                    if(translateType) v -= dir * win;

                    return isVertical ? '0, '+ v +'px' : v + 'px, 0';
                },
                index = this.cur + dir,
                scale = isRevert ? 1 : (1 - Math.abs(.2 * dis / win)).toFixed(6),
                scaleV =  getValue((-dir * win * (1 - scale) / 2).toFixed(6));

            if(index < 0) index += slideLength;
            if(index > slideLength - 1) index = 0;

            this.nextIndex = index;
            this.nextSlide = this.slides[index];

            $(this.slides[index]).addClass('action').siblings().removeClass('action');

            this.nextSlide.style.webkitTransform = 'translate('+ getValue() +')';
            if(this.opts.translateType == 'noScale'){
                this.slides[this.cur].style.webkitTransform = 'translate('+ getValue(null ,true) +')';
            }else{
                this.slides[this.cur].style.webkitTransform = 'translate('+ scaleV +') scale('+ scale +')';
            }
        },
        handleEvent: function(e){
            switch(e.type){
                case 'touchstart':
                case 'mousedown':
                    this.start(e);
                    break;
                case 'touchmove':
                case 'mousemove':
                    this.move(e);
                    break;
                case 'touchend':
                case 'mouseup':
                    this.end(e);
                    break;
            }
        },
        stop: function(){
            utils.removeEvent(this.el, eventName.start, this);
        }
    }

    window.Swipe = Swipe;
    $.fn.swipe = function(opts){
        var defaults = {
            cur: 0,
            callback: function(){},
            dir: 'vertical' // vertical or horizontal
        }
        return this.each(function(){
            $(this).data('swipe', new Swipe(this, $.extend(defaults, opts)));
            return this;
        });
    }
})();