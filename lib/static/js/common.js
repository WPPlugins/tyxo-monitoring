function tx_jsonp($endpoint, $params, $callback, loading_callback, loading_callback_delay) {

    var _params    = {};
    if($params)	_params = $params;
    _params.apikey = tyxo_api_key;

    var delay_timeout;
    clearTimeout(delay_timeout);

    jQuery.ajax({
        url: tyxo_api_base+$endpoint,
        dataType: 'jsonp',
        data: _params,
        timeout: 16000,
        cache: false,
        success: function(dataWeGotViaJsonp){

            var isFunc = jQuery.isFunction($callback);
            if(isFunc) $callback(dataWeGotViaJsonp);

        },beforeSend: function (jqXHR, settings) {

            if(jQuery.isFunction(loading_callback) && typeof loading_callback_delay != "undefined") {
                delay_timeout	= setTimeout(function() { loading_callback('start'); }, loading_callback_delay);
            } else if(jQuery.isFunction(loading_callback)) {
                loading_callback('start');
            }

        }, complete: function(){

            if(jQuery.isFunction(loading_callback)) {
                clearTimeout(delay_timeout);
                loading_callback('end');
            }

        }
    });

}

/*
function aj(to, params, callback, apikey, loading_callback, loading_callback_delay) {

    if(window.main_ajax) window.main_ajax.abort();

    var _params	= {};
    if(params)	_params = params;

    var delay_timeout; clearTimeout(delay_timeout);

    window.main_ajax = jQuery.ajax({

        type: "POST",url: to,dataType: "json", timeout: 16000, cache: false,data: _params,success: function(response){

            if(response.meta.code == 200) {

                var isFunc = jQuery.isFunction(callback);
                if(isFunc) callback(response.data, response.meta);

            }

        }, error: function (jqXHR, textStatus, statusCode, errorThrown) {

            if(textStatus == 'timeout') console.log('Network Connection Problem');
            console.log("Ajax Error ("+to+")", textStatus);
            if(jQuery.isFunction(loading_callback)) loading_callback('end');


        }, statusCode: function(jqXHR, textStatus, statusCode, errorThrown){

            alert('404 Not Found');

        },beforeSend: function (jqXHR, settings) {

            jqXHR.setRequestHeader('ApiKey', apikey);
            //if(csrf) jqXHR.setRequestHeader('X-Csrf', csrf);

            if(jQuery.isFunction(loading_callback) && typeof loading_callback_delay != "undefined") {
                delay_timeout	= setTimeout(function() { loading_callback('start'); }, loading_callback_delay);
            } else if(jQuery.isFunction(loading_callback)) {
                loading_callback('start');
            }

        }, complete: function(){

            if(jQuery.isFunction(loading_callback)) {
                clearTimeout(delay_timeout);
                loading_callback('end');
            }

        }
    });

}
*/

function loader_animation_base (action) {

    if(action == 'start') {

        jQuery('#tyxo_loader_container').empty().html('<div style="padding: 32px 0; font-size: 18px"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Loading...</div>');

    } else {

        jQuery('#tyxo_loader_container').empty().html('');

    }

}

//php.js
function empty(mixed_var) {

    var undef, key, i, len;
    var emptyValues = [undef, null, false, 0, '', '0'];

    for (i = 0, len = emptyValues.length; i < len; i++) {
        if (mixed_var === emptyValues[i]) {
            return true;
        }
    }

    if (typeof mixed_var === 'object') {
        for (key in mixed_var) {
            return false;
        }
        return true;
    }

    return false;
}

//php.js
function isset() {
    var a = arguments,
        l = a.length,
        i = 0,
        undef;

    if (l === 0) {
        throw new Error('Empty isset');
    }

    while (i !== l) {
        if (a[i] === undef || a[i] === null) {
            return false;
        }
        i++;
    }

    return true;
}

//php.js
function number_format(number, decimals, dec_point, thousands_sep) {

    number = (number + '')
        .replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                    .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
            .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}

function trim_sring(x) {
    if(!x) return '';
    return x.replace(/^\s+|\s+$/gm,'');
}