$(function () {
    $('#add_domain_btn').click(function () {
        $('#add_domain.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_domain.ui.modal').modal('show');
    });

    $('#submit_domain').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#add_domain_form.ui.form').submit();
    });
    $('#add_domain_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le nom du domaine'
                            }
                        ]
                    }

                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'post',
                        url: Routing.generate('domain_add'),
                        data: $('#add_domain_form.ui.form').serialize(),
                        dataType: 'json',
                        processData: false,
                        //contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#submit_domain').addClass('disabled');
                            $('#cancel_add_domain').addClass('disabled');
                            $('#add_domain_form.ui.form').addClass('loading');
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
                                $('#cancel_add_domain').removeClass('disabled');
                                $('#submit_domain').removeClass('disabled');
                                $('#add_domain_form.ui.form').removeClass('loading');
                                $('#list_as_grid_content').prepend(response.domain_content_grid);
                                $('#list_as_table_content').prepend(response.domain_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_domain.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Domaine ajouté avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_domain').removeClass('disabled');
                            $('#submit_domain').removeClass('disabled');
                            $('#add_domain_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_domain(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('domain_update', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
            $('.ui.dropdown').dropdown('remove active');
            $('.ui.dropdown').dropdown('remove visible');
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
                $('#edit_domain').remove();
                $('#edit_domain_content').html(response.edit_domain_form);
                $('#edit_domain.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_domain.ui.modal').modal('show');
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
    $('#submit_edit_domain').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#edit_domain_form.ui.form').submit();
    });
    $('#edit_domain_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le nom du domaine'
                            }
                        ]
                    }

                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('domain_update', {id: id}),
                        data: $('#edit_domain_form.ui.form').serialize(),
                        dataType: 'json',
                        processData: false,
                        //contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#submit_edit_domain').addClass('disabled');
                            $('#cancel_edit_domain').addClass('disabled');
                            $('#edit_domain_form.ui.form').addClass('loading');
                            $('#cancel_details_domain').addClass('disabled');
                            $('#disable_domain').addClass('disabled');
                            $('#enable_domain').addClass('disabled');
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
                                $('#submit_edit_domain').removeClass('disabled');
                                $('#cancel_edit_domain').removeClass('disabled');
                                $('#edit_domain_form.ui.form').removeClass('loading');
                                $('#cancel_details_domain').removeClass('disabled');
                                $('#disable_domain').removeClass('disabled');
                                $('#enable_domain').removeClass('disabled');
                                $('#domain_grid' + id).html(response.domain_content_grid);
                                $('#domain_list' + id).html(response.domain_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_domain.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Domaine modifié avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#edit_domain').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_domain').removeClass('disabled');
                            $('#cancel_edit_domain').removeClass('disabled');
                            $('#edit_domain_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_domain(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'DELETE',
        url: Routing.generate('domain_delete', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
            $('.ui.dropdown').dropdown('remove active');
            $('.ui.dropdown').dropdown('remove visible');
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
            $('#domain_grid' + id).remove();
            $('#domain_list' + id).remove();
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

function show_domain(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'GET',
        url: Routing.generate('domain_get_one', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
            $('.ui.dropdown').dropdown('remove active');
            $('.ui.dropdown').dropdown('remove visible');
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
                $('#edit_domain').remove();
                $('#edit_domain_content').html(response.domain_details);
                $('#edit_domain.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_domain.ui.modal').modal('show');
                execute_edit(id);
                $('#edit_domain_btn').click(function () {
                    $('#block_details').hide();
                    $('#block_form_edit').show();
                    $('#cancel_edit_domain').show();
                    $('#submit_edit_domain').show();
                    $(this).hide();
                });
                $('#cancel_edit_domain').click(function () {
                    $('#block_details').show();
                    $('#block_form_edit').hide();
                    $('#edit_domain_btn').show();
                    $('#submit_edit_domain').hide();
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

function enable_domain(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_domain.ui.modal').modal('hide');
    $('#edit_domain').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('domain_update', {id: id}),
        data: {'action': 'enable'},
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
            $('.ui.dropdown').dropdown('remove active');
            $('.ui.dropdown').dropdown('remove visible');
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
                $('#message_error>div.header').html("Echec d'activation du domaine");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#enable_domain_grid' + id).hide();
            $('#disable_domain_grid' + id).show();
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

function disable_domain(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_domain.ui.modal').modal('hide');
    $('#edit_domain').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('domain_update', {id: id}),
        data: {'action': 'disable'},
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
            $('.ui.dropdown').dropdown('remove active');
            $('.ui.dropdown').dropdown('remove visible');
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
                $('#message_error>div.header').html("Echec de la désactivation du domaine");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#disable_domain_grid' + id).hide();
            $('#enable_domain_grid' + id).show();
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