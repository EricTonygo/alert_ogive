$(function () {
    $.datetimepicker.setLocale('fr');
    $('#publicationDate').datetimepicker({
        timepicker: false,
        //minDate: '0',
        format: 'd-m-Y',
        lang: 'fr',
        scrollInput: false
    });
    $("#openingDate").datetimepicker({
        minDate: '0',
        format: 'd-m-Y H:i',
        lang: 'fr',
        scrollInput: false
    });
    $("#deadline").datetimepicker({
        minDate: '0',
        format: 'd-m-Y H:i',
        lang: 'fr',
        scrollInput: false
    });
    $('#checkbox_aao_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_aao_add').show();
            $('#field_asmi_add').hide();
            $('#field_asmi_add>.ui.dropdown').dropdown('clear');
        }
    });

    $('#checkbox_asmi_add').change(function () {
        if ($(this).is(':checked')) {
            $('#field_asmi_add').show();
            $('#field_aao_add').hide();
            $('#field_aao_add>.ui.dropdown').dropdown('clear');
        }
    });

    $('#ogive_alertbundle_additive_callOffer.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#ogive_alertbundle_additive_expressionInterest.ui.dropdown').dropdown({
        on: 'click'
    });

    $('#ogive_alertbundle_additive_domain.ui.dropdown').dropdown({
        on: 'click'
    });

    $('#ogive_alertbundle_additive_subDomain.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#cancel_add_additive').click(function () {
        window.location.reload();
    });
    $('#add_additive_btn').click(function () {
        $('#add_additive.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_additive.ui.modal').modal('show');
    });


    $('#add_additive_form.ui.form')
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
                    publication_date: {
                        identifier: 'publication_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de publication de l'additif"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'change'
//                onSuccess: function (event, fields) {
//                    $.ajax({
//                        type: 'post',
//                        url: Routing.generate('additive_add'),
//                        data: fields,
//                        dataType: 'json',
//                        beforeSend: function () {
//                            $('#submit_additive').addClass('disabled');
//                            $('#cancel_add_additive').addClass('disabled');
//                            $('#add_additive_form.ui.form').addClass('loading');
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
//                                    $('#error_name_header').html("Echec de la validation. Veuillez verifier à nouveau vos données");
//                                    $('#error_name_message').show();
//                                }
//                            }
//                        },
//                        success: function (response, textStatus, jqXHR) {
//
//                            $('#cancel_add_additive').removeClass('disabled');
//                            $('#submit_additive').removeClass('disabled');
//                            $('#add_additive_form.ui.form').removeClass('loading');
////                                $('#list_as_grid_content').prepend(response.additive_content_grid);
////                                $('#list_as_table_content').prepend(response.additive_content_list);
////                                $('.ui.dropdown').dropdown({
////                                    on: 'hover'
////                                });
//                            $('#add_additive.ui.modal').modal('hide');
//                            $('#message_success>div.header').html(response.message);
//                            $('#message_success').show();
//                            window.location.replace(Routing.generate('additive_index'));
//                            setTimeout(function () {
//                                $('#message_success').hide();
//                            }, 4000);
//
//
//                        },
//                        error: function (jqXHR, textStatus, errorThrown) {
//                            $('#cancel_add_additive').removeClass('disabled');
//                            $('#submit_additive').removeClass('disabled');
//                            $('#add_additive_form.ui.form').removeClass('loading');
//                            /*alertify.error("Internal Server Error");*/
//                        }
//                    });
//                    return false;
//                }
            }
            );
    $('#submit_additive').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        if ($('#add_additive_form.ui.form').form('is valid')) {
            $.ajax({
                type: 'post',
                url: Routing.generate('additive_add'),
                data: {'testunicity': 'yes', 'reference': $('#add_additive_form.ui.form input[name*="reference"]').val()},
                dataType: 'json',
                beforeSend: function () {
                    $('#submit_additive').addClass('disabled');
                    $('#cancel_add_additive').addClass('disabled');
                    $('#add_additive_form.ui.form').addClass('loading');
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
                            $('#error_name_header').html("Echec de la validation. Veuillez verifier à nouveau vos données");
                            $('#error_name_message').show();
                        }
                    }
                },
                success: function (response, textStatus, jqXHR) {
                    $('#add_additive_form.ui.form').submit();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#cancel_add_additive').removeClass('disabled');
                    $('#submit_additive').removeClass('disabled');
                    $('#add_additive_form.ui.form').removeClass('loading');
                    /*alertify.error("Internal Server Error");*/
                }
            });
        }
    });
});

