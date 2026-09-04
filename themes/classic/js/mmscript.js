/*
 * Smart Notifications
 */
function errorNotificationBig(title, content) {
    $.bigBox({
        title: title,
        content: content,
        color: "#C46A69",
        icon: "fa fa-warning shake animated",
        number: "1",
        timeout: 5000,
    });
}

function infoNotificationBig(title, content) {
    $.bigBox({
        title: title,
        content: content,
        color: "#3276B1",
        icon: "fa fa-bell swing animated",
        number: "2",
        timeout: 5000,
    });
}

function successNotificationBig(title, content) {
    $.bigBox({
        title: title,
        content: content,
        color: "#739E73",
        icon: "fa fa-check",
        number: "3",
        timeout: 5000,
    });
}

function successNotificationSmall(title, content) {
    $.smallBox({
        title: title,
        content: content,
        color: "#739E73",
        iconSmall: "fa fa-thumbs-up bounce animated",
        timeout: 5000
    });
}

function errorNotificationSmall(title, content) {
    $.smallBox({
        title: title,
        content: content,
        color: "#C46A69",
        iconSmall: "fa fa-thumbs-down bounce animated",
        timeout: 5000
    });
}

function getParameterByName(name, url) {
    if (!url) {
        url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
    if (!results)
        return null;
    if (!results[2])
        return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}