/* Underscore custom functions */
_.mixin({
    isJSON: function(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }
});

'use strict';
var $ = jQuery;

var RW_Utils = {
    getHash: function() {
        return location.hash.replace(/[^\w]/g, '');
    },
    navigateTo: function(_index) {
        $(document).find('a.ajaxify').attr('href', function(index, attr) {
            if (attr.indexOf(Toddler_Conf.link_pages[_index]) !== -1) {
                var _self = $(this);
                setTimeout(function() {
                    _self.trigger('click');
                }, 400);
            }
        });
    },
    confirm: function(message, callback) {
        $('#confirm').modal({
            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
            position: ["20%", ],
            overlayId: 'confirm-overlay',
            containerId: 'confirm-container',
            onShow: function(dialog) {
                var modal = this;

                $('.message', dialog.data[0]).append(message);

                // if the user clicks "yes"
                $('.yes', dialog.data[0]).click(function() {
                    // call the callback
                    if ($.isFunction(callback)) {
                        callback.apply();
                    }
                    // close the dialog
                    modal.close(); // or $.modal.close();
                });
            }
        });
    },

    modalIt: function (data) {
        var Defaults = {
            onOpen: function (dialog) {
                dialog.overlay.fadeIn('fast', function () {
                    dialog.data.hide();
                    dialog.container.fadeIn('fast', function () {
                            dialog.data.show();
                    });
                });
            },
            minHeight: 470,
            overlayClose: true,
        };
        
        var all_setup = {
            minHeight: data.minHeight || Defaults.minHeight,
            onOpen: data.onOpen || Defaults.onOpen,
            overlayClose: data.overlayClose || Defaults.overlayClose,
        };
        
        for (var i in data) {
            if (data.hasOwnProperty(i)) {
                for (var x in all_setup) {
                    if (x !== i) {
                        all_setup[i] = data[i];
                    }
                }
            }
        }
        
        $(data.selector).modal(all_setup);
    }

};