function edit_additive(id) {
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
        url: Routing.generate('additive_update', {id: id}),
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
            $('#edit_additive').remove();
            $('#edit_additive_content').html(response.edit_additive_form);
            $('#edit_additive.ui.modal').modal('setting', {
                autofocus: false,
                inverted: true,
                closable: false
            });

            $('#ogive_alertbundle_additive_callOffer.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_additive_expressionInterest.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_additive_domain.ui.dropdown').dropdown({
                on: 'click'
            });

            $('#ogive_alertbundle_additive_subDomain.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#checkbox_aao_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_aao_edit').show();
                    $('#field_asmi_edit').hide();
                    $('#field_asmi_edit>.ui.dropdown').dropdown('clear');
                }
            });

            $('#checkbox_asmi_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_asmi_edit').show();
                    $('#field_aao_edit').hide();
                    $('#field_aao_edit>.ui.dropdown').dropdown('clear');
                }
            });
            $('#cancel_edit_additive').click(function () {
                window.location.reload();
            });

            $('#edit_additive.ui.modal').modal('show');
            execute_edit(id);
            $("#openingDate_edit").datetimepicker({
                minDate: '0',
                format: 'd-m-Y H:i',
                lang: 'fr',
                scrollInput: false
            });
            $("#deadline_edit").datetimepicker({
                minDate: '0',
                format: 'd-m-Y H:i',
                lang: 'fr',
                scrollInput: false
            });
            $('#edit_callOffer_btn').click(function () {
                $('#block_details').hide();
                $('#block_form_edit').show();
                $('#cancel_edit_callOffer').show();
                $('#submit_edit_callOffer').show();
                $(this).hide();
            });
            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
            $('#message_error').show();
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function execute_edit(id) {

    $('#edit_additive_form.ui.form')
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
                    publication_date: {
                        identifier: 'publication_date',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez renseigner la date de publication de l'additif"
                            }
                        ]
                    }
                },
                inline: true,
                on: 'change'
//                onSuccess: function (event, fields) {
//                    $.ajax({
//                        type: 'PUT',
//                        url: Routing.generate('additive_update', {id: id}),
//                        data: fields,
//                        dataType: 'json',
//                        beforeSend: function () {
//                            $('#submit_edit_additive').addClass('disabled');
//                            $('#cancel_edit_additive').addClass('disabled');
//                            $('#edit_additive_form.ui.form').addClass('loading');
//                            $('#cancel_details_additive').addClass('disabled');
//                            $('#disable_additive').addClass('disabled');
//                            $('#enable_additive').addClass('disabled');
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
//                                    $('#error_name_header_edit').html("Echec de la validation. Veuillez vérifier vos données");
//                                    $('#error_name_message_edit').show();
//                                }
//
//                            }
//                        },
//                        success: function (response, textStatus, jqXHR) {
//                            $('#submit_edit_additive').removeClass('disabled');
//                            $('#cancel_edit_additive').removeClass('disabled');
//                            $('#edit_additive_form.ui.form').removeClass('loading');
//                            $('#cancel_details_additive').removeClass('disabled');
//                            $('#disable_additive').removeClass('disabled');
//                            $('#enable_additive').removeClass('disabled');
////                                $('#additive_grid' + id).html(response.additive_content_grid);
////                                $('#additive_list' + id).html(response.additive_content_list);
////                                $('.ui.dropdown').dropdown({
////                                    on: 'hover'
////                                });
//
//                            $('#edit_additive.ui.modal').modal('hide');
//                            $('#message_success>div.header').html(response.message);
//                            $('#message_success').show();
//                            window.location.replace(Routing.generate('additive_index'));
//                            setTimeout(function () {
//                                $('#message_success').hide();
//                            }, 4000);
//                            $('#edit_additive').remove();
//
//
//                        },
//                        error: function (jqXHR, textStatus, errorThrown) {
//                            $('#submit_edit_additive').removeClass('disabled');
//                            $('#cancel_edit_additive').removeClass('disabled');
//                            $('#edit_additive_form.ui.form').removeClass('loading');
//                            /*alertify.error("Internal Server Error");*/
//                        }
//                    });
//                    return false;
//                }
            }
            );

    $('#submit_edit_additive').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
        if ($('#edit_additive_form.ui.form').form('is valid')) {
            $.ajax({
                type: 'PUT',
                url: Routing.generate('additive_update', {id: id}),
                data: {'testunicity': 'yes', 'reference': $('#edit_additive_form.ui.form input[name*="reference"]').val()},
                dataType: 'json',
                beforeSend: function () {
                    $('#submit_edit_additive').addClass('disabled');
                    $('#cancel_edit_additive').addClass('disabled');
                    $('#edit_additive_form.ui.form').addClass('loading');
                    $('#cancel_details_additive').addClass('disabled');
                    $('#disable_additive').addClass('disabled');
                    $('#enable_additive').addClass('disabled');
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
                    $('#edit_additive_form.ui.form').submit();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $('#submit_edit_additive').removeClass('disabled');
                    $('#cancel_edit_additive').removeClass('disabled');
                    $('#edit_additive_form.ui.form').removeClass('loading');
                }
            });
        }
    });
}

