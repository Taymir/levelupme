window.addEvent('domready', function(){
    var buttons = $('switcher-content').getChildren();
    var textSlides = $('textslider').getChildren();
    var imageSlides = $('imageslider').getChildren();
    var currentSlide = 0;
    var showDuration = 7000;
    var interval;
    
    var show = function (slidenum)
    {
        if(slidenum == currentSlide)
            return;
        textSlides[currentSlide].setStyle('display', 'none');
        imageSlides[currentSlide].setStyle('display', 'none');
        buttons[currentSlide].removeClass('active');
        
        textSlides[slidenum].setStyle('display', '');
        imageSlides[slidenum].setStyle('display', '');
        textSlides[slidenum].set('opacity', .7);
        imageSlides[slidenum].set('opacity', .7);
        textSlides[slidenum].fade('in');
        imageSlides[slidenum].fade('in');
        buttons[slidenum].addClass('active');
        currentSlide = slidenum;
    }
    var showNext = function ()
    {
        if(currentSlide < imageSlides.length - 1)
            return show(currentSlide + 1);
        return show(0);
    }
    
    buttons.each(function(button, i) {
       button.addEvent('click', function(e){
           show(i);
       });
    });
    
    window.addEvent('load', function() {
        interval = showNext.periodical(showDuration);
    })
});