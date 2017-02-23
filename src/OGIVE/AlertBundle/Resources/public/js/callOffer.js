$(function () {
    $('#field_asmi_add').hide();
    $('#checkbox_aono_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_asmi_add').hide();
            $('#field_asmi_add>.ui.dropdown').dropdown('clear');
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
            $('##field_asmi_add>.ui.dropdown').dropdown('clear');
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
    $('#ogive_alertbundle_calloffer_subDomain.ui.dropdown').dropdown({
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


    $('#cancel_add_callOffer').click(function () {
        window.location.replace(Routing.generate('call_offer_index'));
    });

    $('#add_callOffer_form.ui.form')
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
                on: 'change'
//                onSuccess: function (event, fields) {
//                    $.ajax({
//                        type: 'post',
//                        url: Routing.generate('call_offer_add'),
//                        data: $('#add_callOffer_form.ui.form').serialize(),
//                        dataType: 'json',
//                        processData: false,
//                        //contentType: false,
//                        cache: false,
//                        beforeSend: function () {
//                            $('#submit_callOffer').addClass('disabled');
//                            $('#cancel_add_callOffer').addClass('disabled');
//                            $('#add_callOffer_form.ui.form').addClass('loading');
//                        },
//                        statusCode: {
//                            500: function (xhr) {
//                                $('#server_error_message').show();
//                            },
//                            400: function (response, textStatus, jqXHR) {
//                                var myerrors = response.responseJSON;
//                                if (myerrors.success === false) {
//                                    $('#error_name_header').html("Echec de la validation");
//                                    $('#error_name_list').html('<li>' + myerrors.message + '</li>');
//                                    $('#error_name_message').show();
//                                } else {
//                                    $('#error_name_header').html("Echec de la validation");
//                                    $('#error_name_message').show();
//                                }
//
//                            }
//                        },
//                        success: function (response, textStatus, jqXHR) {
//                            $('#cancel_add_callOffer').removeClass('disabled');
//                            $('#submit_callOffer').removeClass('disabled');
//                            $('#add_callOffer_form.ui.form').removeClass('loading');
////                                $('#list_as_grid_content').prepend(response.callOffer_content_grid);
////                                $('#list_as_table_content').prepend(response.callOffer_content_list);
////                                $('.ui.dropdown').dropdown({
////                                    on: 'hover'
////                                });
//                            $('#add_callOffer.ui.modal').modal('hide');
//                            $('#message_success>div.header').html(response.message);
//                            $('#message_success').show();
//                            window.location.replace(Routing.generate('call_offer_index'));
//                            setTimeout(function () {
//                                $('#message_success').hide();
//                            }, 4000);
//                        },
//                        error: function (jqXHR, textStatus, errorThrown) {
//                            $('#cancel_add_callOffer').removeClass('disabled');
//                            $('#submit_callOffer').removeClass('disabled');
//                            $('#add_callOffer_form.ui.form').removeClass('loading');
//                            /*alertify.error("Internal Server Error");*/
//                        }
//                    });
//                    return false;
//                }
            });
    $('#submit_callOffer').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        if ($('#add_callOffer_form.ui.form').form('is valid')) {
            $.ajax({
                type: 'post',
                url: Routing.generate('call_offer_add'),
                data: {'testunicity': 'yes', 'reference': $('#add_callOffer_form.ui.form input[name*="reference"]').val()},
                dataType: 'json',
                processData: false,
                //contentType: false,
                cache: false,
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
                        var myerrors = response.responseJSON;
                        if (myerrors.success === false) {
                            $('#error_name_header').html("Echec de la validation");
                            $('#error_name_list').html('<li>' + myerrors.message + '</li>');
                            $('#error_name_message').show();
                        } else {
                            $('#error_name_header').html("Echec de la validation");
                            $('#error_name_message').show();
                        }

                    }
                },
                success: function (response, textStatus, jqXHR) {
                    $('#add_callOffer_form.ui.form').submit();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#cancel_add_callOffer').removeClass('disabled');
                    $('#submit_callOffer').removeClass('disabled');
                    $('#add_callOffer_form.ui.form').removeClass('loading');
                }
            });
        }
    });

});



