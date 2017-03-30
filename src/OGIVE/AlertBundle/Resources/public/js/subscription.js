$(function () {
    $('#ogive_alertbundle_subscription_periodicity.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#ogive_alertbundle_subscription_currency.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#add_subscription_btn').click(function () {
        $('#add_subscription.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_subscription.ui.modal').modal('show');
    });

    $('#submit_subscription').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        $('#add_subscription_form.ui.form').submit();
    });
    $('#cancel_add_subscription').click(function () {
        window.location.reload();
    });
    $('#add_subscription_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'abonnement"
                            }
                        ]
                    },
                    periodicity: {
                        identifier: 'periodicity',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner la périodicité de l'abonnement"
                            }
                        ]
                    },
                    price: {
                        identifier: 'price',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez spécifier le coût de l'abonnement"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'post',
                        url: Routing.generate('subscription_add'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_subscription').addClass('disabled');
                            $('#cancel_add_subscription').addClass('disabled');
                            $('#add_subscription_form.ui.form').addClass('loading');
                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header').html("Echec de la validation");
                                    $('#error_name_list').html('<li>' + myerrors.message + '</li>');
                                    $('#error_name_message').show();
                                } else {
                                    $('#error_name_header').html("Echec de la validation. Veuillez vérifier vos données");
                                    $('#error_name_message').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#cancel_add_subscription').removeClass('disabled');
                            $('#submit_subscription').removeClass('disabled');
                            $('#add_subscription_form.ui.form').removeClass('loading');
                            $('#add_subscription.ui.modal').modal('hide');
                            $('#message_success>div.header').html(response.message);
                            $('#message_success').show();
                            window.location.replace(Routing.generate('subscription_index'));
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_subscription').removeClass('disabled');
                            $('#submit_subscription').removeClass('disabled');
                            $('#add_subscription_form.ui.form').removeClass('loading');
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_subscription(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('subscription_update', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {
                $('#server_error_message').show();
            },
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
            $('#edit_subscription').remove();
            $('#edit_subscription_content').html(response.edit_subscription_form);
            $('#ogive_alertbundle_subscription_periodicity.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_subscription_currency.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#edit_subscription.ui.modal').modal('setting', {
                autofocus: false,
                inverted: true,
                closable: false
            });
            $('#cancel_edit_subscription').click(function () {
                window.location.reload();
            });
            $('#edit_subscription.ui.modal').modal('show');
            execute_edit(id);
            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
        }
    });
}

