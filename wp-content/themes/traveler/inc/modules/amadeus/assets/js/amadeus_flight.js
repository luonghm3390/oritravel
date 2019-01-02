(function ($) {
    $(document).ready(function () {
        var data  = $('.amadeus-content-search').data();
        _get_search_result(data);
        function _get_search_result(data){
            if($('.amadeus-content-search').length){
                if (typeof data == 'object') {
                    var boss = $('.amadeus-content-search');
                    var list = $('.amadeus-list', boss);
                    var overlay = $('.overlay-form', boss);
                    var listResult = $('.amadeus-list-result', list);
                    if(data.origin == '' || data.origin == undefined)
                        data.origin = 'BER'
                    if(data.destination == '' || data.destination == undefined)
                        data.destination = 'JFK';
                    $('#depature-title', list).html(st_amadeus_text.departure+" <span class='fcode'>" + data.origin + "</span> " +st_amadeus_text.to+ " <span class='fcode'>" + data.destination + "</span><div id='flight-count'></div>");
                    var queryStr = '';
                    if(data.return_date != '' && data.return_date != undefined)
                        queryStr += "&return_date=" + data.return_date;
                    if(data.children != '')
                        queryStr += "&children=" + data.children;
                    if(data.infants != '')
                        queryStr += "&infants=" + data.infants;
                    $.getJSON("https://api.connect.travelaudience.com/api/search/?affiliate_key="+st_amadeus.travelau_key+"&origin="+data.origin+"&destination="+data.destination+"&departure_date="+data.departure_date+"&adults="+data.adults+"&currency="+st_amadeus.currency+queryStr, function (ev) {
                        if (typeof ev == 'object') {
                            if(typeof ev.results !== 'undefined' && ev.results.length > 0){
                                $('#depature-title #flight-count').html(ev.results.length + ' ' + st_amadeus_text.result);
                                var i;
                                var te = '';
                                var targetLink = '';
                                if(st_amadeus.target == 'on')
                                    targetLink = ' target="_blank" ';
                                for (i = 0; i < ev.results.length; ++i) {
                                    var item = ev.results[i];

                                    if(i < 500) {
                                        var stops = '';
                                        var flist = '';
                                        if(item["outbound"]["flights"].length > 0){
                                            var j;
                                            for(j = 0; j < item["outbound"]["flights"].length; j++){
                                                var fitem = item["outbound"]["flights"][j];
                                                var start_stops = item["outbound"]["flights"][0]["origin"]["airport"];
                                                var end_stops = item["outbound"]["flights"][item["outbound"]["flights"].length - 1]["destination"]["airport"];
                                                var layover = '';
                                                if(j != 0 && j != item["outbound"]["flights"].length ){
                                                    layover = '<i class=\'fa fa-clock-o\'></i> '+st_amadeus_text.layover+' -- ' + _get_layover_time(item["outbound"]["flights"][j - 1]["arrives_at"], fitem["departs_at"]);
                                                }
                                                stops += '<div class="flight-stop-layover-item">\n' +
                                                    '                                                <div class="flight-stop-layover-iata" data-html="true" data-toggle="tooltip" data-placement="top" title="<div class=\'flight-tooltip\'><i class=\'fa fa-plane\'></i> '+st_amadeus_text.airport+' <span class=\'ftcode\'>' + fitem["origin"]["airport"] + '</span><br />'+layover+'</div>"><span>'+fitem["origin"]["airport"]+'</span></div>\n' +
                                                    '                                            </div>';
                                                if(j == item["outbound"]["flights"].length - 1) {
                                                    stops += '<div class="flight-stop-layover-item">\n' +
                                                        '                                                <div class="flight-stop-layover-iata" data-html="true" data-toggle="tooltip" data-placement="top" title="<div class=\'flight-tooltip\'><i class=\'fa fa-plane\'></i> '+st_amadeus_text.airport+' ' + fitem["destination"]["airport"] + '<br /></div>"><span>'+fitem["destination"]["airport"]+'</span></div>\n' +
                                                        '                                            </div>';
                                                }

                                                var flist_stop = '';
                                                if(j != 0 && j != item["outbound"]["flights"].length ){
                                                    flist_stop = '<div class="flight-stop">\n' +
                                                        '                                        <div class="flight-stop-name">\n' +
                                                        '                                            <i class="fa fa-clock-o"></i> '+st_amadeus_text.stop_at+' <span class="fstop">'+fitem["origin"]["airport"]+'</span>\n' +
                                                        '                                        </div>\n' +
                                                        '                                        <div class="flight-stop-duration">'+_get_layover_time(item["outbound"]["flights"][j - 1]["arrives_at"], fitem["departs_at"])+'</div>\n' +
                                                        '                                    </div>';
                                                }
                                                flist += '<div class="flight-detail-item">\n'
                                                    +flist_stop+
                                                    '                                    <div class="flight-detail-main">\n' +
                                                    '                                        <div class="flight-time">\n' +
                                                    '                                            <div class="time-range"><i class="fa fa-calendar-check-o"></i> '+_split_date(fitem["departs_at"], 'h')+' - '+_split_date(fitem["arrives_at"], 'h')+'</div>\n' +
                                                    '                                            <div class="time-duration">'+_get_layover_time(fitem["departs_at"], fitem["arrives_at"])+'</div>\n' +
                                                    '                                        </div>\n' +
                                                    '                                        <div class="air-range">\n' +
                                                    '                                            <i class="fa fa-road"></i> <span class="fcode">'+fitem["origin"]["airport"]+'</span> → <span class="fcode">'+fitem["destination"]["airport"]+'</span> \n' +
                                                    '                                        </div>\n' +
                                                    '                                        <div class="flight-meta">\n' +
                                                    '                                            <ul>\n' +
                                                    '                                                <li>'+st_amadeus_text.flight_number+': '+fitem["operating_airline"] + fitem["flight_number"]+'</li>\n' +
                                                    '                                                <li>'+st_amadeus_text.aircraft+': A'+fitem["aircraft"]+'</li>\n' +
                                                    '                                            </ul>\n' +
                                                    '                                        </div>\n' +
                                                    '                                    </div>\n' +
                                                    '                                </div>';
                                            }
                                        }


                                        te += '<div class="amadeus-item hidden">\n' +
                                            '                        <div class="amadeus-left">\n' +
                                            '                            <a '+targetLink+' href="'+item["deep_link"]+'" class="btn btn-primary">'+st_amadeus_text.book+'<span\n' +
                                            '                                        class="price">'+ _get_format_money(item["fare"]["total_price"]) +'</span></a>\n' +
                                            '                            <p>'+st_amadeus_text.on+' <span>'+ev["meta"]["carriers"][item["airline"]]["name"]+'</span></p>\n' +
                                            '                            <img src="https://api.connect.travelaudience.com'+ev["meta"]["carriers"][item["airline"]]["logos"]["medium"]+'"/>\n' +
                                            '                        </div>\n' +
                                            '                        <div class="amadeus-mid">\n' +
                                            '                            <div class="img-small">\n' +
                                            '                                <img src="https://api.connect.travelaudience.com'+ev["meta"]["carriers"][item["airline"]]["logos"]["small"]+'"/>\n' +
                                            '                            </div>\n' +
                                            '                            <div class="flight-process">\n' +
                                            '                                <div class="flight-start">\n' +
                                            '                                    <time class="flight-start-time">'+_split_date(item["outbound"]["flights"][0]["departs_at"], 'h')+'</time>\n' +
                                            '                                    <div class="flight-start-date">\n' +
                                            '                                        <span class="flight-start-date-day">'+_split_date_item(_split_date(item["outbound"]["flights"][0]["departs_at"], 'd'), 'd')+'</span>'+_get_month_full(_split_date_item(_split_date(item["outbound"]["flights"][0]["departs_at"], 'd'), 'm'))+'\n' +
                                            '                                    </div>\n' +
                                            '                                    <div class="flight-start-city">\n' +
                                            '                                        <span class="flight-start-city-name" data-fcode="'+start_stops+'"></span>\n' +
                                            '                                    </div>\n' +
                                            '                                </div>\n' +
                                            '                                <section class="flight-stop-layovers">\n' +
                                            '                                    <header class="flight-stop-layovers-time">\n' +
                                            '                                        <span class="formatted_time">'+_split_time(item["outbound"]["duration"], 'h')+'h</span>\n' +
                                            '                                        &nbsp;\n' +
                                            '                                        <span class="formatted_time">'+_split_time(item["outbound"]["duration"], 'm')+'m</span>\n' +
                                            '                                    </header>\n' +
                                            '                                    <main class="flight-stop-layovers-list">\n' +
                                            '                                        <div class="flight-stop-layovers-list-wrapper">\n'
                                            +stops+
                                            '                                        </div>\n' +
                                            '                                    </main>\n' +
                                            '                                </section>\n' +
                                            '                                <div class="flight-start flight-end">\n' +
                                            '                                    <div class="flight-start-date">\n' +
                                            '                                        <span class="flight-start-date-day">'+_split_date_item(_split_date(item["outbound"]["flights"][item["outbound"]["flights"].length - 1]["arrives_at"], 'd'), 'd')+'</span>'+_get_month_full(_split_date_item(_split_date(item["outbound"]["flights"][item["outbound"]["flights"].length - 1]["arrives_at"], 'd'), 'm'))+'\n' +
                                            '                                    </div>\n' +
                                            '                                    <time class="flight-start-time">'+_split_date(item["outbound"]["flights"][item["outbound"]["flights"].length - 1]["arrives_at"], 'h')+'</time>\n' +
                                            '                                    <div class="flight-start-city">\n' +
                                            '                                        <span class="flight-start-city-name" data-fcode="'+end_stops+'"></span>\n' +
                                            '                                    </div>\n' +
                                            '                                </div>\n' +
                                            '                            </div>\n' +
                                            '\n' +
                                            '                            <div class="booking-item-more">\n' +
                                            '                                <ul class="extra-info">\n' +
                                            '                                    <li><i class="fa fa-circle-o"></i> '+st_amadeus_text.travel_class+': '+item["travel_class"]+'</li>\n' +
                                            '                                    <li><i class="fa fa-circle-o"></i> '+st_amadeus_text.cabin_code+': '+item["cabin_code"]+'</li>\n' +
                                            '                                    <li><i class="fa fa-circle-o"></i> '+st_amadeus_text.fare_family+': '+item["fare_family"]+'</li>\n' +
                                            '                                </ul>\n' +
                                            '                            </div>\n' +
                                            '\n' +
                                            '                            <div class="flight-detail-info">\n'
                                            +flist+
                                            '                            </div>\n' +
                                            '                        </div>\n' +
                                            '                        <div class="amadeus-right">\n' +
                                            '                            <i class="fa fa-chevron-down" aria-hidden="true"></i>\n' +
                                            '                        </div>\n' +
                                            '                    </div>';
                                    }

                                }
                                $('#load-more', list).show();
                            }else{
                                $('#load-more', list).hide();
                                var te = st_amadeus_text.no_result;
                            }

                            listResult.html(te);
                            if($('.flight-stop-layover-iata').length) {
                                $('.flight-stop-layover-iata').tooltip();
                            }
                        }
                    }).done(function () {
                        loadMore();
                        overlay.fadeOut();
                    }).error(function (er) {
                        if(er.statusText == 'error'){
                            listResult.append(st_amadeus_text.error_text);
                        }else if(er.statusText == 'Bad Request'){
                            if(er.responseJSON.status == 400){
                                var message = JSON.parse(er.responseJSON.message);
                                keys = Object.keys(message);
                                var ie;
                                var errStr = "";
                                for(ie = 0; ie < Object.keys(message).length; ie++){
                                    errStr += _get_name_param(keys[ie]) + ": " + message[keys[ie]] + "\n";
                                }
                                listResult.html(errStr);
                            }
                        }
                        overlay.fadeOut();
                    });
                }
            }
        }
        $(document).on('click', '.amadeus-right', function () {
            $(this).toggleClass('open');
            var sparent = $(this).closest('.amadeus-item');
            var detail = $('.flight-detail-info', sparent);
            detail.slideToggle();
        });

        function _split_time(timeStr, valStr){
            if(timeStr != '') {
                var res = timeStr.split(":");
                if (valStr == 'h')
                    return res[0];
                else
                    return res[1];
            }
        }
        function _split_date(dateTimeStr, valStr){
            if(dateTimeStr != '') {
                var res = dateTimeStr.split("T");
                if (valStr == 'd')
                    return res[0];
                else
                    return res[1];
            }
        }
        function _split_date_item(dateStr, valStr){
            if(dateStr != '') {
                var res = dateStr.split("-");
                if (valStr == 'y'){
                    return res[0];
                }else if(valStr == 'm'){
                    return res[1];
                }else{
                    return res[2];
                }
            }
        }
        function _get_month_full(numMonth){
            var monthNames = [st_amadeus_text.jan, st_amadeus_text.feb, st_amadeus_text.mar, st_amadeus_text.apr, st_amadeus_text.may, st_amadeus_text.jun,
                st_amadeus_text.jul, st_amadeus_text.aug, st_amadeus_text.sep, st_amadeus_text.oct, st_amadeus_text.nov, st_amadeus_text.dec
            ];

            if(Number.isInteger(parseInt(numMonth)) && parseInt(numMonth) > 0 && parseInt(numMonth) < 13){
                return monthNames[numMonth - 1];
            }else{
                return monthNames[0];
            }
        }
        function _get_layover_time(start, end){
            var timeStart = _split_date(start, 't');
            var timeEnd = _split_date(end, 't');
            var dateStart = _split_date(start, 'd');
            var dateEnd = _split_date(end, 'd');
            d1 = moment(dateStart + " " + timeStart, "YYYY-MM-DD HH:mm");
            d2 = moment(dateEnd + " " + timeEnd, "YYYY-MM-DD HH:mm");
            var hourDiff = d2.diff(d1,'hours');
            var minuteDiff = d2.diff(d1,'minutes');
            hourDuration = Math.floor(minuteDiff/60);
            minuteDuration = minuteDiff % 60;
            if(String(hourDuration).length == 1)
                hourDuration = '0' + String(hourDuration);

            if(String(minuteDuration).length == 1)
                minuteDuration = '0' + String(minuteDuration);

            return hourDuration + 'h' + ' ' + minuteDuration + 'm';
        }
        function _get_format_money(money){
            var currencySymbol = st_amadeus.currency_symbol;
            var currencySymbolPos = st_amadeus.currency_symbol_pos;
            switch (currencySymbolPos){
                case 'left':
                    return currencySymbol + money;
                    break;
                case 'right':
                    return money + currencySymbol;
                    break;
                case 'left_space':
                    return currencySymbol + " " + money;
                    break;
                case 'right_space':
                    return money + " " + currencySymbol;
                    break;
                default:
                    return currencySymbol + money;
                    break;
            }
        }

        function _get_name_param(param_key){
            switch (param_key){
                case 'apikey':
                    return st_amadeus_text.api_key;
                    break;
                case 'origin':
                    return st_amadeus_text.origin
                    break;
                case 'destination':
                    return st_amadeus_text.destination;
                    break;
                case 'departure_date':
                    return st_amadeus_text.departure_date;
                    break;
                case 'return_date':
                    return st_amadeus_text.return_date;
                    break;
                case 'adults':
                    return st_amadeus_text.adults;
                    break;
                case 'children':
                    return st_amadeus_text.children;
                    break;
                case 'infants':
                    return st_amadeus_text.infants;
                    break;
                case 'currency':
                    return st_amadeus_text.currency;
                    break;
                case 'others':
                    return st_amadeus_text.others;
                default:
                    return '';
                    break;
            }
        }

        function onlyUnique(value, index, self) {
            return self.indexOf(value) === index;
        }

        var cj = 0;
        function loadMore(){
            var arr_fcode = [];
            if(cj == 0) {
                $('.fstop').each(function () {
                    var fcode = $(this).text();
                    arr_fcode.push(fcode);
                });
                $('.flight-start-city-name').each(function () {
                    var fcode = $(this).data('fcode');
                    arr_fcode.push(fcode);
                });
                $('#depature-title .fcode').each(function () {
                    var fcode = $(this).text();
                    arr_fcode.push(fcode);
                });
                $(".amadeus-list-result").find('.fcode').each(function () {
                    var fcode = $(this).text();
                    arr_fcode.push(fcode);
                });
                var unique_fcode = arr_fcode.filter(onlyUnique);
                var airport = [];
                var jsonRequest;
                for (var jj = 0; jj < unique_fcode.length; jj++) {
                    jsonRequest = $.getJSON("https://api.sandbox.amadeus.com/v1.2/location/" + unique_fcode[jj] + "?apikey=" + st_amadeus.apikey, function (ev) {
                        if(ev.hasOwnProperty('city')){
                            var city = ev.city.name;
                            airport[ev.city.code] = {
                                'city': city,
                                'airport': ev.airports[0]["name"],
                            };
                            for (var j = 0; j < ev.airports.length; j++) {
                                airport[ev.airports[j]["code"]] = {
                                    'city': ev.airports[j]["city_name"],
                                    'airport': ev.airports[j]["name"],
                                };
                            }
                        }else{
                            for (var j = 0; j < ev.airports.length; j++) {
                                airport[ev.airports[j]["code"]] = {
                                    'city': ev.airports[j]["city_name"],
                                    'airport': ev.airports[j]["name"],
                                };
                            }
                        }
                    });
                }

                $.when(jsonRequest)
                    .done(function(firstResult, secondResult) {
                        setTimeout(function (d) {
                            $('.fstop').each(function () {
                                var fcode = $(this).text();
                                $(this).text(airport[fcode]["airport"] + ' ( ' + fcode + ' )');
                            });
                            $('#depature-title .fcode').each(function () {
                                var fcode = $(this).text();
                                $(this).text(airport[fcode]["city"] + ' ( ' + fcode + ' )');
                            });
                            $(".amadeus-list-result").find('.fcode').each(function () {
                                var fcode = $(this).text();
                                $(this).text(airport[fcode]["airport"] + ' ( ' + fcode + ' )');
                            });
                            $('.flight-start-city-name').each(function () {
                                var fcode = $(this).data('fcode');
                                $(this).text(airport[fcode]["city"]);
                            });
                        }, 2000);
                    });
                /*setTimeout(function (d) {
                    $('#depature-title .fcode').each(function () {
                        var fcode = $(this).text();
                        $(this).text(airport[fcode]["city"] + ' ( ' + fcode + ' )');
                    });
                    $(".amadeus-list-result").find('.fcode').each(function () {
                        var fcode = $(this).text();
                        $(this).text(airport[fcode]["airport"] + ' ( ' + fcode + ' )');
                    });
                    $('.flight-start-city-name').each(function () {
                        var fcode = $(this).data('fcode');
                        $(this).text(airport[fcode]["city"]);
                    });
                }, 5000);*/
            }

            //Pag
            $(".amadeus-list-result .hidden").slice(0,10).removeClass("hidden");
            var total = $('.amadeus-list-result .amadeus-item').length;
            var totalHidden = $('.amadeus-list-result .amadeus-item.hidden').length;
            if(totalHidden == 0){
                $('#load-more').html(st_amadeus_text.no_more);
                $('#load-more').addClass('no-more');
            }else{
                $('#load-more').html(st_amadeus_text.load_more);
                $('#load-more').removeClass('no-more');
            }
            cj++;
        }

        $("#load-more").on("click",loadMore);

        //$('.amadeus-search-box .btn-amd-search-flight').click(function (e) {
        $(document).on('click', '.amadeus-search-box .btn-amd-search-flight', function (e) {
            cj = 0;
            e.preventDefault();
            var me = $(this);
            var boss = $('.amadeus-content-search');
            var overlay = $('.overlay-form', boss);
            var sparent = me.closest('.btn-amd-search-flight');
            var sform = $(this).closest('form');
            var required = !1;
            if ($('#location_origin', sform).val() == '') {
                required = !0;
                $('#location_origin', sform).addClass('error')
            } else {
                $('#location_origin', sform).removeClass('error')
            }
            if ($('#location_destination', sform).val() == '') {
                required = !0;
                $('#location_destination', sform).addClass('error')
            } else {
                $('#location_destination', sform).removeClass('error')
            }
            if ($('#amd-field-depart-date', sform).val() == '') {
                required = !0;
                $('#amd-field-depart-date', sform).addClass('error')
            } else {
                $('#amd-field-depart-date', sform).removeClass('error')
            }
            if($('.amd-passengers-class input[name="adults"]', sform).val() == 0 && $('.amd-passengers-class input[name="children"]', sform).val() == 0 && $('.amd-passengers-class input[name="infants"]', sform).val() == 0){
                required = !0;
                $('.amd_group_display', sform).addClass('error')
            }else{
                $('.amd_group_display', sform).removeClass('error')
            }
            if(!required){
                overlay.fadeIn();
                var data  = sform.serializeArray();
                var dataSeach = [];
                if(data.length > 0){
                    for(var i = 0; i< data.length; i++){
                        dataSeach[data[i].name] = data[i].value;
                    }
                }
                _get_search_result(dataSeach);
            }
        })
    });
})(jQuery)