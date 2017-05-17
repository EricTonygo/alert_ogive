
function add_entreprise() {
    $('#ogive_alertbundle_entreprise_domains.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#ogive_alertbundle_entreprise_subDomains.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#add_entreprise_btn').click(function () {
        $('#add_entreprise.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_entreprise.ui.modal').modal('show');
    });

    $('#submit_entreprise').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        $('#add_entreprise_form.ui.form').submit();
    });
    $('#add_entreprise_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'entreprise"
                            }
                        ]
                    },
                    domain: {
                        identifier: 'domains',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner au moins un domaine d'activité"
                            }
                        ]
                    },
                    phone: {
                        identifier: 'phone',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le numéro de téléphone de l'entreprise"
                            },
                            {
                                type: 'regExp[/^([\+][0-9]{4,}?)$/]',
                                prompt: "Veuillez saisir le numéro de téléphone valide"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'change',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'post',
                        url: Routing.generate('entreprise_add'),
                        data: $('#add_entreprise_form.ui.form').serialize(),
                        dataType: 'json',
                        processData: false,
                        //contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#submit_entreprise').addClass('disabled');
                            $('#cancel_add_entreprise').addClass('disabled');
                            $('#add_entreprise_form.ui.form').addClass('loading');
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
                                    $('#error_name_header').html("Echec de la validation. Veuillez verifier vos données");
                                    $('#error_name_message').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#cancel_add_entreprise').removeClass('disabled');
                            $('#submit_entreprise').removeClass('disabled');
                            $('#add_entreprise_form.ui.form').removeClass('loading');
                            $('#add_entreprise.ui.modal').modal('hide');
                            $('#message_success>div.header').html(response.message);
                            $('#message_success').show();
                            window.location.replace(Routing.generate('entreprise_index'));
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_entreprise').removeClass('disabled');
                            $('#submit_entreprise').removeClass('disabled');
                            $('#add_entreprise_form.ui.form').removeClass('loading');
                        }
                    });
                    return false;
                }
            }
            );
}


$(function () {
    add_entreprise();
    $('#cancel_add_entreprise').click(function () {
        window.location.reload();
    });
});

function edit_entreprise(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
    $('.ui.dropdown').dropdown({
        on: 'hover'
    });
    $.ajax({
        type: 'PUT',
        url: Routing.generate('entreprise_update', {id: id}),
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
            $('#edit_entreprise').remove();
            $('#edit_entreprise_content').html(response.edit_entreprise_form);

            $('#edit_entreprise.ui.modal').modal('setting', {
                autofocus: false,
                inverted: true,
                closable: false
            });
            $('#ogive_alertbundle_entreprise_domains.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_entreprise_subDomains.ui.dropdown').dropdown({
                on: 'click'
            });
            $('.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#cancel_edit_entreprise').click(function () {
                window.location.reload();
            });
            $('#edit_entreprise.ui.modal').modal('show');
            execute_edit(id);

            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
        }
    });
}

