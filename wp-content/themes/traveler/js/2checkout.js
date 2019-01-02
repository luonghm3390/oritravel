/**
 * Created by Administrator on 11/29/2017.
 */
jQuery(document).ready(function ($) {
    'use strict';
    var successCallback = function (data) {
        var myForm = document.getElementById('cc-form');
        myForm.token.value = data.response.token.token;
        myForm.wait_validate_st_twocheckout.value = 'run';
        $('#cc-form').STSendAjax();
    };

    var successCallbackModal = function (data) {
        var myForm = $('.booking_modal_form');
        $('#token', myForm).val(data.response.token.token);
        myForm.find('input[name="wait_validate_st_twocheckout"]').val('run');
        $('.booking_modal_form').STSendModalBookingAjax();
    };

    var successCallbackPackage = function(data) {
        var myForm = document.getElementById('mpk-form');
        myForm.token.value = data.response.token.token;
        myForm.submit();
    };

    var errorCallback = function (data) {
        if (data.errorCode === 200) {
            tokenRequest();
        } else {
            alert(data.errorMsg);
        }
    };
    var errorCallbackModal = function (data) {
        if (data.errorCode === 200) {
            tokenRequestModal();
        } else {
            alert(data.errorMsg);
        }
    };

    var errorCallbackPackage = function (data) {
        if (data.errorCode === 200) {
            tokenRequestPackage();
        } else {
            alert(data.errorMsg);
        }
    };

    var tokenRequest = function () {
        var form = $("#cc-form");
        var args = {
            sellerId: st_2checkout_params.twocheckout.accountID,
            publishableKey: st_2checkout_params.twocheckout.publicKey,
            ccNo: $("#st_twocheckout_card_number", form).val(),
            cvv: $("#st_twocheckout_card_code", form).val(),
            expMonth: $("#st_twocheckout_card_expiry_month", form).val(),
            expYear: $("#st_twocheckout_card_expiry_year", form).val()
        };
        TCO.requestToken(successCallback, errorCallback, args);
    };

    var tokenRequestModal = function () {
        var form = $(".booking_modal_form");
        var args = {
            sellerId: st_2checkout_params.twocheckout.accountID,
            publishableKey: st_2checkout_params.twocheckout.publicKey,
            ccNo: $("#st_twocheckout_card_number", form).val(),
            cvv: $("#st_twocheckout_card_code", form).val(),
            expMonth: $("#st_twocheckout_card_expiry_month", form).val(),
            expYear: $("#st_twocheckout_card_expiry_year", form).val()
        };
        TCO.requestToken(successCallbackModal, errorCallbackModal, args);
    };

    var tokenRequestPackage = function () {
        var form = $("#mpk-form");
        var args = {
            sellerId: st_2checkout_params.twocheckout.accountID,
            publishableKey: st_2checkout_params.twocheckout.publicKey,
            ccNo: $("#st_twocheckout_card_number", form).val(),
            cvv: $("#st_twocheckout_card_code", form).val(),
            expMonth: $("#st_twocheckout_card_expiry_month", form).val(),
            expYear: $("#st_twocheckout_card_expiry_year", form).val()
        };
        TCO.requestToken(successCallbackPackage, errorCallbackPackage, args);
    };

    $(function () {
        TCO.loadPubKey(st_2checkout_params.twocheckout.loadPubKey);


        /* Modal */
        $(".booking_modal_form", 'body').on('st_wait_checkout_modal', function (e) {
            var payment = $('input[name="st_payment_gateway"]:checked', this).val();
            if (payment === 'st_twocheckout') {
                tokenRequestModal();
                return false;
            }
            return true;
        });

        $(".booking_modal_form", 'body').on('st_before_checkout_modal', function (e) {
            $('input[name="wait_validate_st_twocheckout"]', this).val('wait');
        });
        /* End Modal */
        $("#cc-form", 'body').on('st_wait_checkout', function (e) {
            var payment = $('input[name="st_payment_gateway"]:checked', this).val();
            if (payment === 'st_twocheckout') {
                tokenRequest();
                return false;
            }
            return true;
        });

        $("#cc-form", 'body').on('st_before_checkout', function (e) {
            $('input[name="wait_validate_st_twocheckout"]', this).val('wait');
        });

        $("#mpk-form").submit(function(e) {
            var payment = $('input[name="st_payment_gateway"]:checked', this).val();
            if (payment === 'st_twocheckout') {
                tokenRequestPackage();
                return false;
            }
            return true;
        });

    });
});