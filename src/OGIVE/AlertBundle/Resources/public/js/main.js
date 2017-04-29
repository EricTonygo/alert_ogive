$(function () {
    var init = 0;

    $('#show_list_table').click(function () {
        if (init === 0) {
            $('#list_as_grid').hide();
            $('#list_as_table').show();
            init = 1;
        } else if (init === 1) {
            $('#list_as_grid').show();
            $('#list_as_table').hide();
            init = 0;
        }
    });

    $('.ui.sidebar')
            .sidebar({
                //context: $('.bottom.segment'),
                dimPage: false
            })
            .sidebar('setting', 'transition', 'overlay')
            .sidebar('attach events', '.main.menu .mobile_menu.item')
            ;

    $('.ui.dropdown').dropdown({
        on: 'hover'
    });

    $('.ui.accordion')
            .accordion({
            })
            ;

    $('a.item').click(function () {
        $('a.item').removeClass('active');
        $(this).addClass('active');
    });

//    $('.message .close')
//            .on('click', function () {
//                $(this)
//                        .closest('.message')
//                        .transition('fade')
//                        ;
//            })
//            ;
$('.message .close')
            .on('click', function () {
                $(this).parent( ".message" ).hide();
            })
            ;
            
$('#pagination').change(function(e){
    e.preventDefault();
    $('.nav_link').removeClass('is-active');
    $('#home').addClass('is-active');
    var url = $(this).find('option:selected').val();
    if(url !==""){
        window.location.replace(url);
    }
});

$('input.search_input').on("change paste keyup", function (e) {
        var myclass = $(this).attr('id');
        if ($(this).val() !== "") {
            $('i.remove.link.icon.' + myclass).show();
        } else {
            $('i.remove.link.icon.' + myclass).hide();
        }
    });

    $('input.search_input').on("focusout", function (e) {
        var myclass = $(this).attr('id');
        if ($(this).val() === "") {
            $('i.remove.link.icon.' + myclass).hide();
        }
    });

    $('i.remove.link.icon').click(function (e) {
        var myid = $(this).attr('search_input_id');
        $('#' + myid).val("");
        $('i.remove.link.icon.' + myid).hide();
    });
    
    $('#search_form').submit(function () {
        if ($('#search_form input[name="s"]').val() === "") {
            return false;
        }
        $('#submit_search_form').addClass('loading');
    });
});