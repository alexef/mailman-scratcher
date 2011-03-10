/* Move first word from h2, i.e. cut [Doctorat-anunturi] */

$(document).ready(function () {
  text = $('h2').html();
  parts = text.split(']');
  while (parts[0].trim()[0] == '[') {
    label = parts.shift().trim().slice(1);
    $('#list-label').prepend('<span>&bull; ' + label + '</span>');
  }
  
  text = parts.join(' ');
  $('h2').html(text);
});
  
  