function execute_edit(id) {
    $('#submit_edit_subscription').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        $('#edit_subscription_form.ui.form').submit();
    });
    $('#edit_subscription_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'abonnement"
                            }
                        ]
                    },
                    periodicity: {
                        identifier: 'periodicity',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner la périodicité de l'abonnement"
                            }
                        ]
                    },
                    price: {
                        identifier: 'price',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez spécifier le coût de l'abonnement"
                            },
                            {
                                type: 'number',
                                prompt: "coût de l'abonnement doit être un nombre"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('subscription_update', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_subscription').addClass('disabled');
                            $('#cancel_edit_subscription').addClass('disabled');
                            $('#edit_subscription_form.ui.form').addClass('loading');
                            $('#cancel_details_subscription').addClass('disabled');
                            $('#disable_subscription').addClass('disabled');
                            $('#enable_subscription').addClass('disabled');
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
                                } else {
                                    $('#error_name_header_edit').html("Echec de la validation. Veuillez vérifier vos données");
                                    $('#error_name_message_edit').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#submit_edit_subscription').removeClass('disabled');
                            $('#cancel_edit_subscription').removeClass('disabled');
                            $('#edit_subscription_form.ui.form').removeClass('loading');
                            $('#cancel_details_subscription').removeClass('disabled');
                            $('#disable_subscription').removeClass('disabled');
                            $('#enable_subscription').removeClass('disabled');
                            $('#edit_subscription.ui.modal').modal('hide');
                            $('#message_success>div.header').html(response.message);
                            $('#message_success').show();
                            window.location.reload();
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);
                            $('#edit_subscription').remove();

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_subscription').removeClass('disabled');
                            $('#cancel_edit_subscription').removeClass('disabled');
                            $('#edit_subscription_form.ui.form').removeClass('loading');
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_subscription(id) {
    $('#confirm_delete_subscription.ui.small.modal')
            .modal('show');

    $('#execute_delete_subscription').click(function (e) {
        e.preventDefault();
        $('#confirm_delete_subscription.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
        $.ajax({
            type: 'DELETE',
            url: Routing.generate('subscription_delete', {id: id}),
            dataType: 'json',
            beforeSend: function () {
                $('#message_loading').show();
            },
            statusCode: {
                500: function (xhr) {
                    $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                    $('#message_error').show();
                    
                },
                404: function (response, textStatus, jqXHR) {
                    $('#message_error>div.header').html(response.responseJSON.message);
                    $('#message_error').show();
                    
                }
            },
            success: function (response, textStatus, jqXHR) {
                $('#subscription_grid' + id).remove();
                $('#subscription_list' + id).remove();
                $('#message_loading').hide();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.replace(Routing.generate('subscription_index'));
                setTimeout(function () {
                    $('#message_success').hide();
                }, 4000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#message_loading').hide();
            }
        });
    });
}

function show_subscription(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
    $.ajax({
        type: 'GET',
        url: Routing.generate('subscription_get_one', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            500: function (xhr) {
                $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                    $('#message_error').show();
            },
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
            $('#edit_subscription').remove();
            $('#edit_subscription_content').html(response.subscription_details);
            $('#ogive_alertbundle_subscription_periodicity.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_subscription_currency.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#edit_subscription.ui.modal').modal('setting', {
                autofocus: false,
                inverted: true,
                closable: false
            });
            $('#cancel_details_subscription').click(function () {
                window.location.reload();
            });

            $('#edit_subscription.ui.modal').modal('show');
            execute_edit(id);
            $('#edit_subscription_btn').click(function () {
                $('#block_details').hide();
                $('#block_form_edit').show();
                $('#cancel_edit_subscription').show();
                $('#submit_edit_subscription').show();
                $(this).hide();
            });
            $('#cancel_edit_subscription').click(function () {
                $('#block_details').show();
                $('#block_form_edit').hide();
                $('#edit_subscription_btn').show();
                $('#submit_edit_subscription').hide();
                $(this).hide();
            });

            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
        }
    });
}

function enable_subscription(id) {
    $('#confirm_enable_subscription.ui.small.modal')
            .modal('show');

    $('#execute_enable_subscription').click(function (e) {
        e.preventDefault();
        $('#confirm_enable_subscription.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_subscription.ui.modal').modal('hide');
        $('#edit_subscription').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
        $.ajax({
            type: 'PUT',
            url: Routing.generate('subscription_update', {id: id}),
            data: {'action': 'enable'},
            dataType: 'json',
            beforeSend: function () {
                $('#message_loading').show();
            },
            statusCode: {
                500: function (xhr) {
                    $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                    $('#message_error').show();
                    
                },
                404: function (response, textStatus, jqXHR) {
                    $('#message_error>div.header').html("Echec d'activation de l'abonnement");
                    $('#message_error').show();
                    
                }
            },
            success: function (response, textStatus, jqXHR) {
                $('#message_loading').hide();
                $('#enable_subscription_grid' + id).hide();
                $('#disable_subscription_grid' + id).show();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.reload();
                setTimeout(function () {
                    $('#message_success').hide();
                }, 4000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#message_loading').hide();
            }
        });
    });
}

function disable_subscription(id) {
    $('#confirm_disable_subscription.ui.small.modal')
            .modal('show');

    $('#execute_disable_subscription').click(function (e) {
        e.preventDefault();
        $('#confirm_disable_subscription.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_subscription.ui.modal').modal('hide');
        $('#edit_subscription').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
        $.ajax({
            type: 'PUT',
            url: Routing.generate('subscription_update', {id: id}),
            data: {'action': 'disable'},
            dataType: 'json',
            beforeSend: function () {
                $('#message_loading').show();
            },
            statusCode: {
                500: function (xhr) {
                    $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                    $('#message_error').show();
                    
                },
                404: function (response, textStatus, jqXHR) {
                    $('#message_error>div.header').html("Echec de la désactivation de l'abonnement");
                    $('#message_error').show();
                    
                }
            },
            success: function (response, textStatus, jqXHR) {
                $('#message_loading').hide();
                $('#disable_subscription_grid' + id).hide();
                $('#enable_subscription_grid' + id).show();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.reload();
                setTimeout(function () {
                    $('#message_success').hide();
                }, 4000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#message_loading').hide();
            }
        });
    });
}