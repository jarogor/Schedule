$(document).ready(function() {

    var formData = {};
    var re = /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/;

    var regions = $('#regions'),
        couriers = $('#couriers'),
        date_start = $('#date_start'),
        date_end = $('#date_end');

    var timeoutShowing;

    regions.change(function() {
        formData.region = this.value;
        $(this).removeClass('is-invalid');
    });

    couriers.change(function() {
        formData.courier = this.value;
        $(this).removeClass('is-invalid');
    });

    date_start.change(function() {
        if (re.test(this.value)) {
            formData.date_start = this.value;

            if( (new Date(this.value) > new Date(date_end.val())) ) {
                date_end.attr("disabled", true).attr('title','').tooltip('hide').val('');
            }

            date_end.removeAttr("disabled");
            $(this).removeClass('is-invalid');
        } else {
            date_end.attr("disabled", true).val('');
            delete formData.date_start;
        }
    });

    date_end.change(function() {

        if( re.test(formData.date_start) && re.test(this.value) ) {
            if( (new Date(this.value) < new Date(formData.date_start)) ) {

                $(this).addClass('is-invalid');
                $(this).attr('title', "В прошлое отправиться невозможно").tooltip({trigger: 'manual'}).tooltip('show');

                delete formData.date_end;

            } else {
                $(this).attr('title','').tooltip('hide');
                $(this).removeClass('is-invalid');

                formData.date_end = this.value;
            }
        } else {
            $(this).attr('title','').tooltip('hide');
            $(this).removeClass('is-invalid');
        }
    });

    $('form button').on('click touchstart', function(){

        if(!formData.region) {
            regions.addClass('is-invalid');
        } else {
            regions.removeClass('is-invalid');
        }

        if(!formData.courier) {
            couriers.addClass('is-invalid');
        } else {
            couriers.removeClass('is-invalid');
        }

        if(!formData.date_start) {
            date_start.addClass('is-invalid');
        } else {
            date_start.removeClass('is-invalid');
        }

        if(!formData.date_end) {
            date_end.addClass('is-invalid');
        } else {
            date_end.removeClass('is-invalid');
        }

        if(Object.keys(formData).length == 4) {

            $.ajax({
                type: "POST",
                url: '/ajax',
                data: {"addTask": JSON.stringify(formData)},
                // dataType: 'json',
                success: function (res) {

                    if(res.status === 'offerTask') {

                        if(timeoutShowing) clearTimeout(timeoutShowing);

                        var html= couriers.find('option:selected').text() + ' в это время в дороге.';

                        if(res.data.length) {
                            html += ' Свободные дни для поездок: <ul>';

                            res.data.forEach(function (item) {
                                html += '<li>с ' + item.date_start + ' по ' + item.date_end + '</li>';
                            });

                            html += '</ul>';
                        }

                        $('.alert').html('').append(html).removeClass('alert-success').addClass('alert-dark');

                    }

                    if(res.status === 'setTask') {

                        $('.alert').html('Задача успешно добавлена.').removeClass('alert-dark').addClass('alert-success');

                        timeoutShowing = setTimeout(function () {
                            $('.alert').html('').removeClass('alert-success');
                        }, 5000);
                    }
                }
            });
        }
    });

    $('.get-clear-data').click(function () {
        $.ajax({
            type: 'POST',
            url: '/ajax',
            data: 'clear=true',
            success: function () {
                location.reload();
            }
        });
    });

    $('.get-generate-data').click(function () {
        $.ajax({
            type: 'POST',
            url: '/ajax',
            data: 'generate=true',
            success: function () {
                location.reload();
            }
        });
    });
});
