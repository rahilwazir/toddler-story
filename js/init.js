// Underscore custom functions
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

    hashString: Toddler_Conf.link_pages[4],

    delay: (3 * 1000),
    rd: true,

    getHash: function(str) {
        var hashURI = (str || location.hash.split('#')[1] || this.hashString),
            expectString = hashURI.replace(/[^\w]/g, ''),
            expectNumeric = hashURI.match(/([^\W]+)-(\d+)(?:\/?(?=([^\W]+)))?/), o = {};
            location.hash.split('/').splice(1);

        if ( expectNumeric && expectNumeric[1] && expectNumeric[2] ) {

            this.hashString = expectNumeric[1];

            o.hashTag = expectNumeric[1];
            o.id = expectNumeric[2];

            if ( expectNumeric[3] ) {
                o.deeplink = expectNumeric[3];
            }

            return o;
        }

        if ( expectString && expectString !== '' ) {
            this.hashString = expectString;

            o.hashTag = expectString;

            return o;
        }

        return false;
    },

    navigateTo: function(_index) {
        location.hash = Toddler_Conf.link_pages[_index];
    },

    confirm: function(message, callback) {
        $('#confirm').modal({
            closeHTML: "<a href='#' title='Close' class='modal-close'>x</a>",
            position: ["20%"],
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
                    modal.close();
                });
            }
        });
    },

    modalIt: function (data) {
        var Defaults = {
            onOpen: function (dialog) {
                dialog.data.removeClass('disable');

                if (data.onOpenCallback) {
                    data.onOpenCallback();
                }

                dialog.overlay.fadeIn('fast', function () {
                    dialog.data.hide();
                    dialog.container.fadeIn('fast', function () {
                            dialog.data.show();
                    });
                });
            },
            minHeight: 470,
            overlayClose: true,
            onClose: function(dialog) {
                dialog.data.addClass('disable');

                if (data.onCloseCallback) {
                    data.onCloseCallback();
                }

            }
        };
        
        var all_setup = {
            minHeight: data.minHeight || Defaults.minHeight,
            onOpen: data.onOpen || Defaults.onOpen,
            overlayClose: data.overlayClose || Defaults.overlayClose
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
    },

    loaderElem: null,

    initLoader: function(elem) {
        if ( this.loaderElem === null && elem ) {
            this.loaderElem = elem;
            this.loaderElem.prepend('<div class="process-loading"></div>');
        }
    },

    removeLoader: function() {
        if ( this.loaderElem !== null ) {
            this.loaderElem.find('.process-loading').remove();
            this.loaderElem = null;
        }
    },

    refresh: function() {
        var self = this;

        if ( this.rd ) {
            var currentURL = location.href,
                testHash = /[#]+$/.test(currentURL);

            if ( testHash ) {
                location.href = currentURL.replace(/[#]+$/, '');
            } else {
                location.href = currentURL.replace(/[#]+$/, '') + '#';
            }

            this.rd = false;
        } else {
            alert('Please wait 5 seconds.');
        }

        setTimeout(function () {
            self.rd = true;
        }, this.delay);
    },

    textOnly: function(obj) {
        return obj.contents().filter(function (s) {
            return s.nodeType === 3;
        }).text();
    }

};
var Toddler;

Toddler = {
    currentTab: null,

    init: function() {

        var self = this;

        this.preventPound();

        self.refreshE();

        // remove empty <p></p> tags
        $('p:empty').remove();

        $('#menu').slicknav();

        this.dropDown();

        if (Toddler_Conf.isChildPage === "true") {
            $('#menu').slicknav();
        } else {
            this.popups();
        }

        if (Toddler_Conf.parentAdminPage === "true") {
            self.checkBoxChecker();

            self.fileHandler();

            $(document).on('submit', 'form[name]', function(e) {
                e.preventDefault();
                var $self = $(this);
                $(this).find('.rw-error').removeClass('rw-error').end().find('p.error').remove();

                var data_set = new FormData(), refresh = false, shortPops = false;

                data_set.append("hashTag", RW_Utils.getHash());
                data_set.append("full_data", JSON.stringify($(this).serializeArray()));

                data_set.append('token', Toddler_Conf.token);

                if ($(e.target).is('form[name="update_child"]') || $(e.target).is('form[name="add_child"]')) {
                    data_set.append("action", 'add_children');
                    data_set.append("baby_img", $(this).find('input[type="file"]')[0].files[0]);
                } else if ($(e.target).is('form[name="update_user"]')) {
                    data_set.append("action", 'update_user_parent');
                    data_set.append("user_profile_pic", $(this).find('input[type="file"]')[0].files[0]);
                } else if ($(e.target).is('form[name="au_child_blog"]')) {
                    data_set.append('action', 'au_child_blog');
                    refresh = true;
                } else if ($(e.target).is('form[name="add_journal"]')) {
                    data_set.append('action', 'add_journal');
                    refresh = true; shortPops = true;
                }

                self.ajaxifying({
                    data: data_set,
                    processData: false,
                    contentType: false,
                    beforeCallback: function() {
                        if (!shortPops) {
                            RW_Utils.initLoader(this.loader);
                        }
                    },
                    successCallback: function(responseText) {
                        RW_Utils.removeLoader();

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

                            if (shortPops) {
                                $('.simplemodal-close').trigger('click');
                            }

                            if ( refresh ) {
                                RW_Utils.refresh();
                            } else {
                                RW_Utils.navigateTo(4);
                            }
                        } else {
                            obj_keys = _.keys(my_array);

                            for (var i = 0; i < obj_keys.length; i++) {
                                $self.find('input[name="' + obj_keys[i] + '"],\n\
                                    select[name="' + obj_keys[i] + '"],\n\
                                    textarea[name="' + obj_keys[i] + '"]')
                                    .addClass('rw-error').parent().append( '<p class="error">' + my_array[obj_keys[i]] + '</p>' );
                            }

                        }
                    }
                });
            });

            var hashTag = '', ajaxifying_data = (RW_Utils.getHash() || {});

            $(document).on('click', '.ajaxify', function(e) {
                if ( $(this).hasClass('block') ) e.preventDefault();

                if ($(e.target).is('input.ajaxify[name="delete_child"]')) {
                    var confirm_message = 'Please confirm you wish to delete this childprofile. As once its deleted you are not able to restore any information, pictures or videos linked to this profile.';

                    RW_Utils.confirm(confirm_message, function() {
                        ajaxifying_data.hashTag = 'deleteChild';

                        self.ajaxifying({
                            data: ajaxifying_data,
                            beforeCallback: function() {
                                RW_Utils.initLoader(this.loader);
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
                    // comment adder
                    if ( $(this).hasClass('specific') ) {
                        var $elem = $(this),
                            expect = (
                                $elem.attr('href') ||
                                $elem.attr('data-value')
                            ),
                            parentElement = $(this).parents('article.blog_post');

                        ajaxifying_data = RW_Utils.getHash(expect);

                        ajaxifying_data.commentContent= $(this).prev().val();

                        self.ajaxifying({
                            loader: $(this).parents('.specific-loader'),
                            data: ajaxifying_data,
                            beforeCallback: function() {
                                RW_Utils.initLoader( this.loader );
                            },
                            successCallback: function(data) {
                                if ( data ) {
                                    var commentLength = parentElement.find('.comments-list').prepend(data).children('.single-comment').length;
                                    parentElement.find('.comment-counter .comment-count').text(commentLength);
                                    parentElement.find('.no-comments-yet').remove();
                                    $elem.prev().val('');
                                }
                            },
                            completeCallback: function() {
                                RW_Utils.removeLoader();
                            }
                        });
                    } else {
                        self.ajaxifying({
                            data: ajaxifying_data
                        });
                    }
                }
            });

            $(document).on('click', 'input[name="cancel"]', function(){
                RW_Utils.navigateTo(4);
            })

            $(window).hashchange( function(){
                if (sessionStorage.getItem('current_hash') !== RW_Utils.hashString) {
                    sessionStorage.setItem('current_hash', RW_Utils.hashString);
                }

                var windowHash = RW_Utils.getHash();

                if ( windowHash ) {
                    self.ajaxifying({
                        data: RW_Utils.getHash()
                    });
                }

            });

            $(window).hashchange();
        }

        if (Toddler_Conf.is_user_logged_in === "false") {
            $('#wp-fb-ac-fm input[name="redirectTo"]').val(Toddler_Conf.profilePage);
        }

        this.tabSwitcher();
    },

    preventPound: function() {
        $(document).on('click', 'a[href="#"]', function(e) {
            e.preventDefault();
        });
    },

    ajaxifying: function(obj) {
        var self = this;

        var Defaults = {
            req_method: 'POST',

            loader: $('body > .container'),

            URL: Toddler_Conf.admin_url,

            data: {
                all_data: 'something_to_send'
            },

            beforeCallback: function() {
                RW_Utils.initLoader(obj.loader);
                $('#form-section').empty().removeClass('nothing-cool');
            },

            successCallback: function(data) {
                $('#form-section').html(data);

                self.changeMainTitle();
            },

            completeCallback: function() {
                RW_Utils.removeLoader();

                if ($('#form-section').find('*').length <= 1) {
                    $('#form-section').addClass('nothing-cool');
                }

                self.datePicker();
            }
        };

        obj.loader = (obj.loader || Defaults.loader);

        obj.data.action = obj.data.action || 'hash_load';

        obj.data.token = Toddler_Conf.token;

        var all_setup = {
            type: obj.req_method || Defaults.req_method,
            url: obj.URL || Defaults.URL,
            data: obj.data || Defaults.data,
            beforeSend: obj.beforeCallback || Defaults.beforeCallback,
            success: obj.successCallback || Defaults.successCallback,
            complete: obj.completeCallback || Defaults.completeCallback
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

//            try {
            $.ajax(all_setup);
//            } catch (e) {
//                alert('Some error occurred. Please try again.');
//            }
    },

    checkBoxChecker: function() {
        $(document).on('click', '#background-chooser > ul > li', function(e) {
            e.preventDefault();
            $('#background-chooser').find(' > ul > li .check').removeClass('active');
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
            }
        });

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
                    $.each(result.errors, function(key) {
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
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('click', '.remove', function() {
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
            changeYear: true
        });
    },
    tabSwitcher: function() {
        var self = this;

        $(document).on('click', '.tabs > .submit-button', function(e) {
            if ( !$(this).hasClass('unblock') ) e.preventDefault();

            if ($(this).hasClass('active')) { return; }

            self.currentTab = $(this).val();

            var currentIndex = $(this).index();

            $(this).parents('.tabs').find(' > .submit-button.active').removeClass('active');
            $(this).addClass('active');

            $(this).parents('.tab_action').next('.middle_hash_content').find(' > .tab_content').removeClass('enable');
            $(this).parents('.tab_action').next('.middle_hash_content').find(' > .tab_content:eq(' + currentIndex + ')').addClass('enable');

            self.currentTab = $(this).val();

            if (self.currentTab === 'Blog') {
                $('.blog-list .blog_post').each(function () {
                    var originalContent = $('.post-content').text(),
                        trimmedContent = originalContent.substring(0, 100);

                    $('.post-content').text(trimmedContent);

                })
            }
        });
    },

    changeMainTitle: function ( custom_default ) {
        var childName = $('#title_name').val(),
            childDesc = $('#title_desc').val(),
            finaltitle = childName + ( ( (custom_default || childDesc) ) ? ' ' + (custom_default || childDesc) : '');

        if ( childName ) {
            $('.wrap_simple .fleft').text(finaltitle);
            document.title = finaltitle;
        }
    },

    dropDown: function() {
        $('.lang_selector').each(function() {
            $(this).dropdown({
                stack: false,
                onOptionSelect: function (elem) {
                    var curIndex = elem.index();
                    $('.qts-lang-menu').trigger('click')
                            .find('option:eq('+curIndex+')').prop('selected', true)
                            .trigger('change');
                }
            });
        });
    },

    refreshE: function() {
        $(document).on('click', '.refresh-icon', function(e) {
            e.preventDefault();

            RW_Utils.refresh();
        });
    },

    blogUtils: function() {
        var self = this;

        // blog reading
        $(document).on('click', '.read-blog, .edit-blog, .delete-blog', function(e) {
            e.preventDefault();

            var blogValue = Math.abs( $(this).attr('data-blog-value')),
                parentArticle = $(this).parents('article[data-id="' + blogValue + '"]'),
                blogValueOfArticle = Math.abs( parentArticle.attr('data-id')),
                blogToken = parentArticle.find('input[name="blog_token"]').clone(true, true),
                blogUpdateToken = parentArticle.find('input[name="blog_update_token"]').clone(true, true),
                blogID = parentArticle.find('input[name="blog_id"]').clone(true, true);

            if ( $(e.target).is('.read-blog') ) {
                $('input.read-blog').removeClass('disable');
                parentArticle.find('input.read-blog').addClass('disable').end().find('.edit-blog, .delete-blog').removeClass('disable');

                $('.blog_post').not(parentArticle).addClass('disable');

                $('.comment-box').addClass('disable');

                if (blogValue === blogValueOfArticle) {
                    parentArticle.find('.comment-box').removeClass('disable');
                }
            }

            if ($(e.target).is('.edit-blog')) {
                var editParent = $('#new-post-screen');

                $(this).addClass('disable');

                $('.blog-list').addClass('disable');
                $('.blog_post').removeClass('disable');
                $('#new-post-screen').removeClass('disable');

                var title = parentArticle.find('.post-title').text(),
                    description = parentArticle.find('.post-content').text();

                var postdate = parentArticle.find('.date').text(),
                    postmonth = parentArticle.find('.date_month').text(),
                    postyear = parentArticle.find('.date_year').text();

                editParent.find('.date').text(postdate)
                    .end().find('.date_month').text(postmonth)
                    .end().find('.date_year').text(postyear);

                $('input[name="child_id"]').after(blogToken, blogUpdateToken, blogID);

                $('input[name="child_blog_title"]').val(title);

                $('textarea[name="child_blog_description"]').val(description);
            }

            if ($(e.target).is('.delete-blog')) {
                var ajaxifying_data = {},
                    confirm_message = 'Delete this blog post? It cannot be undone.';

                RW_Utils.confirm(confirm_message, function() {
                    ajaxifying_data.hashTag = 'deleteChildBlogPost';
                    ajaxifying_data.id = blogID.val();
                    ajaxifying_data.blogToken = blogToken.val();

                    self.ajaxifying({
                        data: ajaxifying_data,
                        beforeCallback: function() {
                            RW_Utils.initLoader(this.loader);
                        },
                        successCallback: function(data) {
                            var result = JSON.parse(data);
                            if (result.status) {
                                alert(result.status);

                                RW_Utils.refresh();
                            }
                        }
                    });
                });
            }

        });

        //blog new post
        $(document).on('click', '#add_new_blog', function(e) {
            e.preventDefault();

            $('.blog-list').addClass('disable');
            $('#new-post-screen').removeClass('disable');
            $('#new-post-screen').find('input[type="text"], textarea').val('');
        });


        $(document).on('mouseenter', '.removal-input', function() {
            $(this).find('.remove-comment').removeClass('disable');
        }).on('mouseleave', '.removal-input', function() {
            $(this).find('.remove-comment').addClass('disable');
        });

        // comment removing
        $(document).on('click', '.remove-comment', function(e) {
            if ( $(this).hasClass('block') ) {
                e.preventDefault();
            }

            var _comment_id = Math.abs($(this).parents('.single-comment').attr('data-comment-id')),
                _elem_parent = $(this).parents('.single-comment[data-comment-id="' + _comment_id + '"]'),
                ajaxifying_data = RW_Utils.getHash( $(this).attr('data-value')),
                topParentElem = $(this).parents('article.blog_post'),
                superParentID = topParentElem.attr('data-id');


            ajaxifying_data.parentPostID = superParentID;

            RW_Utils.confirm('Delete this comment?', function() {
                self.ajaxifying({
                    loader: $(this).parents('.specific-loader'),
                    data: ajaxifying_data,
                    beforeCallback: function() {
                        RW_Utils.initLoader( this.loader );
                    },
                    successCallback: function(data) {
                        var result = JSON.parse(data);
                        if (result.deleted) {
                            _elem_parent.addClass('removing').fadeOut(700, function() {
                                _elem_parent.remove();
                            });

                            if (result.commentTotal >= 0) {
                                topParentElem.find('.comment-count').text(result.commentTotal);
                            }
                        }
                    },
                    completeCallback: function() {
                        RW_Utils.initLoader( this.loader );
                    }
                });
            });
        });
    },

    journalUtils: function () {
        var self = this;

        $(document).on('click', '.pop', function() {
            var $self = $(this), form = $('form[name=add_journal]'), journalID, journalToken, journalUpdateToken;

            RW_Utils.modalIt({
                selector: '.popup',
                onOpenCallback: function() {

                    if ($self.hasClass('edit')) {
                        $self.parent().find('.hd input[type=hidden]').not($self).each(function(e, x) {
                            switch ($(x).attr('name')) {
                                case 'journal_id':
                                    journalID = $(x).clone(true);
                                    break;
                                case 'journal_token':
                                    journalToken = $(x).clone(true);
                                    break;
                                case 'journal_update_token':
                                    journalUpdateToken = $(x).clone(true);
                                    break;
                                default:
                                    break;
                            }

                            form.find('input,textarea,select').each(function(ee, xx) {
                                if ($(x).attr('name') === $(xx).attr('name')) {
                                    $(xx).val($(x).val());
                                }
                            });
                        });
                        $('input[name=journal_post]').val('Update');
                        form.append(journalID, journalToken, journalUpdateToken);
                    }
                },

                onCloseCallback: function() {
                    $('input[name=journal_post]').val('Publish');

                    form.find('input,textarea,select').each(function(ee, xx) {
                        $(xx).val('');
                    });
                }
            });
        });

        // journal removing
        $(document).on('click', '.delete_journal', function() {
            var ajaxifying_data = {},
                _parent = $(this).parent().find('.hd'),
                confirm_message = 'Delete this post?';

            RW_Utils.confirm(confirm_message, function() {
                ajaxifying_data.hashTag = 'deleteJournalPost';
                ajaxifying_data.id = _parent.find('input[name=journal_id]').val();
                ajaxifying_data.journalToken = _parent.find('input[name=journal_token]').val();

                console.log(ajaxifying_data);

                self.ajaxifying({
                    data: ajaxifying_data,
                    beforeCallback: function() {
                        RW_Utils.initLoader(this.loader);
                    },
                    successCallback: function(data) {
                        try {
                            var result = JSON.parse(data);
                            if (result.status) {
                                alert(result.status);

                                RW_Utils.refresh();
                            }
                        } catch (e) {
                            console.log(e);
                        }
                    }
                });
            });
        });
    }
};

Toddler.init();