function edit_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
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
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
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

            $('#ogive_alertbundle_calloffer_subDomain.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#checkbox_aono_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_asmi_edit').hide();
                    $('#field_asmi_edit>.ui.dropdown').dropdown('clear');
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
                    $('#field_asmi_edit>.ui.dropdown').dropdown('clear');
                }
            });

            $('#checkbox_aoir_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_asmi_edit').show();
                }
            });
            $('#cancel_edit_callOffer').click(function () {
                window.location.replace(Routing.generate('call_offer_index'));
            });
            $('#edit_callOffer.ui.modal').modal('show');
            execute_edit(id);

            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function execute_edit(id) {
    $('#edit_callOffer_form.ui.form')
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
                on: 'change'
//                onSuccess: function (event, fields) {
//                    $.ajax({
//                        type: 'PUT',
//                        url: Routing.generate('call_offer_update', {id: id}),
//                        data: $('#edit_callOffer_form.ui.form').serialize(),
//                        dataType: 'json',
//                        processData: false,
//                        //contentType: false,
//                        cache: false,
//                        beforeSend: function () {
//                            $('#submit_edit_callOffer').addClass('disabled');
//                            $('#cancel_edit_callOffer').addClass('disabled');
//                            $('#edit_callOffer_form.ui.form').addClass('loading');
//                            $('#cancel_details_callOffer').addClass('disabled');
//                            $('#disable_callOffer').addClass('disabled');
//                            $('#enable_callOffer').addClass('disabled');
//                        },
//                        statusCode: {
//                            500: function (xhr) {
//                                $('#server_error_message_edit').show();
//                            },
//                            400: function (response, textStatus, jqXHR) {
//                                var myerrors = response.responseJSON;
//                                if (myerrors.success === false) {
//                                    $('#error_name_header_edit').html("Echec de la validation");
//                                    $('#error_name_list_edit').html('<li>' + myerrors.message + '</li>');
//                                    $('#error_name_message_edit').show();
//                                } else {
//                                    $('#error_name_header_edit').html("Echec de la validation");
//                                    $('#error_name_message_edit').show();
//                                }
//
//                            }
//                        },
//                        success: function (response, textStatus, jqXHR) {
//                            $('#submit_edit_callOffer').removeClass('disabled');
//                            $('#cancel_edit_callOffer').removeClass('disabled');
//                            $('#edit_callOffer_form.ui.form').removeClass('loading');
//                            $('#cancel_details_callOffer').removeClass('disabled');
//                            $('#disable_callOffer').removeClass('disabled');
//                            $('#enable_callOffer').removeClass('disabled');
////                                $('#callOffer_grid' + id).html(response.callOffer_content_grid);
////                                $('#callOffer_list' + id).html(response.callOffer_content_list);
////                                $('.ui.dropdown').dropdown({
////                                    on: 'hover'
////                                });
//                            $('#edit_callOffer.ui.modal').modal('hide');
//                            $('#message_success>div.header').html(response.message);
//                            $('#message_success').show();
//                            window.location.replace(Routing.generate('call_offer_index'));
//                            setTimeout(function () {
//                                $('#message_success').hide();
//                            }, 4000);
//                            $('#edit_callOffer').remove();
//
//
//                        },
//                        error: function (jqXHR, textStatus, errorThrown) {
//                            $('#submit_edit_callOffer').removeClass('disabled');
//                            $('#cancel_edit_callOffer').removeClass('disabled');
//                            $('#edit_callOffer_form.ui.form').removeClass('loading');
//                            /*alertify.error("Internal Server Error");*/
//                        }
//                    });
//                    return false;
//                }
            }
            );
    $('#submit_edit_callOffer').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        if ($('#edit_callOffer_form.ui.form').form('is valid')) {
            $.ajax({
                type: 'PUT',
                url: Routing.generate('call_offer_update', {id: id}),
                data: {'testunicity': 'yes', 'reference': $('#edit_callOffer_form.ui.form input[name*="reference"]').val()},
                dataType: 'json',
                processData: false,
                //contentType: false,
                cache: false,
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
                        } else {
                            $('#error_name_header_edit').html("Echec de la validation");
                            $('#error_name_message_edit').show();
                        }

                    }
                },
                success: function (response, textStatus, jqXHR) {
                    $('#edit_callOffer_form.ui.form').submit();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#submit_edit_callOffer').removeClass('disabled');
                    $('#cancel_edit_callOffer').removeClass('disabled');
                    $('#edit_callOffer_form.ui.form').removeClass('loading');
                    /*alertify.error("Internal Server Error");*/
                }
            });
        }
    });
}

