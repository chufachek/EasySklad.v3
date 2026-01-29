$(function () {
  if (!$('[data-page="services"]').length) return;

  var table = $('#servicesTable tbody');
  var companyId = AppState.get('activeCompanyId', null);

  function load() {
    if (!companyId) return;
    Api.get('/api/companies/' + companyId + '/services').done(function (resp) {
      if (!resp.ok) return;
      table.html(resp.data.map(function (s) {
        return '<tr><td>' + s.id + '</td><td>' + s.name + '</td><td>' + s.price + '</td><td><button class="btn btn-light">Редактировать</button></td></tr>';
      }).join(''));
    });
  }

  load();

  $('#createService').on('click', function () {
    var overlay = Modal.open({
      title: 'Новая услуга',
      content: '<form id="serviceForm" class="form">' +
        '<label>Название<input name="name" required /></label>' +
        '<label>Цена<input name="price" type="number" step="0.01" /></label>' +
        '<label>Описание<textarea name="description"></textarea></label>' +
        '<button type="submit" class="btn btn-accent">Сохранить</button></form>'
    });
    overlay.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();
      var data = { name: this.name.value, price: this.price.value, description: this.description.value };
      Api.request('POST', '/api/companies/' + companyId + '/services', data).done(function () {
        Toast.show('Услуга создана');
        Modal.close(overlay);
        load();
      });
    });
  });
});
