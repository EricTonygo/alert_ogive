$(function () {
    $('#ogive_alertbundle_subscriber_entreprise.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#ogive_alertbundle_subscriber_subscription.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#add_subscriber_btn').click(function () {
        $('#add_subscriber.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_subscriber.ui.modal').modal('show');
    });

    $('#submit_subscriber').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#add_subscriber_form.ui.form').submit();
    });
    $('#add_subscriber_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'abonné"
                            }
                        ]
                    },

                    phoneNumber: {
                        identifier: 'phoneNumber',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le numéro de téléphone de l'abonné"
                            },
                            {
                                type: 'regExp[/^([\+][0-9]{4,}?)$/]',
                                prompt: "Veuillez saisir le numéro de téléphone valide"
                            }
                        ]
                    },

                    subscription: {
                        identifier: 'subscription',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner son abonnement"
                            }
                        ]
                    },

                    enterprise: {
                        identifier: 'enterprise',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner son entreprise"
                            }
                        ]
                    },

                    email: {
                        identifier: 'email',
                        optional: true,
                        rules: [
                            {
                                type: 'email',
                                prompt: "Veuillez saisir une adresse email valide"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'post',
                        url: Routing.generate('subscriber_add'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_subscriber').addClass('disabled');
                            $('#cancel_add_subscriber').addClass('disabled');
                            $('#add_subscriber_form.ui.form').addClass('loading');
                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                console.log(response);
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header').html("Echec de la validation");
                                    $('#error_name_list').html('<li>' + myerrors.message + '</li>');
                                    $('#error_name_message').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.code === 200) {
                                $('#cancel_add_subscriber').removeClass('disabled');
                                $('#submit_subscriber').removeClass('disabled');
                                $('#add_subscriber_form.ui.form').removeClass('loading');
                                $('#list_as_grid_content').prepend(response.subscriber_content_grid);
                                $('#list_as_table_content').prepend(response.subscriber_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_subscriber.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Abonné ajouté avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_subscriber').removeClass('disabled');
                            $('#submit_subscriber').removeClass('disabled');
                            $('#add_subscriber_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('subscriber_update', {id: id}),
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
                $('#edit_subscriber').remove();
                $('#edit_subscriber_content').html(response.edit_subscriber_form);
                $('#ogive_alertbundle_subscriber_entreprise.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#ogive_alertbundle_subscriber_subscription.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#edit_subscriber.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_subscriber.ui.modal').modal('show');
                execute_edit(id);
            }
            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function execute_edit(id) {
    $('#submit_edit_subscriber').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#edit_subscriber_form.ui.form').submit();
    });
    $('#edit_subscriber_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'abonné"
                            }
                        ]
                    },

                    phoneNumber: {
                        identifier: 'phoneNumber',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le numéro de téléphone de l'abonné"
                            },
                            {
                                type: 'regExp[/^([\+][0-9]{4,}?)$/]',
                                prompt: "Veuillez saisir le numéro de téléphone valide"
                            }
                        ]
                    },

                    subscription: {
                        identifier: 'subscription',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner son abonnement"
                            }
                        ]
                    },

                    enterprise: {
                        identifier: 'enterprise',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner son entreprise"
                            }
                        ]
                    },

                    email: {
                        identifier: 'email',
                        optional: true,
                        rules: [
                            {
                                type: 'email',
                                prompt: "Veuillez saisir une adresse email valide"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('subscriber_update', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_subscriber').addClass('disabled');
                            $('#cancel_edit_subscriber').addClass('disabled');
                            $('#edit_subscriber_form.ui.form').addClass('loading');
                            $('#cancel_details_subscriber').addClass('disabled');
                            $('#disable_subscriber').addClass('disabled');
                            $('#enable_subscriber').addClass('disabled');
                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message_edit').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header_edit').html("Echec de la validation");
                                    $('#error_name_list_edit').html('<li>' + myerrors.message + '</li>');
                                    $('#error_name_message_edit').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.code === 200) {
                                $('#submit_edit_subscriber').removeClass('disabled');
                                $('#cancel_edit_subscriber').removeClass('disabled');
                                $('#edit_subscriber_form.ui.form').removeClass('loading');
                                $('#cancel_details_subscriber').removeClass('disabled');
                                $('#disable_subscriber').removeClass('disabled');
                                $('#enable_subscriber').removeClass('disabled');
                                $('#subscriber_grid' + id).html(response.subscriber_content_grid);
                                $('#subscriber_list' + id).html(response.subscriber_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_subscriber.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Abonné modifié avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#edit_subscriber').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_subscriber').removeClass('disabled');
                            $('#cancel_edit_subscriber').removeClass('disabled');
                            $('#edit_subscriber_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'DELETE',
        url: Routing.generate('subscriber_delete', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {
                $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            },
            400: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#subscriber_grid' + id).remove();
            $('#subscriber_list' + id).remove();
            $('#message_loading').hide();
            $('#message_success>div.header').html(response.message);
            $('#message_success').show();
            setTimeout(function () {
                $('#message_success').hide();
            }, 4000);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function show_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'GET',
        url: Routing.generate('subscriber_get_one', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {
                $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            },
            400: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
            if (response.code === 200) {
                $('#edit_subscriber').remove();
                $('#edit_subscriber_content').html(response.subscriber_details);
                $('#ogive_alertbundle_subscriber_periodicity.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#ogive_alertbundle_subscriber_currency.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#edit_subscriber.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_subscriber.ui.modal').modal('show');
                execute_edit(id);
                $('#edit_subscriber_btn').click(function () {
                    $('#block_details').hide();
                    $('#block_form_edit').show();
                    $('#cancel_edit_subscriber').show();
                    $('#submit_edit_subscriber').show();
                    $(this).hide();
                });
                $('#cancel_edit_subscriber').click(function () {
                    $('#block_details').show();
                    $('#block_form_edit').hide();
                    $('#edit_subscriber_btn').show();
                    $('#submit_edit_subscriber').hide();
                    $(this).hide();
                });
            }
            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function enable_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_subscriber.ui.modal').modal('hide');
    $('#edit_subscriber').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('subscriber_update', {id: id}),
        data: {'action': 'enable'},
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {
                $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            },
            400: function (response, textStatus, jqXHR) {
                var myerrors = response.responseJSON;
                $('#message_error>div.header').html(myerrors.message);
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#enable_subscriber_grid' + id).hide();
            $('#disable_subscriber_grid' + id).show();
            $('#message_success>div.header').html(response.message);
            $('#message_success').show();
            setTimeout(function () {
                $('#message_success').hide();
            }, 4000);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function disable_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_subscriber.ui.modal').modal('hide');
    $('#edit_subscriber').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('subscriber_update', {id: id}),
        data: {'action': 'disable'},
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {
                $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            },
            400: function (response, textStatus, jqXHR) {
                var myerrors = response.responseJSON;
                $('#message_error>div.header').html(myerrors.message);
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#disable_subscriber_grid' + id).hide();
            $('#enable_subscriber_grid' + id).show();
            $('#message_success>div.header').html(response.message);
            $('#message_success').show();
            setTimeout(function () {
                $('#message_success').hide();
            }, 4000);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}