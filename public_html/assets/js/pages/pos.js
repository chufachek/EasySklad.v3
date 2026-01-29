$(function () {
  $(document).on('keydown', function (e) {
    if (e.key === '/') {
      var input = $('#posSearch');
      if (input.length) {
        e.preventDefault();
        input.focus();
      }
    }
    if (e.key === 'Enter' && !e.ctrlKey) {
      if ($('#posSearch').is(':focus')) {
        $('#posAdd').trigger('click');
      }
    }
    if (e.key === 'Enter' && e.ctrlKey) {
      $('#posPay').trigger('click');
    }
  });
});
