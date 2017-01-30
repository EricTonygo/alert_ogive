$(function () {
    $('#add_specialFollowUp_btn').click(function () {
        $('#add_specialFollowUp.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_specialFollowUp.ui.modal').modal('show');
    });

    $('#submit_specialFollowUp').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#add_specialFollowUp_form.ui.form').submit();
    });
    $('#add_specialFollowUp_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le nom du suivi spécialisé'
                            }
                        ]
                    }

                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'post',
                        url: Routing.generate('specialFollowUp_add'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_specialFollowUp').addClass('disabled');
                            $('#cancel_add_specialFollowUp').addClass('disabled');
                            $('#add_specialFollowUp_form.ui.form').addClass('loading');
                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message').show();
                            },
                            404: function (response, textStatus, jqXHR) {
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
                                $('#cancel_add_specialFollowUp').removeClass('disabled');
                                $('#submit_specialFollowUp').removeClass('disabled');
                                $('#add_specialFollowUp_form.ui.form').removeClass('loading');
                                $('#list_as_grid_content').prepend(response.specialFollowUp_content_grid);
                                $('#list_as_table_content').prepend(response.specialFollowUp_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_specialFollowUp.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Suivi spécialisé ajouté avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_specialFollowUp').removeClass('disabled');
                            $('#submit_specialFollowUp').removeClass('disabled');
                            $('#add_specialFollowUp_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_specialFollowUp(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('specialFollowUp_update', { id: id }),
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
            if (response.code === 200) {
                $('#edit_specialFollowUp').remove();
                $('#edit_specialFollowUp_content').html(response.edit_specialFollowUp_form);
                $('#edit_specialFollowUp.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_specialFollowUp.ui.modal').modal('show');
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
    $('#submit_edit_specialFollowUp').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#edit_specialFollowUp_form.ui.form').submit();
    });
    $('#edit_specialFollowUp_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le nom du suivi spécialisé'
                            }
                        ]
                    },
                    description: {
                        identifier: 'description',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir la description du suivi spécialisé'
                            }
                        ]
                    }

                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('specialFollowUp_update', { id: id }),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_specialFollowUp').addClass('disabled');
                            $('#cancel_edit_specialFollowUp').addClass('disabled');
                            $('#edit_specialFollowUp_form.ui.form').addClass('loading');
                            $('#cancel_details_specialFollowUp').addClass('disabled');
                            $('#disable_specialFollowUp').addClass('disabled');
                            $('#enable_specialFollowUp').addClass('disabled');
                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message_edit').show();
                            },
                            404: function (response, textStatus, jqXHR) {
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
                                $('#submit_edit_specialFollowUp').removeClass('disabled');
                                $('#cancel_edit_specialFollowUp').removeClass('disabled');
                                $('#edit_specialFollowUp_form.ui.form').removeClass('loading');
                                $('#cancel_details_specialFollowUp').removeClass('disabled');
                                $('#disable_specialFollowUp').removeClass('disabled');
                                $('#enable_specialFollowUp').removeClass('disabled');
                                $('#specialFollowUp_grid' + id).html(response.specialFollowUp_content_grid);
                                $('#specialFollowUp_list' + id).html(response.specialFollowUp_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_specialFollowUp.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Suivi spécialisé modifié avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#edit_specialFollowUp').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_specialFollowUp').removeClass('disabled');
                            $('#cancel_edit_specialFollowUp').removeClass('disabled');
                            $('#edit_specialFollowUp_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_specialFollowUp(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'DELETE',
        url: Routing.generate('specialFollowUp_delete', { id: id }),
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
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#specialFollowUp_grid' + id).remove();
            $('#specialFollowUp_list' + id).remove();
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

function show_specialFollowUp(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'GET',
        url: Routing.generate('specialFollowUp_get_one', { id: id }),
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
            if (response.code === 200) {
                $('#edit_specialFollowUp').remove();
                $('#edit_specialFollowUp_content').html(response.specialFollowUp_details);
                $('#edit_specialFollowUp.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_specialFollowUp.ui.modal').modal('show');
                execute_edit(id);
                $('#edit_specialFollowUp_btn').click(function () {
                    $('#block_details').hide();
                    $('#block_form_edit').show();
                    $('#cancel_edit_specialFollowUp').show();
                    $('#submit_edit_specialFollowUp').show();
                    $(this).hide();
                });
                $('#cancel_edit_specialFollowUp').click(function () {
                    $('#block_details').show();
                    $('#block_form_edit').hide();
                    $('#edit_specialFollowUp_btn').show();
                    $('#submit_edit_specialFollowUp').hide();
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

function enable_specialFollowUp(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_specialFollowUp.ui.modal').modal('hide');
    $('#edit_specialFollowUp').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('specialFollowUp_update', { id: id }),
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
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html("Echec d'activation du suivi spécialisé");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#enable_specialFollowUp_grid' + id).hide();
            $('#disable_specialFollowUp_grid' + id).show();
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

function disable_specialFollowUp(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_specialFollowUp.ui.modal').modal('hide');
    $('#edit_specialFollowUp').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('specialFollowUp_update', { id: id }),
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
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html("Echec de la désactivation du suivi spécialisé");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#disable_specialFollowUp_grid' + id).hide();
            $('#enable_specialFollowUp_grid' + id).show();
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