function execute_edit(id) {
    $('#submit_edit_entreprise').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        $('#edit_entreprise_form.ui.form').submit();
    });
    $('#edit_entreprise_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'entreprise"
                            }
                        ]
                    },
                    domain: {
                        identifier: 'domains',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner au moins un domaine d'activité"
                            }
                        ]
                    },
                    phone: {
                        identifier: 'phone',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le numéro de téléphone de l'entreprise"
                            },
                            {
                                type: 'regExp[/^([\+][0-9]{4,}?)$/]',
                                prompt: "Veuillez saisir le numéro de téléphone valide"
                            }
                        ]
                    },
                    subscription_update: {
                        identifier: 'subscription_update',
                        rules: [
                            {
                                type: 'checked',
                                prompt: "Veuillez préciser la raison de la mise à jour"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'change',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('entreprise_update', {id: id}),
                        data: $('#edit_entreprise_form.ui.form').serialize(),
                        dataType: 'json',
                        processData: false,
                        //contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#submit_edit_entreprise').addClass('disabled');
                            $('#cancel_edit_entreprise').addClass('disabled');
                            $('#edit_entreprise_form.ui.form').addClass('loading');
                            $('#cancel_details_entreprise').addClass('disabled');
                            $('#disable_entreprise').addClass('disabled');
                            $('#enable_entreprise').addClass('disabled');
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
                                    $('#error_name_header_edit').html("Echec de la validation. Veuillez verifier vos données");
                                    $('#error_name_message_edit').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#submit_edit_entreprise').removeClass('disabled');
                            $('#cancel_edit_entreprise').removeClass('disabled');
                            $('#edit_entreprise_form.ui.form').removeClass('loading');
                            $('#cancel_details_entreprise').removeClass('disabled');
                            $('#disable_entreprise').removeClass('disabled');
                            $('#enable_entreprise').removeClass('disabled');
                            $('#edit_entreprise.ui.modal').modal('hide');
                            $('#message_success>div.header').html(response.message);
                            $('#message_success').show();
                            window.location.reload();
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);
                            $('#edit_entreprise').remove();


                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_entreprise').removeClass('disabled');
                            $('#cancel_edit_entreprise').removeClass('disabled');
                            $('#edit_entreprise_form.ui.form').removeClass('loading');
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_entreprise(id) {
    $('#confirm_delete_entreprise.ui.small.modal')
            .modal('show')
            ;

    $('#execute_delete_entreprise').click(function (e) {
        e.preventDefault();
        $('#confirm_disabe_entreprise.ui.small.modal')
                .modal('hide')
                ;
        $('#message_error').hide();
        $('#message_success').hide();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
        $('.ui.dropdown').dropdown({
            on: 'hover'
        });
        $.ajax({
            type: 'DELETE',
            url: Routing.generate('entreprise_delete', {id: id}),
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
                console.log(response);
                $('#entreprise_grid' + id).remove();
                $('#entreprise_list' + id).remove();
                $('#message_loading').hide();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.replace(Routing.generate('entreprise_index'));
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

function show_entreprise(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
    $('.ui.dropdown').dropdown({
        on: 'hover'
    });
    $.ajax({
        type: 'GET',
        url: Routing.generate('entreprise_get_one', {id: id}),
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
            $('#edit_entreprise').remove();
            $('#edit_entreprise_content').html(response.entreprise_details);
            $('#edit_entreprise.ui.modal').modal('setting', {
                autofocus: false,
                inverted: true,
                closable: false
            });
            $('#ogive_alertbundle_entreprise_domains.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_entreprise_subDomains.ui.dropdown').dropdown({
                on: 'click'
            });

            $('#cancel_details_entreprise').click(function () {
                window.location.reload();
            });
            $('#edit_entreprise.ui.modal').modal('show');
            execute_edit(id);
            $('#edit_entreprise_btn').click(function () {
                $('#block_details').hide();
                $('#block_form_edit').show();
                $('#cancel_edit_entreprise').show();
                $('#submit_edit_entreprise').show();
                $(this).hide();
            });
            $('#cancel_edit_entreprise').click(function () {
                $('#block_details').show();
                $('#block_form_edit').hide();
                $('#edit_entreprise_btn').show();
                $('#submit_edit_entreprise').hide();
                $(this).hide();
            });

            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
        }
    });
}

function enable_entreprise(id) {
    $('#confirm_enable_entreprise.ui.small.modal')
            .modal('show')
            ;

    $('#enable_entreprise_form.ui.form')
            .form({
                fields: {
                    subscription_update: {
                        identifier: 'subscription_update',
                        rules: [
                            {
                                type: 'checked',
                                prompt: "Veuillez préciser la raison de la mise à jour"
                            }
                        ]
                    }
                },
                onSuccess: function (event, fields) {
                    $('#confirm_enable_entreprise.ui.small.modal')
                            .modal('hide')
                            ;
                    $('#message_error').hide();
                    $('#message_success').hide();
                    $('#edit_entreprise.ui.modal').modal('hide');
                    $('#edit_entreprise').remove();
                    $('.ui.dropdown').dropdown('remove active');
                    $('.ui.dropdown').dropdown('remove visible');
                    $('.ui.dropdown>div.menu').removeClass('visible');
                    $('.ui.dropdown>div.menu').addClass('hidden');
                    $('.ui.dropdown').dropdown({
                        on: 'hover'
                    });
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('entreprise_update', {id: id}),
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
                                $('#message_error>div.header').html(response.responseJSON.message);
                                $('#message_error').show();
                                window.location.reload();
                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#message_loading').hide();
                            $('#enable_entreprise_grid' + id).hide();
                            $('#disable_entreprise_grid' + id).show();
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
                    return false;
                }
            });

    $('#execute_enable_entreprise').click(function (e) {
        e.preventDefault();
        $('#enable_entreprise_form.ui.form').submit();
    });
}

function disable_entreprise(id) {
    $('#confirm_disable_entreprise.ui.small.modal')
            .modal('show')
            ;

    $('#execute_disable_entreprise').click(function (e) {
        e.preventDefault();
        $('#confirm_disable_entreprise.ui.small.modal')
                .modal('hide')
                ;
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_entreprise.ui.modal').modal('hide');
        $('#edit_entreprise').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
        $('.ui.dropdown').dropdown({
            on: 'hover'
        });
        $.ajax({
            type: 'PUT',
            url: Routing.generate('entreprise_update', {id: id}),
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
                    $('#message_error>div.header').html("Echec de la désactivation de l'entreprise");
                    $('#message_error').show();
                    window.location.reload();
                }
            },
            success: function (response, textStatus, jqXHR) {
                $('#message_loading').hide();
                $('#disable_entreprise_grid' + id).hide();
                $('#enable_entreprise_grid' + id).show();
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
