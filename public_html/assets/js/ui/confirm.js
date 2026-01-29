var Confirm = {
  show: function (message, onConfirm) {
    var overlay = Modal.open({
      title: 'Подтверждение',
      content: '<p>' + message + '</p>',
      footer: '<button class="btn btn-light" data-cancel>Отмена</button><button class="btn btn-accent" data-confirm>OK</button>'
    });
    overlay.querySelector('[data-cancel]').addEventListener('click', function () {
      Modal.close(overlay);
    });
    overlay.querySelector('[data-confirm]').addEventListener('click', function () {
      if (onConfirm) onConfirm();
      Modal.close(overlay);
    });
  }
};
