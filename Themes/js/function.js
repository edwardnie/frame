function setCookie( name, value ) {
    var Days = 30;
    var exp = new Date();
    exp.setTime( exp.getTime() + Days * 24 * 60 * 60 * 1000 );
    document.cookie = name + "=" + escape( value ) + ";expires=" + exp.toGMTString();
}


function getCookie( name ) {
    var arr = document.cookie.match( new RegExp( "(^| )" + name + "=([^;]*)(;|$)" ) );
    if ( arr != null ) {
        return (arr[2]);
    } else {
        return "";
    }
}


function deleteCookie( name ) {
    var exp = new Date();
    exp.setTime( exp.getTime() - 1 );
    var cval = getCookie( name );
    if ( cval != null ) document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
}


/**
 * 打开面板
 * @param id
 * @param url
 * @param height
 */
showBox = function ( id, url, height ) {
    $(".mask").show();
    $( "#" + id ).html( '<iframe id="' + id + 'Iframe" allowTransparency="true" scrolling="auto" width="100%" ' +
    'frameborder="0" height="' + height + '" src="' + url + '"></iframe>' );
    $( "#" + id + "Iframe" ).load( function () {
        $( "#" + id ).css( {"display" : "block"} );
    } );
    $("#close-box").show();
    return true;
};

/**
 * 关闭面板
 * @param id
 */
hideBox = function ( id ) {
    $( "#" + id ).css( {"display" : "none"} );
    $(".mask").hide();
    window.location.reload();
    return true;
};



/**
 * 生成URL
 * @param string method
 * @param object params
 * @returns String
 */
generateUrl = function ( base_url, method, params ) {
    params = params || {};
    var url = base_url + '?method=' + method;
    for ( key in params ) {
        url += "&" + key + "=" + params[key];
    }
    return url;
};


showObject = function ( type, value ) {
    if ( type == 1 ) {
        $( '.' + value ).show();
    } else {
        $( '#' + value ).show();
    }
};


hideObject = function ( type, value ) {
    if ( type == 1 ) {
        $( '.' + value ).hide();
    } else {
        $( '#' + value ).hide();
    }
};

ObjectSize = function ( obj ) {
    var size = 0, key;
    for ( key in obj ) {
        if ( obj.hasOwnProperty( key ) ) size++;
    }
    return size;
};

function isArray( obj ) {
    return Object.prototype.toString.call( obj ) === '[object Array]';
}

isEmpty = function ( str ) {
    if ( str == null || str == '' || str == undefined ) {
        return true;
    }
    return false;
};

getQueryString = function ( url ) {
    var result = url.match( new RegExp( "[\?\&][^\?\&]+=[^\?\&]+", "g" ) );
    var rs = [];
    for ( var i = 0; i < result.length; i++ ) {
        result[i] = result[i].substring( 1 );
        var res = result[i].split( "=" );
        rs[i] = res[1];
    }
    return rs;
};

generateHash = function ( data, key ) {
    data.sort();
    console.log(data,key,md5( data.join() + key ));
    return md5( data.join() + key );
};

/**
 * js补零
 * @param num
 * @param n
 * @returns {*}
 */
function pad2( num, n ) {
    if ( (num + "").length >= n ) return num;
    return pad2( "0" + num, n );
}