function delete_callOffer(id) {
    $('#confirm_delete_callOffer.ui.small.modal')
            .modal('show');

    $('#execute_delete_callOffer').click(function (e) {
        e.preventDefault();
        $('#confirm_delete_callOffer.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
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
                404: function (response, textStatus, jqXHR) {
                    $('#message_error>div.header').html(response.responseJSON.message);
                    $('#message_error').show();
                    setTimeout(function () {
                        $('#message_error').hide();
                    }, 4000);
                }
            },
            success: function (response, textStatus, jqXHR) {
                $('#callOffer_grid' + id).remove();
                $('#callOffer_list' + id).remove();
                $('#message_loading').hide();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.replace(Routing.generate('call_offer_index'));
                setTimeout(function () {
                    $('#message_success').hide();
                }, 4000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#message_loading').hide();
                /*alertify.error("Internal Server Error");*/
            }
        });
    });
}

function show_callOffer(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
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
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
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
            $('#ogive_alertbundle_calloffer_subDomain.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#checkbox_aono_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_asmi_edit').hide();
                    $('#field_asmi_edit>.ui.dropdown').dropdown('clear');
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
                    $('#field_asmi_edit>.ui.dropdown').dropdown('clear');
                }
            });

            $('#checkbox_aoir_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_asmi_edit').show();
                }
            });
            $('#cancel_details_callOffer').click(function () {
                window.location.replace(Routing.generate('call_offer_index'));
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

            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function enable_callOffer(id) {
    $('#confirm_enable_callOffer.ui.small.modal')
            .modal('show');

    $('#execute_enable_callOffer').click(function (e) {
        e.preventDefault();
        $('#confirm_enable_callOffer.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_callOffer.ui.modal').modal('hide');
        $('#edit_callOffer').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
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
                404: function (response, textStatus, jqXHR) {
                    $('#message_error>div.header').html("Echec d'activation de l'appel d'offre");
                    $('#message_error').show();
                    setTimeout(function () {
                        $('#message_error').hide();
                    }, 4000);
                }
            },
            success: function (response, textStatus, jqXHR) {
                $('#message_loading').hide();
                $('#enable_callOffer_grid' + id).hide();
                $('#disable_callOffer_grid' + id).show();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.replace(Routing.generate('call_offer_index'));
                setTimeout(function () {
                    $('#message_success').hide();
                }, 4000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#message_loading').hide();
                /*alertify.error("Internal Server Error");*/
            }
        });
    });
}

function disable_callOffer(id) {
    $('#confirm_disable_callOffer.ui.small.modal')
            .modal('show');

    $('#execute_disable_callOffer').click(function (e) {
        e.preventDefault();
        $('#confirm_disable_callOffer.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_callOffer.ui.modal').modal('hide');
        $('#edit_callOffer').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
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
                404: function (response, textStatus, jqXHR) {
                    $('#message_error>div.header').html("Echec de la désactivation de l'appel d'offre");
                    $('#message_error').show();
                    setTimeout(function () {
                        $('#message_error').hide();
                    }, 4000);
                }
            },
            success: function (response, textStatus, jqXHR) {
                $('#message_loading').hide();
                $('#disable_callOffer_grid' + id).hide();
                $('#enable_callOffer_grid' + id).show();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.replace(Routing.generate('call_offer_index'));
                setTimeout(function () {
                    $('#message_success').hide();
                }, 4000);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('#message_loading').hide();
                /*alertify.error("Internal Server Error");*/
            }
        });
    });
}

