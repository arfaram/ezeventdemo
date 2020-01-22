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

    var box, border, border_radius, border_style, background, box_padding, box_blink, thisBox;
    function boxStyle(){
        box = document.querySelectorAll('p.box');
        for(var i=0; i < box.length; i++){
            border = box[i].dataset.ezattributeBorder;
            if(border) {
                border_radius = box[i].dataset.ezattributeBorder_radius;
                border_style = box[i].dataset.ezattributeBorder_style;
                background = box[i].dataset.ezattributeBackground;
                box_padding = box[i].dataset.ezattributeBox_padding;
                box[i].style.border = border_radius + "px " + border_style;
                box[i].style.padding = box_padding + "px";
                box[i].style.borderRadius = border_radius + "px";
                switch (background) {
                    case 'dark':
                        box[i].style.background = '#DDDDDD';
                        break;
                    case 'light':
                        box[i].style.background = '#EEEEEE';
                        break;
                    case 'yellow':
                        box[i].style.background = '#ecf585';
                        break;
                    default:
                        box[i].style.background = 'none';
                        break;
                }

                //below just for laughs
                box_blink = box[i].dataset.ezattributeBox_blink;
                thisBox = box[i];
                if(box_blink){
                    box[i].style.borderColor = "#EEEEEE";
                    function blink_text(thisBox) {
                        $('[data-ezattribute-box_blink="true"]').fadeOut(500);
                        $('[data-ezattribute-box_blink="true"]').fadeIn(500);

                    }
                    setInterval(blink_text, 1000);
                }

            }
        }
    }
    window.addEventListener("load", boxStyle);

})(window, document);

