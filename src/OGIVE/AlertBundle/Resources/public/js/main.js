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
            $('#list_call_offer_as_table').show();
            init = 1;
        } else if (init === 1) {
            $('#list_as_grid').show();
            $('#list_call_offer_as_table').hide();
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
                            $('#cancel_add_domain').hide;
                        },
                        success: function (response, textStatus, jqXHR) {
                            $('#cancel_add_domain').show;
                            $('#submit_domain').removeClass('loading');
                            console.log(response);
                            /*if (response.success === true) {
                                alertify.success(response.data.message);
                            } else {
                                alertify.error(response.data.message);
                            }*/
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_domain').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});


