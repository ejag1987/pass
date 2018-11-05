var urlSitio;

$(function() {
    urlSitio = $('#site-url').data('url');
});

function addFile(file) {
    return '<div class="preview"></div>' +
        '<div class="datos-imagen">'+
        '<span class="file-name">' + file.name + '</span> <span class="file-size">(' + humanizeSize(file.size) + ')</span><br />' +
        'Estado: <span class="file-status">En espera...</span>'+
        '<div class="progress progress-striped active">'+
        '<progress class="progress is-primary progress-bar" value="0" max="100">0%</progress>'+
        '</div>'+
        '</div>';
}

function humanizeSize(size) {
    var i = Math.floor( Math.log(size) / Math.log(1024) );
    return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'kB', 'MB', 'GB', 'TB'][i];
}

jQuery.fn.removeClassExcept = function (val) {
    return this.each(function () {
        $(this).removeClass().addClass(val);
    });
};