/* Move first word from h1, i.e. cut [Doctorat-anunturi] */

$(document).ready(function () {
  text = $('h1').html();
  parts = text.split(']');
  while (parts[0].trim()[0] == '[') {
    label = parts.shift().trim().slice(1);
    $('#list-label').prepend('<span>&bull; ' + label + '</span>');
  }
  
  text = parts.join(' ');
  $('h1').html(text);
});
  
  
