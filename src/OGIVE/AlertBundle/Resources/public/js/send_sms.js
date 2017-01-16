function send_sms_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('send_sms_subscriber', {id: id}),
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
                $('#send_sms_subscriber').remove();
                $('#send_sms_subscriber_content').html(response.send_sms_subscriber_form);

                $('#send_sms_subscriber.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#send_sms_subscriber.ui.modal').modal('show');
                execute_send_sms_subscriber(id);
            }
            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}


function execute_send_sms_subscriber(id) {
    $('#submit_send_sms_subscriber').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#send_sms_subscriber_form.ui.form').submit();
    });
    $('#send_sms_subscriber_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'message',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le sms à envoyer"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('send_sms_subscriber', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_send_sms_subscriber').addClass('disabled');
                            $('#cancel_send_sms_subscriber').addClass('disabled');
                            $('#send_sms_subscriber_form.ui.form').addClass('loading');

                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message_send_sms').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header_send_sms').html("Echec de la validation");
                                    $('#error_name_list_send_sms').html('<li>' + myerrors.message + '</li>');
                                    $('#error_name_message_send_sms').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.code === 200) {
                                $('#submit_send_sms_subscriber').removeClass('disabled');
                                $('#cancel_send_sms_subscriber').removeClass('disabled');
                                $('#send_sms_subscriber_form.ui.form').removeClass('loading');
                                $('#send_sms_subscriber.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Message envoyé avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#send_sms_subscriber').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_send_sms_subscriber').removeClass('disabled');
                            $('#cancel_send_sms_subscriber').removeClass('disabled');
                            $('#send_sms_subscriber_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}