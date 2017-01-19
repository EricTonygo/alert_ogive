$(function () {
    $('#checkbox_aono_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_asmi_add').hide();
        }
    });

    $('#checkbox_aonr_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_asmi_add').show();
        }
    });

    $('#checkbox_aoio_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_asmi_add').hide();
        }
    });

    $('#checkbox_aoir_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_asmi_add').show();
        }
    });
    $('#ogive_alertbundle_calloffer_domain.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#add_callOffer_btn').click(function () {
        $('#add_callOffer.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_callOffer.ui.modal').modal('show');
    });

    $('#submit_callOffer').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#add_callOffer_form.ui.form').submit();
    });
    $('#add_callOffer_form.ui.form')
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
                        url: Routing.generate('call_offer_add'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_callOffer').addClass('disabled');
                            $('#cancel_add_callOffer').addClass('disabled');
                            $('#add_callOffer_form.ui.form').addClass('loading');
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
                                $('#cancel_add_callOffer').removeClass('disabled');
                                $('#submit_callOffer').removeClass('disabled');
                                $('#add_callOffer_form.ui.form').removeClass('loading');
                                $('#list_as_grid_content').prepend(response.callOffer_content_grid);
                                $('#list_as_table_content').prepend(response.callOffer_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_callOffer.ui.modal').modal('hide');
                                $('#message_success>div.header').html("Appel d'offre ajouté avec succès !");
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_callOffer').removeClass('disabled');
                            $('#submit_callOffer').removeClass('disabled');
                            $('#add_callOffer_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('call_offer_update', {id: id}),
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
                $('#edit_callOffer').remove();
                $('#edit_callOffer_content').html(response.edit_callOffer_form);
                $('#edit_callOffer.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#ogive_alertbundle_calloffer_domain.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#checkbox_aono_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').hide();
                    }
                });

                $('#checkbox_aonr_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').show();
                    }
                });

                $('#checkbox_aoio_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').hide();
                    }
                });

                $('#checkbox_aoir_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').show();
                    }
                });
                
                $('#edit_callOffer.ui.modal').modal('show');
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
    $('#submit_edit_callOffer').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#edit_callOffer_form.ui.form').submit();
    });
    $('#edit_callOffer_form.ui.form')
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
                        url: Routing.generate('call_offer_update', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_callOffer').addClass('disabled');
                            $('#cancel_edit_callOffer').addClass('disabled');
                            $('#edit_callOffer_form.ui.form').addClass('loading');
                            $('#cancel_details_callOffer').addClass('disabled');
                            $('#disable_callOffer').addClass('disabled');
                            $('#enable_callOffer').addClass('disabled');
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
                                $('#submit_edit_callOffer').removeClass('disabled');
                                $('#cancel_edit_callOffer').removeClass('disabled');
                                $('#edit_callOffer_form.ui.form').removeClass('loading');
                                $('#cancel_details_callOffer').removeClass('disabled');
                                $('#disable_callOffer').removeClass('disabled');
                                $('#enable_callOffer').removeClass('disabled');
                                $('#callOffer_grid' + id).html(response.callOffer_content_grid);
                                $('#callOffer_list' + id).html(response.callOffer_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_callOffer.ui.modal').modal('hide');
                                $('#message_success>div.header').html("Appel d'offre modifié avec succès !");
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#edit_callOffer').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_callOffer').removeClass('disabled');
                            $('#cancel_edit_callOffer').removeClass('disabled');
                            $('#edit_callOffer_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'DELETE',
        url: Routing.generate('call_offer_delete', {id: id}),
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
            $('#callOffer_grid' + id).remove();
            $('#callOffer_list' + id).remove();
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

function show_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'GET',
        url: Routing.generate('call_offer_get_one', {id: id}),
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
                $('#edit_callOffer').remove();
                $('#edit_callOffer_content').html(response.callOffer_details);
                $('#edit_callOffer.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#ogive_alertbundle_calloffer_domain.ui.dropdown').dropdown({
                    on: 'click'
                });
                $('#checkbox_aono_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').hide();
                    }
                });

                $('#checkbox_aonr_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').show();
                    }
                });

                $('#checkbox_aoio_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').hide();
                    }
                });

                $('#checkbox_aoir_edit').change(function () {
                    if ($(this).is(':checked')) {
                        $('#field_asmi_edit').show();
                    }
                });
                $('#edit_callOffer.ui.modal').modal('show');
                execute_edit(id);
                $('#edit_callOffer_btn').click(function () {
                    $('#block_details').hide();
                    $('#block_form_edit').show();
                    $('#cancel_edit_callOffer').show();
                    $('#submit_edit_callOffer').show();
                    $(this).hide();
                });
                $('#cancel_edit_callOffer').click(function () {
                    $('#block_details').show();
                    $('#block_form_edit').hide();
                    $('#edit_callOffer_btn').show();
                    $('#submit_edit_callOffer').hide();
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

function enable_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_callOffer.ui.modal').modal('hide');
    $('#edit_callOffer').remove();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('call_offer_update', {id: id}),
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
                $('#message_error>div.header').html("Echec d'activation de l'appel d'offre");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#enable_callOffer_grid' + id).hide();
            $('#disable_callOffer_grid' + id).show();
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

function disable_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_callOffer.ui.modal').modal('hide');
    $('#edit_callOffer').remove();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('call_offer_update', {id: id}),
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
                $('#message_error>div.header').html("Echec de la désactivation de l'appel d'offre");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#disable_callOffer_grid' + id).hide();
            $('#enable_callOffer_grid' + id).show();
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