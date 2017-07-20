module.exports.getCookie = getCookie;

function getCookie(key, socket) {
    var arr = socket.request.headers.cookie.match(new RegExp("(^| )" + key + "=([^;]*)(;|$)"));
    if (arr != null) {
        return (decodeURIComponent(arr[2]));
    } else {
        return "";
    }
}

