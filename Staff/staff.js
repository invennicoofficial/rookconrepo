$(document).ready(function() {
    if($(window).width() >= 768) {
        $(window).resize(resizeScreen).resize();
    } else {
        $('div.set-section-height').removeClass('set-section-height');
    }
});
function resizeScreen() {
    $('body>.container .main-screen').css('padding-bottom','0');
    $('.main-screen .set-section-height').outerHeight($('#footer').offset().top - $('.main-screen .set-section-height').offset().top);
    $('ul.sidebar').outerHeight($('#footer').offset().top - $('.main-screen .set-section-height').offset().top);
    $('div.sidebar').not('.weekly').outerHeight($('#footer').offset().top - $('.main-screen-details').offset().top);
}