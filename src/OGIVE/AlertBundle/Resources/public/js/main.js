$(function () {
    var init = 0;
    $('#add_call_offer_btn').click(function () {
        $('#add_call_offer.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true
        });
        $('#add_call_offer.ui.modal').modal('show');
    });

    $('#submit_call_offer').click(function (e) {
        e.preventDefault();
        $('#add_call_offer_form.ui.form').submit();
    });

    $('#add_domain_btn').click(function () {
        $('#add_domain.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true
        });
        $('#add_domain.ui.modal').modal('show');
    });

    $('#submit_domain').click(function (e) {
        e.preventDefault();
        $('#add_domain_form.ui.form').submit();
    });

    $('#show_list_table').click(function () {
        if (init === 0) {
            $('#list_as_grid').hide();
            $('#list_as_table').show();
            init = 1;
        } else if (init === 1) {
            $('#list_as_grid').show();
            $('#list_as_table').hide();
            init = 0;
        }
    });

    $('.ui.sidebar')
            .sidebar({
                //context: $('.bottom.segment'),
                dimPage: false
            })
            .sidebar('setting', 'transition', 'overlay')
            .sidebar('attach events', '.main.menu .mobile_menu.item')
            ;

    $('.ui.dropdown').dropdown({
        on: 'hover'
    });

    $('.ui.accordion')
            .accordion({
            })
            ;

    $('a.item').click(function () {
        $('a.item').removeClass('active');
        $(this).addClass('active');
    });

    $('.message .close')
            .on('click', function () {
                $(this)
                        .closest('.message')
                        .transition('fade')
                        ;
            })
            ;

    $('#add_call_offer_form.ui.form')
            .form({
                fields: {
                    type_procedure: {
                        identifier: 'type_procedure',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez selectionner un type'
                            }
                        ]
                    },
                    reference: {
                        identifier: 'reference',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir une reférence'
                            }
                        ]
                    },
                    object: {
                        identifier: 'object',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir une reférence'
                            }
                        ]
                    },
                    owner: {
                        identifier: 'owner',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez renseigner le maître d\'ouvrage'
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    event.preventDefault();
                    $.ajax({

                    });
                }
            }
            );

    $('#login_form.ui.form')
            .form({
                fields: {
                    _username: {
                        identifier: '_username',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir votre matricule ou votre email valide'
                            }
                        ]
                    },
                    _password: {
                        identifier: '_password',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir votre mot de passe'
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur'
                        /*onSuccess: function (event, fields) {
                         event.preventDefault();
                         $.ajax({
                         
                         });
                         }*/
            }
            );

    $('#register_form.ui.form')
            .form({
                fields: {
                    lastname: {
                        identifier: 'lastname',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le nom'
                            }
                        ]
                    },
                    username: {
                        identifier: 'username',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir le matricule'
                            }
                        ]
                    },
                    email: {
                        identifier: 'email',
                        rules: [
                            {
                                type: 'email',
                                prompt: 'Veuillez saisir une adresse email valide'
                            }
                        ]
                    },
                    password: {
                        identifier: 'password',
                        rules: [
                            {
                                type: 'empty',
                                prompt: 'Veuillez saisir un mot de passe'
                            }
                        ]
                    },
                    passwordConfirm: {
                        identifier: 'passwordConfirm',
                        rules: [
                            {
                                type: 'match[password]',
                                prompt: 'Le mot de passe saisi ne correspond pas'
                            }
                        ]
                    }
                },
                inline: true,
                on: 'blur'
            }
            );

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
                        url: $('#add_domain_form.ui.form').attr('action'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_domain').addClass('loading');
                            $('#cancel_add_domain').addClass('disabled');
                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                console.log(response);
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    console.log(myerrors.message)
                                    $('#error_name_header').html("Echec de la validation");
                                    for (var i = 0; i < myerrors.length; i++) {
                                        $('#error_name_list').html('<li>' + myerrors.message + '</li>');
                                    }
                                    $('#error_name_message').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            console.log(response);
                            console.log(response.domain_content_grid);
                            console.log(response.domain_content_list);
                            if (response.code === 200) {
                                $('#cancel_add_domain').removeClass('disabled');
                                $('#submit_domain').removeClass('loading');
                                $('#list_as_grid_content').append(response.domain_content_grid);
                                $('#list_as_table_content').append(response.domain_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_domain.ui.modal').modal('hide');
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_domain').removeClass('loading');
                            $('#cancel_add_domain').removeClass('disabled');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_domain(id) {
    $.ajax({
        type: 'PUT',
        url: '/domains/' + id,
        dataType: 'json',
        beforeSend: function () {

        },
        statusCode: {
            500: function (xhr) {

            },
            400: function (response, textStatus, jqXHR) {

            }
        },
        success: function (response, textStatus, jqXHR) {
            if (response.code === 200) {
                $('#edit_domain').remove();
                $('#edit_domain_content').html(response.edit_domain_form);
                $('#edit_domain.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true
                });
                $('#edit_domain.ui.modal').modal('show');
                execute_edit(id);
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {

            /*alertify.error("Internal Server Error");*/
        }
    });
}

function execute_edit(id) {
    $('#submit_edit_domain').click(function (e) {
        e.preventDefault();
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
                        url: $('#edit_domain_form.ui.form').attr('action'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_domain').addClass('loading');
                            $('#cancel_edit_domain').addClass('disabled');
                        },
                        statusCode: {
                            500: function (xhr) {
                                $('#server_error_message_edit').show();
                            },
                            400: function (response, textStatus, jqXHR) {
                                var myerrors = response.responseJSON;
                                if (myerrors.success === false) {
                                    $('#error_name_header_edit').html("Echec de la validation");
                                    for (var i = 0; i < myerrors.length; i++) {
                                        $('#error_name_list_edit').html('<li>' + myerrors.message + '</li>');
                                    }
                                    $('#error_name_message_edit').show();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.code === 200) {
                                $('#cancel_edit_domain').removeClass('disabled');
                                $('#submit_edit_domain').removeClass('loading');
                                $('#domain_grid' + id).html(response.domain_content_grid);
                                $('#domain_list' + id).html(response.domain_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_domain.ui.modal').modal('hide');
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_domain').removeClass('loading');
                            $('#cancel_edit_domain').removeClass('disabled');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_domain(id) {
    $.ajax({
        type: 'DELETE',
        url: '/domains/' + id,
        dataType: 'json',
        beforeSend: function () {

        },
        statusCode: {
            500: function (xhr) {
                $('#server_error_message_delete').show();
            },
            400: function (response, textStatus, jqXHR) {

            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#domain_grid' + id).remove();
            $('#domain_list' + id).remove();
            $('.ui.dropdown').dropdown({
                on: 'hover'
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {

            /*alertify.error("Internal Server Error");*/
        }
    });
}