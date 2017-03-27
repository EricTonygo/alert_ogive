$(function () {
    $.datetimepicker.setLocale('fr');
    $('#renewal_subscription_subcriber_date').datetimepicker({
        //timepicker: false,
        //minDate: '0',
        format: 'd-m-Y H:i',
        lang: 'fr',
        scrollInput: false
    });
    $('#ogive_alertbundle_subscriber_entreprise.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#ogive_alertbundle_subscriber_subscription.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#cancel_add_subscriber').click(function () {
        window.location.replace(Routing.generate('subscriber_index'));
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
        $('#error_name_message_edit').hide();
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
                            window.location.replace(Routing.generate('subscriber_index'));
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);

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
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
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
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
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
            $('#cancel_edit_subscriber').click(function () {
                window.location.replace(Routing.generate('subscriber_index'));
            });
            $('#cancel_details_subscriber').click(function () {
                window.location.replace(Routing.generate('subscriber_index'));
            });
            $('#edit_subscriber.ui.modal').modal('show');
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
    $('#submit_edit_subscriber').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#error_name_message_edit').hide();
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
                                } else {
                                    $('#error_name_header_edit').html("Echec de la validation. Veuillez vérifier vos données");
                                    $('#error_name_message_edit').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
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
                            window.location.replace(Routing.generate('subscriber_index'));
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);
                            $('#edit_subscriber').remove();

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_subscriber').removeClass('disabled');
                            $('#cancel_edit_subscriber').removeClass('disabled');
                            $('#edit_subscriber_form.ui.form').removeClass('loading');
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_subscriber(id) {
    $('#confirm_delete_subscriber.ui.small.modal')
            .modal('show');

    $('#execute_delete_subscriber').click(function (e) {
        e.preventDefault();
        $('#confirm_delete_subscriber.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
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
                404: function (response, textStatus, jqXHR) {
                    $('#message_error>div.header').html(response.responseJSON.message);
                    $('#message_error').show();
                    window.location.replace(Routing.generate('subscriber_index'));
                    setTimeout(function () {
                        $('#message_error').hide();
                    }, 4000);
                }
            },
            success: function (response, textStatus, jqXHR) {
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
                $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        });
    });
}

function show_subscriber(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('.ui.dropdown').dropdown('remove active');
    $('.ui.dropdown').dropdown('remove visible');
    $('.ui.dropdown>div.menu').removeClass('visible');
    $('.ui.dropdown>div.menu').addClass('hidden');
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
            404: function (response, textStatus, jqXHR) {
                $('#message_error>div.header').html(response.responseJSON.message);
                $('#message_error').show();
            }
        },
        success: function (response, textStatus, jqXHR) {
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

            $('#cancel_details_subscriber').click(function () {
                window.location.replace(Routing.generate('subscriber_index'));
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

            $('#message_loading').hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#message_loading').hide();
            $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
            $('#message_error').show();
            setTimeout(function () {
                $('#message_error').hide();
            }, 4000);
            /*alertify.error("Internal Server Error");*/
        }
    });
}

