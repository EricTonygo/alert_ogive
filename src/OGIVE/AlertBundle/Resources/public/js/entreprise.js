$(function () {
    $('#ogive_alertbundle_entreprise_domain.ui.dropdown').dropdown({
        on: 'click'
    });
    $('#add_entreprise_btn').click(function () {
        $('#add_entreprise.ui.modal').modal('setting', {
            autofocus: false,
            inverted: true,
            closable: false
        });
        $('#add_entreprise.ui.modal').modal('show');
    });

    $('#submit_entreprise').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#add_entreprise_form.ui.form').submit();
    });
    $('#add_entreprise_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'entreprise"
                            }
                        ]
                    },
                    phone: {
                        identifier: 'phone',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le numéro de téléphone de l'entreprise"
                            },
                            {
                                type: 'regExp[/^+[0-9]*$/]',
                                prompt: "Veuillez saisir le numéro de téléphone valide"
                            }
                        ]
                    }

                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'post',
                        url: Routing.generate('entreprise_add'),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_entreprise').addClass('disabled');
                            $('#cancel_add_entreprise').addClass('disabled');
                            $('#add_entreprise_form.ui.form').addClass('loading');
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
                                    $('#error_name_message').hide();
                                }

                            }
                        },
                        success: function (response, textStatus, jqXHR) {
                            if (response.code === 200) {
                                $('#cancel_add_entreprise').removeClass('disabled');
                                $('#submit_entreprise').removeClass('disabled');
                                $('#add_entreprise_form.ui.form').removeClass('loading');
                                $('#list_as_grid_content').prepend(response.entreprise_content_grid);
                                $('#list_as_table_content').prepend(response.entreprise_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#add_entreprise.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Entreprise ajoutée avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#cancel_add_entreprise').removeClass('disabled');
                            $('#submit_entreprise').removeClass('disabled');
                            $('#add_entreprise_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
});

function edit_entreprise(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('entreprise_update', {id: id}),
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
                $('#edit_entreprise').remove();
                $('#edit_entreprise_content').html(response.edit_entreprise_form);

                manage_subscriber();
                $('#edit_entreprise.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_entreprise.ui.modal').modal('show');
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
    $('#submit_edit_entreprise').click(function (e) {
        e.preventDefault();
        $('#server_error_message').hide();
        $('#message_error').hide();
        $('#message_success').hide();
        $('#error_name_message').hide();
        $('#edit_entreprise_form.ui.form').submit();
    });
    $('#edit_entreprise_form.ui.form')
            .form({
                fields: {
                    name: {
                        identifier: 'name',
                        rules: [
                            {
                                type: 'empty',
                                prompt: "Veuillez saisir le nom de l'entreprise"
                            }
                        ]
                    }

                },
                inline: true,
                on: 'blur',
                onSuccess: function (event, fields) {
                    $.ajax({
                        type: 'PUT',
                        url: Routing.generate('entreprise_update', {id: id}),
                        data: fields,
                        dataType: 'json',
                        beforeSend: function () {
                            $('#submit_edit_entreprise').addClass('disabled');
                            $('#cancel_edit_entreprise').addClass('disabled');
                            $('#edit_entreprise_form.ui.form').addClass('loading');
                            $('#cancel_details_entreprise').addClass('disabled');
                            $('#disable_entreprise').addClass('disabled');
                            $('#enable_entreprise').addClass('disabled');
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
                                $('#submit_edit_entreprise').removeClass('disabled');
                                $('#cancel_edit_entreprise').removeClass('disabled');
                                $('#edit_entreprise_form.ui.form').removeClass('loading');
                                $('#cancel_details_entreprise').removeClass('disabled');
                                $('#disable_entreprise').removeClass('disabled');
                                $('#enable_entreprise').removeClass('disabled');
                                $('#entreprise_grid' + id).html(response.entreprise_content_grid);
                                $('#entreprise_list' + id).html(response.entreprise_content_list);
                                $('.ui.dropdown').dropdown({
                                    on: 'hover'
                                });
                                $('#edit_entreprise.ui.modal').modal('hide');
                                $('#message_success>div.header').html('Entreprise modifiée avec succès !');
                                $('#message_success').show();
                                setTimeout(function () {
                                    $('#message_success').hide();
                                }, 4000);
                                $('#edit_entreprise').remove();
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $('#submit_edit_entreprise').removeClass('disabled');
                            $('#cancel_edit_entreprise').removeClass('disabled');
                            $('#edit_entreprise_form.ui.form').removeClass('loading');
                            /*alertify.error("Internal Server Error");*/
                        }
                    });
                    return false;
                }
            }
            );
}

function delete_entreprise(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'DELETE',
        url: Routing.generate('entreprise_delete', {id: id}),
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
            $('#entreprise_grid' + id).remove();
            $('#entreprise_list' + id).remove();
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

function show_entreprise(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $.ajax({
        type: 'GET',
        url: Routing.generate('entreprise_get_one', {id: id}),
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
                $('#edit_entreprise').remove();
                $('#edit_entreprise_content').html(response.entreprise_details);
                manage_subscriber();
                $('#edit_entreprise.ui.modal').modal('setting', {
                    autofocus: false,
                    inverted: true,
                    closable: false
                });
                $('#edit_entreprise.ui.modal').modal('show');
                execute_edit(id);
                $('#edit_entreprise_btn').click(function () {
                    $('#block_details').hide();
                    $('#block_form_edit').show();
                    $('#cancel_edit_entreprise').show();
                    $('#submit_edit_entreprise').show();
                    $(this).hide();
                });
                $('#cancel_edit_entreprise').click(function () {
                    $('#block_details').show();
                    $('#block_form_edit').hide();
                    $('#edit_entreprise_btn').show();
                    $('#submit_edit_entreprise').hide();
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

function enable_entreprise(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_entreprise.ui.modal').modal('hide');
    $('#edit_entreprise').remove();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('entreprise_update', {id: id}),
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
                $('#message_error>div.header').html("Echec d'activation de l'entreprise");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#enable_entreprise_grid' + id).hide();
            $('#disable_entreprise_grid' + id).show();
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

function disable_entreprise(id) {
    $('#message_error').hide();
    $('#message_success').hide();
    $('#edit_entreprise.ui.modal').modal('hide');
    $('#edit_entreprise').remove();
    $.ajax({
        type: 'PUT',
        url: Routing.generate('entreprise_update', {id: id}),
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
                $('#message_error>div.header').html("Echec de la désactivation de l'entreprise");
                $('#message_error').show();
                setTimeout(function () {
                    $('#message_error').hide();
                }, 4000);
            }
        },
        success: function (response, textStatus, jqXHR) {
            console.log(response);
            $('#message_loading').hide();
            $('#disable_entreprise_grid' + id).hide();
            $('#enable_entreprise_grid' + id).show();
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

function addSubscriberForm($collectionHolder, $newLinkTr) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var $newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a subscriber" link li
    var $newFormTr = $('<tr></tr>').append($newForm);
    $newLinkTr.before($newFormTr);

    addSubscriberFormDeleteLink($newFormTr);
}

function addSubscriberFormDeleteLink($subscriberFormTr) {
    var $removeFormA = $('<td class="right aligned"><button class="ui red compact icon button" data-tooltip="Supprimer" data-position="bottom center" data-inverted="" data-variation="mini"><i class="trash icon"></i></button></td>');
    $subscriberFormTr.append($removeFormA);
    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the subscriber form
        $subscriberFormTr.remove();
    });
}

var $addSubscriberLink = $('<button class="ui primary button" ><i class="add user icon"></i> Ajouter</button>');
var $newLinkTr = $('<tr></tr>').append($addSubscriberLink);

function manage_subscriber() {
    /******** suppression des subscribers *********/
    // Get the ul that holds the collection of subscribers
    $collectionHolder = $('tbody.subscribers');

    // add a delete link to all of the existing subscriber form li elements
    $collectionHolder.find('tr').each(function () {
        addSubscriberFormDeleteLink($(this));
    });


    /******** Ajout des subscribers *********/

    // Get the ul that holds the collection of subscribers
    $collectionHolder = $('tbody.subscribers');

    // add the "add a subscriber" anchor and li to the subscribers ul
    $collectionHolder.append($newLinkTr);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addSubscriberLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new subscriber form (see next code block)
        addSubscriberForm($collectionHolder, $newLinkTr);
    });
}