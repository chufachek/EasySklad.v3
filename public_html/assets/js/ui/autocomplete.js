var Autocomplete = {
  attach: function (input, fetcher, onSelect) {
    var list = document.createElement('div');
    list.className = 'autocomplete';
    input.parentNode.appendChild(list);
    input.addEventListener('input', function () {
      var value = input.value;
      fetcher(value).done(function (resp) {
        list.innerHTML = '';
        if (!resp.ok) return;
        resp.data.forEach(function (item) {
          var el = document.createElement('div');
          el.className = 'autocomplete-item';
          el.textContent = item.name;
          el.addEventListener('click', function () {
            onSelect(item);
            list.innerHTML = '';
          });
          list.appendChild(el);
        });
      });
    });
  }
};
