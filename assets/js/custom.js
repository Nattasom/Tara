$(document).ready(function(){
    activeMenu();
    activeTab();
    disabledEnterForm();
    $(".readonly").on("keydown",function(e){return false;});
    //$(".btn-datepicker").parents(".input-group").find("input").attr("readonly","");
    var simpledatepick = $(".btn-datepicker").parents(".input-group").find("input[type=text]").datepicker({
        autoclose:true,
        language:"th-th"
    }).on('changeDate', function (ev) {
        var $input = $(ev.target);
        $input.val(ev.formatdate);
        $input.trigger("change");
        simpledatepick.hide();
    }).data('datepicker');
    $(document).on("click", ".btn-datepicker",function(event){
        event.preventDefault();
        $(this).parents(".input-group").find("input[type=text]").focus();
    });

    $(".input-select").on("click",function(){
        $(this).select();
    });
    $(document).on('keyup keypress', ".input-select", function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            $(this).blur();
            e.preventDefault();
            return false;
        }
    });
    setInterval(animatedInterval,500);
});
function animatedInterval(){
    var $animated = $(".animated-display");
    if ($animated.hasClass("keep-height-hide")){
        $animated.removeClass("keep-height-hide");
    }
    else{
        $animated.addClass("keep-height-hide");
    }
}
function disabledEnterForm(){
    $('form input').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });
}
function activeTab(){
    var hash = window.location.hash;
    if (hash.length > 0) {
        $('a[role="tab"]').removeClass('active');//remove the default active tab
        $('a[href="' + hash + '"]').addClass('active');
        $('.tab-pane').removeClass('active');
        $(hash).addClass('active');
    }
}

function activeMenu(){
    var $keyElement = $("#page-menu-key");
    var menu = $keyElement.attr("data-menu");
    var sub = $keyElement.attr("data-sub");
    var url = $keyElement.attr("data-url");
    if(sub!=""){
        $("#main-menu > li#"+menu+" > a[href='#"+sub+"']").addClass("active").attr("aria-expanded", "true");
        $("#"+sub).addClass("show");
        $("#"+sub).find("a[href$='"+url+"']").addClass("active");
    }else{
        $("#main-menu > li#"+menu).addClass("active");
    }
    
}
function clearInputGroupText(element){
    $(element).parents(".input-group").find("input").val('');
    $(element).addClass("hide");
    $(element).parents(".input-group").find("input").trigger("change");
}
function clearInputGroupText_(element,other){
    var arrID = other.split(',');
    $(element).parents(".input-group").find("input").val('');
    $(element).addClass("hide");

    for(var i = 0;i<arrID.length;i++){
        $(arrID[i]).val('');
        $(arrID[i]).text('');
    }
}
function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
