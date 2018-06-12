$(document).ready(function() {
    if($(window).width() >= 480) {
        $(window).resize(resizeScreen).resize();
    } else {
        $('div.set-section-height').removeClass('set-section-height');
        $(window).resize(resizeScreenMobile).resize();
    }
});
function resizeScreen() {
    // $('body>.container').css('margin-bottom','-5em');
    $('body>.container .main-screen').css('padding-bottom','0');
    $('.main-screen .set-section-height').outerHeight($('#footer').offset().top - $('.main-screen .set-section-height').offset().top);
    $('ul.sidebar').outerHeight($('#footer').offset().top - $('.main-screen .set-section-height').offset().top);
    $('div.sidebar').outerHeight($('#footer').offset().top - $('.main-screen-details').offset().top);
}
function resizeScreenMobile() {
    $('.main-screen-details').outerWidth($(window).width()).css('overflow-x', 'scroll');
}