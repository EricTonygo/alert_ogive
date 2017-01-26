function send_procedure_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'GET',
        url: Routing.generate('send_notification_callOffer_get', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {

            },
            400: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
            if (response.code === 200) {
                $('#send_notification_callOffer').remove();
                $('#send_notification_callOffer_content').html(response.send_notification_callOffer_form);

                $('#send_notification_callOffer.ui.modal').modal('setting', {
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
                $('#send_notification_callOffer.ui.modal').modal('show');
                execute_send_notification_callOffer(id);
            }
            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}


function execute_send_notification_callOffer(id) {
    $('#submit_send_notification_callOffer').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#send_notification_callOffer_form.ui.form').submit();
    });
    $('#send_notification_callOffer_form.ui.form')
            .form({
                fields: {
                    abstract: {
                        identifier: 'abstract',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le resumé à envoyer"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'POST',
                        url: Routing.generate('send_notification_callOffer_post', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_send_notification_callOffer').addClass('disabled');
                            $('#cancel_send_notification_callOffer').addClass('disabled');
                            $('#send_notification_callOffer_form.ui.form').addClass('loading');

                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message_send_notification').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header_send_notification').html("Echec de la validation");
                                    $('#error_name_list_send_notification').html('<li>' + myerrors.message + '</li>');
                                    $('#error_name_message_send_notification').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.code === 200) {
                                $('#submit_send_notification_callOffer').removeClass('disabled');
                                $('#cancel_send_notification_callOffer').removeClass('disabled');
                                $('#send_notification_callOffer_form.ui.form').removeClass('loading');
                                $('#send_notification_callOffer.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Message envoyé avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#send_notification_callOffer').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_send_notification_callOffer').removeClass('disabled');
                            $('#cancel_send_notification_callOffer').removeClass('disabled');
                            $('#send_notification_callOffer_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}