function renewal_subscription_subscriber(id) {
    $('#subscription_type').val(id).change();
    $('#renewal_subscription_subscriber.ui.small.modal')
            .modal('show')
            ;
    $('#renewal_subscription_subcriber_form.ui.form')
            .form({
                fields: {
                    renewal_subscription_subcriber_date: {
                        identifier: 'renewal_subscription_subcriber_date',
                        rules: [
                            {
                                type: 'empty'
                            }
                        ]
                    },
                    subscription_type: {
                        identifier: 'subscription_type',
                        rules: [
                            {
                                type: 'empty'
                            }
                        ]
                    }
                },
                onSuccess: function (event, fields) {
//                $('#renewal_subscription_subscriber.ui.small.modal')
//                        .modal('hide');
                    $('#message_error').hide();
                    $('#message_success').hide();
                    $('#edit_subscriber.ui.modal').modal('hide');
                    $('#edit_subscriber').remove();
                    $('.ui.dropdown').dropdown('remove active');
                    $('.ui.dropdown').dropdown('remove visible');
                    $('.ui.dropdown>div.menu').removeClass('visible');
                    $('.ui.dropdown>div.menu').addClass('hidden');
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('subscriber_update', {id: id}),
                        data: {'action': 'renewal-subscription', 'renewal_subscription_subcriber_date': $("#renewal_subscription_subcriber_form.ui.form input[name='renewal_subscription_subcriber_date']").val()},
                        dataType: 'json',
                        beforeSend: function () {
                            //$('#message_loading').show();
                            $('#execute_renewal_subscription_subcriber').addClass('disabled');
                            $('#cancel_renewal_subscription_subcriber').addClass('disabled');
                            $('#renewal_subscription_subscriber.ui.form').addClass('loading');
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
                                var myerrors = response.responseJSON;
                                $('#message_error>div.header').html(myerrors.message);
                                $('#message_error').show();
                                setTimeout(function () {
                                    $('#message_error').hide();
                                }, 4000);
                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#renewal_subscription_subscriber.ui.small.modal')
                                    .modal('hide');
                            $('#execute_renewal_subscription_subcriber').removeClass('disabled');
                            $('#cancel_renewal_subscription_subcriber').removeClass('disabled');
                            $('#renewal_subscription_subscriber.ui.form').removeClass('loading');
                            $('#message_success>div.header').html(response.message);
                            $('#message_success').show();
                            window.location.replace(Routing.generate('subscriber_index'));
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#message_loading').hide();
                            $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                            $('#message_error').show();
                            setTimeout(function () {
                                $('#message_error').hide();
                            }, 4000);
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            });
    $('#execute_enable_subscriber').click(function (e) {
        e.preventDefault();
        $('#renewal_subscription_subcriber_form.ui.form').submit();
    });
}


function enable_subscriber(id) {
    $('#confirm_enable_subscriber.ui.small.modal')
            .modal('show')
            ;
    $('#enable_subscriber_form.ui.form')
            .form({
                fields: {
                    subscription_update: {
                        identifier: 'subscription_update',
                        rules: [
                            {
                                type: 'checked'
                            }
                        ]
                    }
                },
                onSuccess: function (event, fields) {
                    $('#confirm_enable_subscriber.ui.small.modal')
                            .modal('hide');
                    $('#message_error').hide();
                    $('#message_success').hide();
                    $('#edit_subscriber.ui.modal').modal('hide');
                    $('#edit_subscriber').remove();
                    $('.ui.dropdown').dropdown('remove active');
                    $('.ui.dropdown').dropdown('remove visible');
                    $('.ui.dropdown>div.menu').removeClass('visible');
                    $('.ui.dropdown>div.menu').addClass('hidden');
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('subscriber_update', {id: id}),
                        data: {'action': 'enable', 'subscription_update': $("#enable_subscriber_form.ui.form input[name='subscription_update']:checked").val()},
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
                                var myerrors = response.responseJSON;
                                $('#message_error>div.header').html(myerrors.message);
                                $('#message_error').show();
                                setTimeout(function () {
                                    $('#message_error').hide();
                                }, 4000);
                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#message_loading').hide();
                            $('#enable_subscriber_grid' + id).hide();
                            $('#disable_subscriber_grid' + id).show();
                            $('#message_success>div.header').html(response.message);
                            $('#message_success').show();
                            window.location.replace(Routing.generate('subscriber_index'));
                            setTimeout(function () {
                                $('#message_success').hide();
                            }, 4000);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#message_loading').hide();
                            $('#message_error>div.header').html("Erreur s'est produite au niveau du serveur");
                            $('#message_error').show();
                            setTimeout(function () {
                                $('#message_error').hide();
                            }, 4000);
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            });
    $('#execute_enable_subscriber').click(function (e) {
        e.preventDefault();
        $('#enable_subscriber_form.ui.form').submit();
    });
}

function disable_subscriber(id) {
    $('#confirm_disable_subscriber.ui.small.modal')
            .modal('show')
            ;

    $('#execute_disable_subscriber').click(function (e) {
        e.preventDefault();
        $('#confirm_disable_subscriber.ui.small.modal')
                .modal('hide');
        $('#message_error').hide();
        $('#message_success').hide();
        $('#edit_subscriber.ui.modal').modal('hide');
        $('#edit_subscriber').remove();
        $('.ui.dropdown').dropdown('remove active');
        $('.ui.dropdown').dropdown('remove visible');
        $('.ui.dropdown>div.menu').removeClass('visible');
        $('.ui.dropdown>div.menu').addClass('hidden');
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
                404: function (response, textStatus, jqXHR) {
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
                window.location.replace(Routing.generate('subscriber_index'));
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