$(document).ready(function($){
    SITE.menuHead();
    SITE.backTop();
    SITE.contact();
    SITE.contactPopup();
    SITE.trainingPopup();
    SITE.btnSendTrainingNote();
});

SITE={
    menuHead: function () {
        $('.mbButtonMenu').click(function () {
            $('.menuTop').addClass('on');
            $('.mbButtonMenu').fadeOut();
            $('.mbButtonMenuL').fadeIn();
        });
        $('.mbButtonMenuL').click(function () {
            $('.menuTop').removeClass('on');
            $('.mbButtonMenuL').fadeOut();
            $('.mbButtonMenu').fadeIn();
        });
    },
    backTop: function () {
        $(window).scroll(function () {
            if ($(window).scrollTop() > 0) {
                $("#back-top").fadeIn();
            } else {
                $("#back-top").fadeOut();
            }
        });
        $("#back-top").click(function () {
            $("html, body").animate({scrollTop: 0}, 1000);
            return false;
        });
    },
    postSwipeReviews:function(){
        $('#post-swipe-reviews').owlCarousel({
            loop:true,
            margin:10,
            nav:true,
            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
            dots: true,
            responsive:{0:{items:1}, 600:{items:1}, 768:{items:2}, 1000:{items:3}}
        });
    },
    contact: function () {
        $('#submitContact').click(function () {
            var valid = true;
            if ($('#txtName').val() == '') {
                $('#txtName').addClass('error');
                valid = false;
            } else {
                $('#txtName').removeClass('error');
            }
            /*
            //Check mail
            if ($('#txtMail').val() == '') {
                $('#txtMail').addClass('error');
                valid = false;
            } else {
                var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                var mail = $('#txtMail').val();
                if (regex.test(mail)) {
                    $('#txtMail').removeClass('error');
                } else {
                    $('#txtMail').addClass('error');
                    valid = false;
                }
            }
            */

            if (valid == false) {
                return false;
            }
            return valid;
        });
    },
    contactPopup:function(){
        $('.submitContact').click(function(){
            var valid = true;
            if ($('.txtName').val() == '') {
                $('.txtName').addClass('error');
                valid = false;
            } else {
                $('.txtName').removeClass('error');
            }

            if ($('.txtMobile').val() == '') {
                $('.txtMobile').addClass('error');
                valid = false;
            } else {
                var regex = /^[0-9-+]+(\d{9,10})$/;
                var phone = $('.txtMobile').val();
                if (regex.test(phone)) {
                    $('.txtMobile').removeClass('error');
                } else {
                    $('.txtMobile').addClass('error');
                    valid = false;
                }
            }
            /*
            if ($('.txtMail').val() == '') {
                $('.txtMail').addClass('error');
                valid = false;
            } else {
                var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                var mail = $('.txtMail').val();
                if (regex.test(mail)) {
                    $('.txtMail').removeClass('error');
                } else {
                    $('.txtMail').addClass('error');
                    valid = false;
                }
            }
            */

            if (valid == false) {
                return false;
            }
            return valid;
        });
    },
    btnClickSupport:function(){
        $('.box-reg-click-book').click(function(){
            $('.sys-popup-support').modal('show');
        });
    },
    btnClickTraining:function(){
        $('.course-register').click(function(){
            $('.sys-popup-training').modal('show');
        });
    },
    trainingPopup:function(){
        $('.submitTraining').click(function(){
            var valid = true;
            if ($('.txtNameTraining').val() == '') {
                $('.txtNameTraining').addClass('error');
                valid = false;
            } else {
                $('.txtName').removeClass('error');
            }

            if ($('.txtMobileTraining').val() == '') {
                $('.txtMobileTraining').addClass('error');
                valid = false;
            } else {
                var regex = /^[0-9-+]+(\d{9,10})$/;
                var phone = $('.txtMobileTraining').val();
                if (regex.test(phone)) {
                    $('.txtMobileTraining').removeClass('error');
                } else {
                    $('.txtMobileTraining').addClass('error');
                    valid = false;
                }
            }
            /*
            if ($('.txtMailTraining').val() == '') {
                $('.txtMailTraining').addClass('error');
                valid = false;
            } else {
                var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                var mail = $('.txtMailTraining').val();
                if (regex.test(mail)) {
                    $('.txtMailTraining').removeClass('error');
                } else {
                    $('.txtMailTraining').addClass('error');
                    valid = false;
                }
            }
            */

            if (valid == false) {
                return false;
            }
            return valid;
        });
    },
    btnSendTrainingNote:function(){
        $('.btnSendTrainingNote').click(function(){
            var valid = true;
            if ($('.name').val() == '') {
                $('.name').addClass('error');
                valid = false;
            } else {
                $('.name').removeClass('error');
            }
            if ($('.phone').val() == '') {
                $('.phone').addClass('error');
                valid = false;
            } else {
                var regex = /^[0-9-+]+(\d{9,10})$/;
                var phone = $('.phone').val();
                if (regex.test(phone)) {
                    $('.phone').removeClass('error');
                } else {
                    $('.phone').addClass('error');
                    valid = false;
                }
            }

            if (valid == false) {
                return false;
            }
            return valid;
        });
    }
}