var Toddler = {
    init: function() {
        var self = this;
        
        /**
        * Remove empty <p /> tags
        */
        $('p:empty').remove();
        
        $('#menu').slicknav();
        
        self.dropDown();
        
        if (Toddler_Conf.isChildPage === "true") {
            $('#menu').slicknav();
        } else {
            this.popups();
        }

        if (Toddler_Conf.parentAdminPage === "true") {
            self.checkBoxChecker();

            self.fileHandler();

            $(document).on('submit', 'form[name="add_child"], form[name="update_child"], form[name="update_user"]', function(e) {
                e.preventDefault();
                $(this).find('.rw-error').removeClass('rw-error').end().find('p.error').remove();

                if ($(e.target).is('form[name="update_child"]') || $(e.target).is('form[name="add_child"]')) {
                    var data_set = new FormData();
                    data_set.append("action", 'add_children');
                    data_set.append("hashTag", RW_Utils.getHash());
                    data_set.append("full_data", JSON.stringify($(this).serializeArray()));
                    data_set.append("baby_img", $(this).find('input[type="file"]')[0].files[0]);

                } else if ($(e.target).is('form[name="update_user"]')) {
                    var data_set = new FormData();
                    data_set.append("action", 'update_user_parent');
                    data_set.append("hashTag", RW_Utils.getHash());
                    data_set.append("full_data", JSON.stringify($(this).serializeArray()));
                    data_set.append("user_profile_pic", $(this).find('input[type="file"]')[0].files[0]);
                }

                self.ajaxifying({
                    data: data_set,
                    processData: false,
                    contentType: false,
                    beforeCallback: function() {
                        $('.process-loading').addClass('show');
                    },
                    successCallback: function(responseText) {
                        var result = JSON.parse(responseText), resulted_errors = result.errors, my_array = {}, count_array_length, obj_keys;

                        _.each(resulted_errors, function(value, index) {
                            return my_array[index] = value;
                        });

                        count_array_length = _.keys(my_array).length;

                        if (count_array_length < 1) {
                            alert(result.ok);

                            if (result.hasOwnProperty('redirect')) {
                                location.replace(result.redirect);
                            }

                            RW_Utils.navigateTo(4);

                        } else {
                            obj_keys = _.keys(my_array);

                            for (var i = 0; i < obj_keys.length; i++) {
                                $('input[name="' + obj_keys[i] + '"],\n\
                                    select[name="' + obj_keys[i] + '"],\n\
                                    textarea[name="' + obj_keys[i] + '"]')
                                        .addClass('rw-error').parent().append( '<p class="error">' + my_array[obj_keys[i]] + '</p>' );
                            }
                            
                        }
                    },
                });
            });

            var hashTag = '', ajaxifying_data = {hashTag: RW_Utils.getHash()};

            $(document).on('click', 'a.ajaxify, button.ajaxify, input.ajaxify[type="button"]', function(e) {
                if ($(e.target).is('a.ajaxify') || $(e.target).parent().is('a.ajaxify')) {
                    hashTag = $(this).attr('href').replace(/[^\w]/g, '');

                    if (sessionStorage.getItem('current_hash') !== hashTag) {
                        sessionStorage.setItem('current_hash', hashTag);
                    }

                    ajaxifying_data = {
                        hashTag: hashTag,
                    }

                } else if ($(e.target).is('button.ajaxify') || $(e.target).is('input.ajaxify[type="button"]')) {

                    hashTag = sessionStorage.getItem('current_hash');
                    data_action = $(this).attr('data-action');

                    ajaxifying_data = {
                        hashTag: hashTag,
                        dataSet: (_.isJSON(data_action)) ? data_action : '',
                    }
                }

                //if (RW_Utils.getHash() !== hashTag) {
                if ($(e.target).is('input.ajaxify[name="delete_child"]')) {
                    var confirm_message = 'Please confirm you wish to delete this childprofile. As once its deleted you are not able to restore any information, pictures or videos linked to this profile.';
                    RW_Utils.confirm(confirm_message, function() {
                        self.ajaxifying({
                            data: ajaxifying_data,
                            beforeCallback: function() {
                                $('.process-loading').addClass('show');
                            },
                            successCallback: function(data) {
                                var result = JSON.parse(data);
                                if (result.status) {
                                    alert(result.status);

                                    RW_Utils.navigateTo(4);
                                }
                            }
                        });
                    });
                } else {
                    self.ajaxifying({
                        data: ajaxifying_data
                    });
                }
                //}

                if ($(e.target).is('input[name="cancel"]')) {
                    self.ajaxifying({
                        data: {
                            hashTag: sessionStorage.getItem('current_hash')
                        }
                    });
                }

            });

            if (location.hash !== "") {
                hashTag = RW_Utils.getHash();
                if (sessionStorage.getItem('current_hash') !== hashTag) {
                    sessionStorage.setItem('current_hash', hashTag);
                }
                self.ajaxifying({
                    data: ajaxifying_data
                });
            }
        }

        if (Toddler_Conf.is_user_logged_in === "false") {
            $('#wp-fb-ac-fm input[name="redirectTo"]').val(Toddler_Conf.profilePage);
        }

        this.tabSwitcher();
    },
    ajaxifying: function(obj) {
        var self = this;

        var Defaults = {
            req_method: 'POST',
            URL: Toddler_Conf.admin_url,
            data: {
                all_data: 'something_to_send',
            },
            beforeCallback: function() {
                $('#form-section').empty().removeClass('nothing-cool');
                $('.process-loading').addClass('show');
            },
            successCallback: function(data) {
                $('#form-section').html(data);
                $('.process-loading').removeClass('show');
            },
            completeCallback: function() {
                $('.process-loading').removeClass('show');

                if ($('#form-section').find('*').length <= 1) {
                    $('#form-section').addClass('nothing-cool');
                }

                self.datePicker();
            }
        };

        obj.data.action = obj.data.action || 'hash_load';

        var all_setup = {
            type: obj.req_method || Defaults.req_method,
            url: obj.URL || Defaults.URL,
            data: obj.data || Defaults.data,
            beforeSend: obj.beforeCallback || Defaults.beforeCallback,
            success: obj.successCallback || Defaults.successCallback,
            complete: obj.completeCallback || Defaults.completeCallback,
        };

        for (var i in obj) {
            if (obj.hasOwnProperty(i)) {
                for (var x in all_setup) {
                    if (x !== i) {
                        all_setup[i] = obj[i];
                    }
                }
            }
        }

        $.ajax(all_setup);
    },
    preventPound: function() {
        $('a[href^="#"]').click(function(e) {
            e.preventDefault();
        });
    },
    checkBoxChecker: function() {
        $(document).on('click', '#background-chooser > ul > li', function(e) {
            e.preventDefault();
            $('#background-chooser > ul > li').find('.check').removeClass('active');
            $('input[name="chosen_bg"]').val($(this).attr('data-id'));
            $(this).find('.check').addClass('active');
        });

        $(document).on('click', '.checkbox', function(e) {
            e.preventDefault();
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                $('input[name="public_access"]').val('0');
            } else {
                $(this).addClass('active');
                $('input[name="public_access"]').val('1');
            }
        });
    },
    
    popups: function() {
        $('.basic').click(function(e) {
            e.preventDefault();
            if ( $(e.target).is('.signin') ) {
                RW_Utils.modalIt({
                    selector: '#value'
                });
            } else if ( $(e.target).is('.join') ) {
                RW_Utils.modalIt({
                    selector: '#value_R',
                    minHeight: 720
                });
            }
            
            return false;
        });
        
        $('.switch-modal').click(function (e) {
            e.preventDefault();
            
            if ( $(this).parents('#value')[0] ) {
                $('.simplemodal-close').trigger('click');
                $('a[data-load-popup="value_R"]').trigger('click');
            } else if ( $(this).parents('#value_R')[0] ) {
                $('.simplemodal-close').trigger('click');
                $('a[data-load-popup="value"]').trigger('click');
                console.log('q123');
            }
        })

        $(document).on('submit', '#reg_form', this.registration);

        $(document).on('submit', '#login_form', this.login);

    },
    registration: function(e) {
        e.preventDefault();

        $('.svr_messages').empty();

        $.ajax({
            type: 'POST',
            url: Toddler_Conf.ajax_url,
            data: {
                action: 'registration',
                all_data: JSON.stringify($(this).serializeArray())
            },
            beforeSend: function() {
                $('#reg_form').slideUp();
                $('.reg_preloader').show();

                $('.reg_result_success').remove();

                $('.errors-required').each(function() {
                    $(this).remove();
                });
            },
            success: function(msg) {
                $('.reg_preloader').hide();

                var result = JSON.parse(msg);

                if (result.errors instanceof Object && !$.isEmptyObject(result.errors)) {
                    $.each(result.errors, function(key, val) {
                        $('input#' + key).addClass('rw-error');
                    });

                    $('#reg_form').slideDown();

                } else if (result.success !== '') {
                    $('.svr_messages').append('<h3 class="reg_result_success">' + result.success + '</h3>');
                }
            }
        });
    },
    login: function(e) {
        e.preventDefault();

        $(this).find('.rw-error').removeClass('rw-error');

        $.ajax({
            type: 'POST',
            url: Toddler_Conf.ajax_url,
            data: {
                action: 'rw_login',
                all_data: JSON.stringify($(this).serializeArray())
            },
            beforeSend: function() {
                $('#login_form').slideUp();
                $('.reg_preloader').show();

                $('.svr_messages').empty();

                $('.errors-required').each(function() {
                    $(this).remove();
                });
            },
            success: function(msg) {
                $('.reg_preloader').hide();

                var result = JSON.parse(msg);

                if (result.errors instanceof Object
                        && !$.isEmptyObject(result.errors) ) {
                    $.each(result.errors, function(key) {
                        $('input#' + key).addClass('rw-error');
                    });

                    if ('top_error' in result.errors) {
                        $('.svr_messages').append('<h3 class="login_error">' + result.errors.top_error + '</h3>');
                    }

                    $('#login_form').slideDown();

                } else if (!_.isEmpty(result.success)) {
                    $('.svr_messages').append('<h3 class="login_success">' + result.success.message + '</h3>');

                    setTimeout(function() {
                        location.replace(result.success.redirect_to);
                    }, 2000);
                }
            }
        });
    },
    fileHandler: function() {
        var original_src, image_changed = false;
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    if (input.files[0].type.indexOf('image/') !== -1) {
                        if (e.target.result) {
                            original_src = $('#preview-image').find('img').attr('src');
                            $('#preview-image').find('img').attr('src', e.target.result).removeClass('disable');
                            image_changed = true;
                        } else {
                            image_changed = false;
                            $('#preview-image').append('<img src="' + original_src + '" alt="" />').removeClass('disable');
                        }
                    } else {
                        alert('File is not an image');
                        return false;
                    }
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('click', '.remove', function(e) {
            image_changed = false;
            $(this).removeClass('disable').parent().find('img').attr('src', original_src);
            $(this).addClass('disable');
        });

        $(document).on('change', 'input[type="file"]', function() {
            readURL(this);
        });

        $(document).on('mouseenter', '#preview-image', function() {
            if (image_changed) {
                $(this).find('.remove').removeClass('disable');
            }
        }).on('mouseleave', '#preview-image', function() {
            if (image_changed) {
                $(this).find('.remove').addClass('disable');
            }
        });
    },
    datePicker: function() {
        $('#dob').datepicker({
            dateFormat: 'd-m-yy',
            changeMonth: true,
            changeYear: true,
        });
    },
    tabSwitcher: function() {
        $(document).on('click', '.tabs > .submit-button', function(e) {
            e.preventDefault();

            var currentIndex = $(this).index();

            $(this).parents('.tabs').find(' > .submit-button.active').removeClass('active');
            $(this).addClass('active');

            $(this).parents('.tab_action').next('.middle_hash_content').find(' > .tab_content').removeClass('enable');
            $(this).parents('.tab_action').next('.middle_hash_content').find(' > .tab_content:eq(' + currentIndex + ')').addClass('enable');
        });
    },
    
    dropDown: function () {
        $('.lang_selector').each(function() {
            $(this).dropdown({
                stack: false,
                onOptionSelect: function (elem) {
                    var curIndex = elem.index();
                    $('.qts-lang-menu').trigger('click')
                            .find('option:eq('+curIndex+')').prop('selected', true)
                            .trigger('change');
                },
            });
        });
    }
};

Toddler.init();