$(function () {

    'use strict';

    // Dashb
    $('.toggle-info').click(function () {
        $($this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);
        if($(this).hasClass('selected')){
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        } else {
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
    });

    // Trigger The Selectboxit
    
    $("select").selectBoxIt({
        autoWidth: false
    });
    

    // Hide Placeholder On Form Focus

        $('[placeholder]').focus(function (){

            $(this).attr('data-text', $(this).attr('placeholder'));
            $(this).attr('placeholder', '');
        
        }).blur(function (){
            $(this).attr('placeholder', $(this).attr('data-text'));
        });

    // Add Asterisk On Required Faild

        $('input').each(function (){

            if($(this).attr('required') === 'required') {
                $(this).after('<span class="asterisk">*</span>')
            }

        });

        // Convert Password Faild

        var passField = $('.password');
        $('.far').hover(function (){
            passField.attr('type', 'text');
        }, function (){
            passField.attr('type', 'password');
        });

        // Confirmation Message On Button

        $('.confirm').click(function (){
            return confirm('Are You Sure?');
        }); 

        // Category view option
        $('.cat h3').click(function () {
            $(this).next('.full-view').fadeToggle(200);
        });

        $('.option span').click(function (){
            $(this).addClass('active').siblings('span').removeClass('active');
            if($(this).data('view') === 'full'){
                $('.cat .full-view').fadeIn(200);
            } else {
                $('.cat .full-view').fadeOut(200);
            }
        });

        // show delete button on child cats
        $('.child-link').hover(function (){
            $(this).find('.show-delete').fadeIn();
        }, function (){
            $(this).find('.show-delete').fadeOut();
        });
});