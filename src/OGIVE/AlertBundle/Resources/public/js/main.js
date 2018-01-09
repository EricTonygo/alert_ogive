$(function () {
    var init = 0;

    $('input[name="start-date"').datetimepicker({
        timepicker: false,
        //minDate: '0',
        format: 'd-m-Y',
        lang: 'fr',
        scrollInput: false
    });

    $('input[name="end-date"').datetimepicker({
        timepicker: false,
        //minDate: '0',
        format: 'd-m-Y',
        lang: 'fr',
        scrollInput: false
    });

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
                $(this).parent(".message").hide();
            })
            ;

    $('.pagination').change(function (e) {
        e.preventDefault();
        $('.nav_link').removeClass('is-active');
        $('#home').addClass('is-active');
        var url = $(this).find('option:selected').val();
        if (url !== "") {
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

    $('#filter_form').submit(function () {
        if ($('#filter_form input[name="start-date"]').val() === "" && $('#filter_form input[name="end-date"]').val() === "" && $('#filter_form input[name="owner"]').val() === "" && $('#filter_form input[name="domain"]').val() === "") {
            return false;
        }
        $('#submit_filter_form').addClass('loading');
    });

    $('#filter_form select[name="owner"]').change(function (e) {
        e.preventDefault();
        $('#filter_form').submit();
    });
    
    $('#filter_form select[name="domain"]').change(function (e) {
        e.preventDefault();
        $('#filter_form').submit();
    });
});

function show_sms_message_length() {
    $('#abstract_sms').keypress(function (e) {
        $('#abstract_sms_count').html($(this).val().length);
    });
    $('#abstract_sms').keyup(function (e) {
        $('#abstract_sms_count').html($(this).val().length);
    });
    $('#abstract_sms').keydown(function (e) {
        $('#abstract_sms_count').html($(this).val().length);
    });
}