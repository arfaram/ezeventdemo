(function (global, doc) {
    require('jquery-colorbox');
    $('[data-toggle="tooltip"]').tooltip();

    $('.googleMapPopUp').each(function() {
        var thisPopup = $(this);
        var href = thisPopup.attr('href');
        console.log(href);
        thisPopup.colorbox(
            {html:'<iframe width="425" height="350"  src= "'+href+'?q=Van+Gogh+Museum?t=&amp;ie=UTF8&amp;&amp;output=embed"></iframe>'});
     });


})(window, document);

