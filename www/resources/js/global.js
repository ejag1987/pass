var urlSitio;

$(function() {
    urlSitio = $('#site-url').data('url');
});

/*
 URL → https://github.com/melbon/jquery.useWord
 */
$.fn.lastWord = function() {
    var text = this.text().trim().split(" ");
    var last = text.pop();
    this.html(text.join(" ") + (text.length > 0 ? " <span class='lastWord'>" + last + "</span>" : last));
};


$.fn.firstWord = function() {
    var text = this.text().trim().split(" ");
    console.log(this);
    var first = text.shift();
    this.html((text.length > 0 ? "<span class='firstWord'>"+ first + "</span> " : first) + text.join(" "));
};