function delete_additive(id) {
    $('#confirm_delete_additive.ui.small.modal')
            .modal('show')
            ;

    $('#execute_delete_additive').click(function (e) {
        e.preventDefault();
        $('#confirm_delete_additive.ui.small.modal')
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
            url: Routing.generate('additive_delete', {id: id}),
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
                $('#additive_grid' + id).remove();
                $('#additive_list' + id).remove();
                $('#message_loading').hide();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.replace(Routing.generate('additive_index'));
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

function show_additive(id) {
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
        url: Routing.generate('additive_get_one', {id: id}),
        dataType: 'json',
        beforeSend: function () {
            $('#message_loading').show();
        },
        statusCode: {
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
            $('#edit_additive').remove();
            $('#edit_additive_content').html(response.additive_details);
            $('#edit_additive.ui.modal').modal('setting', {
                autofocus: false,
                inverted: true,
                closable: false
            });
            $('#ogive_alertbundle_additive_domain.ui.dropdown').dropdown({
                on: 'click'
            });

            $('#ogive_alertbundle_additive_subDomain.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_additive_callOffer.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#ogive_alertbundle_additive_expressionInterest.ui.dropdown').dropdown({
                on: 'click'
            });
            $('#checkbox_aao_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_aao_edit').show();
                    $('#field_asmi_edit').hide();
                    $('#field_asmi_edit>.ui.dropdown').dropdown('clear');
                }
            });

            $('#checkbox_asmi_edit').change(function () {
                if ($(this).is(':checked')) {
                    $('#field_asmi_edit').show();
                    $('#field_aao_edit').hide();
                    $('#field_aao_edit>.ui.dropdown').dropdown('clear');
                }
            });
            $('#cancel_details_additive').click(function () {
                window.location.reload();
            });
            $('#edit_additive.ui.modal').modal('show');
            execute_edit(id);
            $("#openingDate_edit").datetimepicker({
                minDate: '0',
                format: 'd-m-Y H:i',
                lang: 'fr',
                scrollInput: false
            });
            $("#deadline_edit").datetimepicker({
                minDate: '0',
                format: 'd-m-Y H:i',
                lang: 'fr',
                scrollInput: false
            });
            $('#edit_callOffer_btn').click(function () {
                $('#block_details').hide();
                $('#block_form_edit').show();
                $('#cancel_edit_callOffer').show();
                $('#submit_edit_callOffer').show();
                $(this).hide();
            });
            $('#edit_additive_btn').click(function () {
                $('#block_details').hide();
                $('#block_form_edit').show();
                $('#cancel_edit_additive').show();
                $('#submit_edit_additive').show();
                $(this).hide();
            });
            $('#cancel_edit_additive').click(function () {
                $('#block_details').show();
                $('#block_form_edit').hide();
                $('#edit_additive_btn').show();
                $('#submit_edit_additive').hide();
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

function enable_additive(id) {
    $('#confirm_enable_additive.ui.small.modal')
            .modal('show')
            ;

    $('#execute_enable_additive').click(function (e) {
        e.preventDefault();
        $('#confirm_enable_additive.ui.small.modal')
                .modal('hide')
                ;
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_additive.ui.modal').modal('hide');
        $('#edit_additive').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
        $('.ui.dropdown').dropdown({
            on: 'hover'
        });
        $.ajax({
            type: 'PUT',
            url: Routing.generate('additive_update', {id: id}),
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
                    $('#message_error>div.header').html("Echec d'activation de l'additif");
                    $('#message_error').show();

                }
            },
            success: function (response, textStatus, jqXHR) {

                $('#message_loading').hide();
                $('#enable_additive_grid' + id).hide();
                $('#disable_additive_grid' + id).show();
                $('#message_success>div.header').html(response.message);
                $('#message_success').show();
                window.location.reload();
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

function disable_additive(id) {
    $('#confirm_disable_additive.ui.small.modal')
            .modal('show')
            ;

    $('#execute_disable_additive').click(function (e) {
        e.preventDefault();
        $('#confirm_disable_additive.ui.small.modal')
                .modal('hide')
                ;
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_additive.ui.modal').modal('hide');
        $('#edit_additive').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
        $('.ui.dropdown').dropdown({
            on: 'hover'
        });
        $.ajax({
            type: 'PUT',
            url: Routing.generate('additive_update', {id: id}),
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
                    $('#message_error>div.header').html("Echec de la désactivation de l'additif");
                    $('#message_error').show();

                }
            },
            success: function (response, textStatus, jqXHR) {

                $('#message_loading').hide();
                $('#disable_additive_grid' + id).hide();
                $('#enable_additive_grid' + id).show();
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