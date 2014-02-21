'use strict';
var $ = jQuery;

var ToddlerAdmin = {

    init: function() {
        (Toddler_Conf.post_types.is_child === "true") ? this.editSlugSet() : null;
        this.featureShow();
        this.dateTime();
        this.selectActiveBG();
        this.smoothAdminScroll();
        this.chosen();
    },

    editSlugSet: function () {
        $('#slugdiv  .hndle span').html('Direct URL');
        $('#slugdiv .inside label').removeClass('screen-reader-text')
                .html(Toddler_Conf.site_url + '/' + Toddler_Conf.post_types.child + '/');
    },
    
    chosen: function () {
        $('select[name^="toddler"]').chosen();
    },

    featureShow: function() {
        $(".cb-enable").click(function(){
            var parent = $(this).parents('.switch');
            $('.cb-disable',parent).removeClass('selected');
            $(this).addClass('selected');
            $('.checkbox',parent).attr('checked', true);
        });
        
        $(".cb-disable").click(function(){
            var parent = $(this).parents('.switch');
            $('.cb-enable',parent).removeClass('selected');
            $(this).addClass('selected');
            $('.checkbox',parent).attr('checked', false);
        });
    },

    dateTime: function () {
        $('#dob_child').datepicker({
            dateFormat: 'd-m-yy',
            changeMonth: true,
            changeYear: true,
        });
    },
    
    selectActiveBG: function () {
        $('.bg-selector').click(function () {
            $('.bg-meta span').removeClass('active-bg');
            
            var _id = $(this).attr('id');
            $('label[for="' + _id + '"]').find('span').addClass('active-bg');
        });
    },
    
    smoothAdminScroll: function () {
        $('a[rel="rw_sscroll"]').click(function(e){
            var self = $(this);
            e.preventDefault();
            
            $('html, body').animate({
                scrollTop: $( $(this).attr('href') ).offset().top - 100
            }, 500, 'swing', function () {
                $(self.attr('href')).addClass('active-bg');
            });
        })
    }
}

ToddlerAdmin.init();