$(function () {
    $('#ogive_alertbundle_expressionInterest_domain.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#add_expressionInterest_btn').click(function () {
        $('#add_expressionInterest.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_expressionInterest.ui.modal').modal('show');
    });

    $('#submit_expressionInterest').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#add_expressionInterest_form.ui.form').submit();
    });
    $('#add_expressionInterest_form.ui.form')
            .form({
                fields: {
                    reference: {
                        identifier: 'reference',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le numero'
                            }
                        ]
                    },
                    object: {
                        identifier: 'object',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir l'objet"
                            }
                        ]
                    },
                    owner: {
                        identifier: 'owner',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner le maître d'ouvrage"
                            }
                        ]
                    },
                    domain: {
                        identifier: 'domain',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner le domaine"
                            }
                        ]
                    },
                    publication_date: {
                        identifier: 'publication_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de publication de l'offre"
                            }
                        ]
                    },
                    opening_date_date: {
                        identifier: 'opening_date_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date d'ouverture de dépôt"
                            }
                        ]
                    },
                    opening_date_time: {
                        identifier: 'opening_date_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure d'ouverture de dépôt"
                            }
                        ]
                    },
                    deadline_date: {
                        identifier: 'deadline_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date limite de dépôt"
                            }
                        ]
                    },
                    deadline_time: {
                        identifier: 'deadline_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure limite de dépôt"
                            }
                        ]
                    },
                    sending_date_date: {
                        identifier: 'sending_date_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de notification aux abonnés"
                            }
                        ]
                    },
                    sending_date_time: {
                        identifier: 'sending_date_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure de notification aux abonnés"
                            }
                        ]
                    }

                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'post',
                        url: Routing.generate('expressionInterest_add'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_expressionInterest').addClass('disabled');
                            $('#cancel_add_expressionInterest').addClass('disabled');
                            $('#add_expressionInterest_form.ui.form').addClass('loading');
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
                                $('#cancel_add_expressionInterest').removeClass('disabled');
                                $('#submit_expressionInterest').removeClass('disabled');
                                $('#add_expressionInterest_form.ui.form').removeClass('loading');
                                $('#list_as_grid_content').prepend(response.expressionInterest_content_grid);
                                $('#list_as_table_content').prepend(response.expressionInterest_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_expressionInterest.ui.modal').modal('hide');
                                $('#message_success>div.header').html("Manifestation d'intérêt ajoutée avec succès !");
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_expressionInterest').removeClass('disabled');
                            $('#submit_expressionInterest').removeClass('disabled');
                            $('#add_expressionInterest_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_expressionInterest(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('expressionInterest_update', {id: id}),
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
                $('#edit_expressionInterest').remove();
                $('#edit_expressionInterest_content').html(response.edit_expressionInterest_form);
                $('#edit_expressionInterest.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#ogive_alertbundle_expressionInterest_domain.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#edit_expressionInterest.ui.modal').modal('show');
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
    $('#submit_edit_expressionInterest').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#edit_expressionInterest_form.ui.form').submit();
    });
    $('#edit_expressionInterest_form.ui.form')
            .form({
                fields: {
                    reference: {
                        identifier: 'reference',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le numero'
                            }
                        ]
                    },
                    object: {
                        identifier: 'object',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir l'objet"
                            }
                        ]
                    },
                    owner: {
                        identifier: 'owner',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner le maître d'ouvrage"
                            }
                        ]
                    },
                    domain: {
                        identifier: 'domain',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez selectionner le domaine"
                            }
                        ]
                    },
                    publication_date: {
                        identifier: 'publication_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de publication de l'offre"
                            }
                        ]
                    },
                    opening_date_date: {
                        identifier: 'opening_date_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date d'ouverture de dépôt"
                            }
                        ]
                    },
                    opening_date_time: {
                        identifier: 'opening_date_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure d'ouverture de dépôt"
                            }
                        ]
                    },
                    deadline_date: {
                        identifier: 'deadline_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date limite de dépôt"
                            }
                        ]
                    },
                    deadline_time: {
                        identifier: 'deadline_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure limite de dépôt"
                            }
                        ]
                    },
                    sending_date_date: {
                        identifier: 'sending_date_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de notification aux abonnés"
                            }
                        ]
                    },
                    sending_date_time: {
                        identifier: 'sending_date_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure de notification aux abonnés"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('expressionInterest_update', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_expressionInterest').addClass('disabled');
                            $('#cancel_edit_expressionInterest').addClass('disabled');
                            $('#edit_expressionInterest_form.ui.form').addClass('loading');
                            $('#cancel_details_expressionInterest').addClass('disabled');
                            $('#disable_expressionInterest').addClass('disabled');
                            $('#enable_expressionInterest').addClass('disabled');
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
                                $('#submit_edit_expressionInterest').removeClass('disabled');
                                $('#cancel_edit_expressionInterest').removeClass('disabled');
                                $('#edit_expressionInterest_form.ui.form').removeClass('loading');
                                $('#cancel_details_expressionInterest').removeClass('disabled');
                                $('#disable_expressionInterest').removeClass('disabled');
                                $('#enable_expressionInterest').removeClass('disabled');
                                $('#expressionInterest_grid' + id).html(response.expressionInterest_content_grid);
                                $('#expressionInterest_list' + id).html(response.expressionInterest_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_expressionInterest.ui.modal').modal('hide');
                                $('#message_success>div.header').html("Manifestation d'intérêt modifiée avec succès !");
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#edit_expressionInterest').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_expressionInterest').removeClass('disabled');
                            $('#cancel_edit_expressionInterest').removeClass('disabled');
                            $('#edit_expressionInterest_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_expressionInterest(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'DELETE',
        url: Routing.generate('expressionInterest_delete', {id: id}),
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
            $('#expressionInterest_grid' + id).remove();
            $('#expressionInterest_list' + id).remove();
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

function show_expressionInterest(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'GET',
        url: Routing.generate('expressionInterest_get_one', {id: id}),
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
                $('#edit_expressionInterest').remove();
                $('#edit_expressionInterest_content').html(response.expressionInterest_details);
                $('#edit_expressionInterest.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#ogive_alertbundle_expressionInterest_domain.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#edit_expressionInterest.ui.modal').modal('show');
                execute_edit(id);
                $('#edit_expressionInterest_btn').click(function () {
                    $('#block_details').hide();
                    $('#block_form_edit').show();
                    $('#cancel_edit_expressionInterest').show();
                    $('#submit_edit_expressionInterest').show();
                    $(this).hide();
                });
                $('#cancel_edit_expressionInterest').click(function () {
                    $('#block_details').show();
                    $('#block_form_edit').hide();
                    $('#edit_expressionInterest_btn').show();
                    $('#submit_edit_expressionInterest').hide();
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

function enable_expressionInterest(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_expressionInterest.ui.modal').modal('hide');
    $('#edit_expressionInterest').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('expressionInterest_update', {id: id}),
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
                $('#message_error>div.header').html("Echec d'activation de la manifestation");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#enable_expressionInterest_grid' + id).hide();
            $('#disable_expressionInterest_grid' + id).show();
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

function disable_expressionInterest(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_expressionInterest.ui.modal').modal('hide');
    $('#edit_expressionInterest').remove();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $.ajax({
        type: 'PUT',
        url: Routing.generate('expressionInterest_update', {id: id}),
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
                $('#message_error>div.header').html("Echec de la désactivation de la manifestition");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#disable_expressionInterest_grid' + id).hide();
            $('#enable_expressionInterest_grid' + id).show();
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