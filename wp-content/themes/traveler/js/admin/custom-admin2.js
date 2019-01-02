jQuery(document).ready(function($){
    if($('#st_sendmail_expire_partner').length)
    {
        $('#st_sendmail_expire_partner').click(function (e) {
            e.preventDefault();
            var t = $(this).closest('#posts-filter');
            var data = t.serializeArray();
            t.find('.partner-message .alert').hide();
            console.log(data);
            $.ajax({
                url: ajaxurl,
                type: "POST",
                data: data,
                dataType: "json",
                beforeSend: function () {
                    t.find('.overlay').show();
                    t.find('.overlay .spinner').addClass('is-active');
                }
            }).done(function (respond) {
                if (respond.status == true) {
                    t.find('.partner-message .alert').removeClass('alert-error').show().html(respond.message);
                } else {
                    t.find('.partner-message .alert').addClass('alert-error').show().html(respond.message);
                }
                t.find('.overlay').hide();
                t.find('.overlay .spinner').removeClass('is-active');
                setTimeout(function () {
                    window.location.reload();
                }, 3000);
            })
        });
    }
});