function send_mail_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'POST',
        url: Routing.generate('send_mail_subscriber', {id: id}),
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
            $('#send_mail_subscriber').remove();
            $('#send_mail_subscriber_content').html(response.send_mail_subscriber_form);

            $('#send_mail_subscriber.ui.modal').modal('setting', {
                autofocus: false,
                inverted: true,
                closable: false
            });
            $('#send_mail_subscriber.ui.modal').modal('show');
            execute_send_mail_subscriber(id);

            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}


function execute_send_mail_subscriber(id) {
    $('#submit_send_mail_subscriber').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#send_mail_subscriber_form.ui.form').submit();
    });
    $('#send_mail_subscriber_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'message',
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
                        url: Routing.generate('send_mail_subscriber', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_send_mail_subscriber').addClass('disabled');
                            $('#cancel_send_mail_subscriber').addClass('disabled');
                            $('#send_mail_subscriber_form.ui.form').addClass('loading');

                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message_send_mail').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header_send_mail').html("Echec de la validation");
                                    $('#error_name_list_send_mail').html('<li>' + myerrors.message + '</li>');
                                    $('#error_name_message_send_mail').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#submit_send_mail_subscriber').removeClass('disabled');
                            $('#cancel_send_mail_subscriber').removeClass('disabled');
                            $('#send_mail_subscriber_form.ui.form').removeClass('loading');
                            $('#send_mail_subscriber.ui.modal').modal('hide');
                            $('#message_success>div.header').html('Message envoyé avec succès !');
                            $('#message_success').show();
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);
                            $('#send_mail_subscriber').remove();

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_send_mail_subscriber').removeClass('disabled');
                            $('#cancel_send_mail_subscriber').removeClass('disabled');
                            $('#send_mail_subscriber_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

