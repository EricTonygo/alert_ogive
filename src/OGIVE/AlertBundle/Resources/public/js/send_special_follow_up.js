function send_special_follow_up(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'GET',
        url: Routing.generate('send_special_follow_up_get', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {

            },
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
                $('#send_special_follow_up').remove();
                $('#send_special_follow_up_content').html(response.send_special_follow_up_form);

                $('#send_special_follow_up.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#checkbox_all_subscribers').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_select_subscribers').hide();
                        $('#field_select_subscribers>.ui.dropdown').dropdown('clear');
                    } else {
                        $('#field_select_subscribers').show();
                    }
                });
                $('#subscribers.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#send_special_follow_up.ui.modal').modal('show');
                execute_send_special_follow_up(id);
            
            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}


function execute_send_special_follow_up(id) {
    $('#submit_send_special_follow_up').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#send_special_follow_up_form.ui.form').submit();
    });
    $('#send_special_follow_up_form.ui.form')
            .form({
                fields: {
                    abstract: {
                        identifier: 'abstract',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le message à envoyer"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'POST',
                        url: Routing.generate('send_special_follow_up_post', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_send_special_follow_up').addClass('disabled');
                            $('#cancel_send_special_follow_up').addClass('disabled');
                            $('#send_special_follow_up_form.ui.form').addClass('loading');

                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message_send_notification').show();
                            },
                            404: function (response, textStatus, jqXHR) {
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header_send_notification').html("Echec de la validation");
                                    $('#error_name_list_send_notification').html('<li>' + myerrors.message + '</li>');
                                    $('#error_name_message_send_notification').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                                $('#submit_send_special_follow_up').removeClass('disabled');
                                $('#cancel_send_special_follow_up').removeClass('disabled');
                                $('#send_special_follow_up_form.ui.form').removeClass('loading');
                                $('#send_special_follow_up.ui.modal').modal('hide');
                                $('#message_success>div.header').html(response.message);
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#send_special_follow_up').remove();
                            

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_send_special_follow_up').removeClass('disabled');
                            $('#cancel_send_special_follow_up').removeClass('disabled');
                            $('#send_special_follow_up_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}
