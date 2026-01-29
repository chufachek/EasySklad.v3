var Toast = {
  show: function (message) {
    var root = document.getElementById('toastRoot');
    if (!root) return;
    var toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    root.appendChild(toast);
    setTimeout(function () { toast.remove(); }, 3000);
  }
};
