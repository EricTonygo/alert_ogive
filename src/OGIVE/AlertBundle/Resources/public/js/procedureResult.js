$(function () {
    $('#checkbox_aao_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_aao_add').show();
            $('#field_asmi_add').hide();
        }
    });

    $('#checkbox_asmi_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_asmi_add').show();
            $('#field_aao_add').hide();
        }
    });

    $('#ogive_alertbundle_procedureResult_callOffer.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#ogive_alertbundle_procedureResult_expressionInterest.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#add_procedureResult_btn').click(function () {
        $('#add_procedureResult.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_procedureResult.ui.modal').modal('show');
    });

    $('#submit_procedureResult').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#add_procedureResult_form.ui.form').submit();
    });
    $('#add_procedureResult_form.ui.form')
            .form({
                fields: {
                    reference: {
                        identifier: 'reference',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir la reférence'
                            }
                        ]
                    },
                    object: {
                        identifier: 'object',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le contenu du resultat"
                            }
                        ]
                    },

//                    callOffer: {
//                        identifier: 'callOffer',
//                        rules: [
//                            {
//                                type: 'empty',
//                                prompt: "Veuillez selectionner l'appel d'offre"
//                            }
//                        ]
//                    },
//                    expressionInterest: {
//                        identifier: 'expressionInterest',
//                        rules: [
//                            {
//                                type: 'empty',
//                                prompt: "Veuillez selectionner la manisfestation d'intérêt"
//                            }
//                        ]
//                    },
                    publication_date_date: {
                        identifier: 'publication_date_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de publication de l'attribution"
                            }
                        ]
                    },

                    publication_date_time: {
                        identifier: 'publication_date_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure de publication de l'attribution"
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
                        url: Routing.generate('procedureResult_add'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_procedureResult').addClass('disabled');
                            $('#cancel_add_procedureResult').addClass('disabled');
                            $('#add_procedureResult_form.ui.form').addClass('loading');
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
                                $('#cancel_add_procedureResult').removeClass('disabled');
                                $('#submit_procedureResult').removeClass('disabled');
                                $('#add_procedureResult_form.ui.form').removeClass('loading');
                                $('#list_as_grid_content').prepend(response.procedureResult_content_grid);
                                $('#list_as_table_content').prepend(response.procedureResult_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_procedureResult.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Attribution ajoutée avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_procedureResult').removeClass('disabled');
                            $('#submit_procedureResult').removeClass('disabled');
                            $('#add_procedureResult_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_procedureResult(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('procedureResult_update', {id: id}),
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
                $('#edit_procedureResult').remove();
                $('#edit_procedureResult_content').html(response.edit_procedureResult_form);
                $('#edit_procedureResult.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#ogive_alertbundle_procedureResult_callOffer.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#ogive_alertbundle_procedureResult_expressionInterest.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#checkbox_aao_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_aao_edit').show();
                        $('#field_asmi_edit').hide();
                    }
                });

                $('#checkbox_asmi_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').show();
                        $('#field_aao_edit').hide();
                    }
                });
                $('#edit_procedureResult.ui.modal').modal('show');

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
    $('#submit_edit_procedureResult').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#edit_procedureResult_form.ui.form').submit();
    });
    $('#edit_procedureResult_form.ui.form')
            .form({
                fields: {
                    reference: {
                        identifier: 'reference',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir la reférence'
                            }
                        ]
                    },
                    object: {
                        identifier: 'object',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le contenu du resultat"
                            }
                        ]
                    },

//                    callOffer: {
//                        identifier: 'callOffer',
//                        rules: [
//                            {
//                                type: 'empty',
//                                prompt: "Veuillez selectionner l'appel d'offre"
//                            }
//                        ]
//                    },
//                    expressionInterest: {
//                        identifier: 'expressionInterest',
//                        rules: [
//                            {
//                                type: 'empty',
//                                prompt: "Veuillez selectionner la manisfestation d'intérêt"
//                            }
//                        ]
//                    },
                    publication_date_date: {
                        identifier: 'publication_date_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de publication de l'attribution"
                            }
                        ]
                    },

                    publication_date_time: {
                        identifier: 'publication_date_time',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner l'heure de publication de l'attribution"
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
                        url: Routing.generate('procedureResult_update', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_procedureResult').addClass('disabled');
                            $('#cancel_edit_procedureResult').addClass('disabled');
                            $('#edit_procedureResult_form.ui.form').addClass('loading');
                            $('#cancel_details_procedureResult').addClass('disabled');
                            $('#disable_procedureResult').addClass('disabled');
                            $('#enable_procedureResult').addClass('disabled');
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
                                $('#submit_edit_procedureResult').removeClass('disabled');
                                $('#cancel_edit_procedureResult').removeClass('disabled');
                                $('#edit_procedureResult_form.ui.form').removeClass('loading');
                                $('#cancel_details_procedureResult').removeClass('disabled');
                                $('#disable_procedureResult').removeClass('disabled');
                                $('#enable_procedureResult').removeClass('disabled');
                                $('#procedureResult_grid' + id).html(response.procedureResult_content_grid);
                                $('#procedureResult_list' + id).html(response.procedureResult_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_procedureResult.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Attribution modifiée avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#edit_procedureResult').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_procedureResult').removeClass('disabled');
                            $('#cancel_edit_procedureResult').removeClass('disabled');
                            $('#edit_procedureResult_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_procedureResult(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'DELETE',
        url: Routing.generate('procedureResult_delete', {id: id}),
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
            $('#procedureResult_grid' + id).remove();
            $('#procedureResult_list' + id).remove();
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

function show_procedureResult(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'GET',
        url: Routing.generate('procedureResult_get_one', {id: id}),
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
                $('#edit_procedureResult').remove();
                $('#edit_procedureResult_content').html(response.procedureResult_details);
                $('#edit_procedureResult.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#ogive_alertbundle_procedureResult_domain.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#edit_procedureResult.ui.modal').modal('show');
                execute_edit(id);
                $('#edit_procedureResult_btn').click(function () {
                    $('#block_details').hide();
                    $('#block_form_edit').show();
                    $('#cancel_edit_procedureResult').show();
                    $('#submit_edit_procedureResult').show();
                    $(this).hide();
                });
                $('#cancel_edit_procedureResult').click(function () {
                    $('#block_details').show();
                    $('#block_form_edit').hide();
                    $('#edit_procedureResult_btn').show();
                    $('#submit_edit_procedureResult').hide();
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

function enable_procedureResult(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_procedureResult.ui.modal').modal('hide');
    $('#edit_procedureResult').remove();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('procedureResult_update', {id: id}),
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
                $('#message_error>div.header').html("Echec d'activation de l'attribution");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#enable_procedureResult_grid' + id).hide();
            $('#disable_procedureResult_grid' + id).show();
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

function disable_procedureResult(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_procedureResult.ui.modal').modal('hide');
    $('#edit_procedureResult').remove();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('procedureResult_update', {id: id}),
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
                $('#message_error>div.header').html("Echec de la désactivation de l'attribution");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#disable_procedureResult_grid' + id).hide();
            $('#enable_procedureResult_grid' + id).show();
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