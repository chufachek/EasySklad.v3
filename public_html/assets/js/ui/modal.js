var Modal = {
  open: function (options) {
    var overlay = document.createElement('div');
    overlay.className = 'modal-overlay open';
    var modal = document.createElement('div');
    modal.className = 'modal' + (options.fullscreen ? ' fullscreen' : '');
    modal.innerHTML = '<div class="modal-header"><h3>' + options.title + '</h3><button class="icon-btn" data-close>✕</button></div>' +
      '<div class="modal-body">' + options.content + '</div>' +
      (options.footer ? '<div class="modal-footer">' + options.footer + '</div>' : '');
    overlay.appendChild(modal);
    document.getElementById('modalRoot').appendChild(overlay);

    overlay.addEventListener('click', function (e) {
      if (e.target === overlay) {
        Modal.close(overlay);
      }
    });
    modal.querySelector('[data-close]').addEventListener('click', function () {
      Modal.close(overlay);
    });
    return overlay;
  },
  close: function (overlay) {
    if (!overlay) return;
    overlay.classList.remove('open');
    setTimeout(function () {
      overlay.remove();
    }, 150);
  }
};
