/**
 * CIMembership
 * 
 * @package		Site
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */
$(function(){$(".select").select2({minimumResultsForSearch:"-1",width:200}),$(".select-liquid").select2({minimumResultsForSearch:"-1",width:"off"}),$(".select-full").select2({width:"100%"}),$(".styled, .multiselect-container input").uniform({radioClass:"choice",selectAutoWidth:!1}),$(".validate").validate({invalidHandler:function(e,i){var a=i.numberOfInvalids();a&&($("label.error").removeClass("hide"),$("label.error").css("display","table"))},errorPlacement:function(e,i){"checker"==i.parent().parent().attr("class")||"choice"==i.parent().parent().attr("class")?e.appendTo(i.parent().parent().parent().parent().parent()):"checkbox"==i.parent().parent().attr("class")||"radio"==i.parent().parent().attr("class")?e.appendTo(i.parent().parent().parent()):e.insertAfter(i)},rules:{minimum_characters:{required:!0,minlength:3},maximum_characters:{required:!0,maxlength:6},minimum_number:{required:!0,min:3},maximum_number:{required:!0,max:6},range:{required:!0,range:[6,16]},email_field:{required:!0,email:!0},url_field:{required:!0,url:!0},date_field:{required:!0,date:!0},digits_only:{required:!0,digits:!0},enter_password:{required:!0,minlength:5},repeat_password:{required:!0,minlength:5,equalTo:"#enter_password"},custom_message:"required",group_styled:{required:!0,minlength:2},group_unstyled:{required:!0,minlength:2},agree:"required"},messages:{custom_message:{required:"This message is editable"},agree:"Please accept our policy"},success:function(e){e.addClass("hide")}}),$(".navigation").find("li.active").parents("li").addClass("active"),$(".navigation").find("li").not(".active").has("ul").children("ul").addClass("hidden-ul"),$(".navigation").find("li").has("ul").children("a").parent("li").addClass("has-ul"),$(document).on("click",".sidebar-toggle",function(e){e.preventDefault(),$("body").toggleClass("sidebar-narrow"),$("body").hasClass("sidebar-narrow")?($(".navigation").children("li").children("ul").css("display",""),$(".sidebar-content").hide().delay().queue(function(){$(this).show().addClass("animated fadeIn").clearQueue()})):($(".navigation").children("li").children("ul").css("display","none"),$(".navigation").children("li.active").children("ul").css("display","block"),$(".sidebar-content").hide().delay().queue(function(){$(this).show().addClass("animated fadeIn").clearQueue()}))}),$(".navigation").find("li").has("ul").children("a").on("click",function(e){e.preventDefault(),$("body").hasClass("sidebar-narrow")?($(this).parent("li > ul li").not(".disabled").toggleClass("active").children("ul").slideToggle(250),$(this).parent("li > ul li").not(".disabled").siblings().removeClass("active").children("ul").slideUp(250)):($(this).parent("li").not(".disabled").toggleClass("active").children("ul").slideToggle(250),$(this).parent("li").not(".disabled").siblings().removeClass("active").children("ul").slideUp(250))})});
$(function(){
    'use strict';
    $(".cmskill").each(function () {
        var pint = $(this).attr('data-skills');
        var decs = pint * 100;
        var grs = $(this).attr('data-gradientstart');
        var gre = $(this).attr('data-gradientend');

        $(this).circleProgress({
            value: pint,
            startAngle: -Math.PI / 4 * 2,
            fill: {gradient: [[grs, 1], [gre, .2]], gradientAngle: Math.PI / 4 * 2},
            lineCap: 'round',
            thickness: 20,
            animation: {duration: 1800},
            size: 180
        }).on('circle-animation-progress', function (event, progress) {
            $(this).find('strong').html(parseInt(decs * progress) + '<span> / 100</span>');
        });
    });
});