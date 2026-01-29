$(function () {
  if (!$('[data-page="warehouses"]').length) return;

  var table = $('#warehousesTable tbody');
  var companyId = AppState.get('activeCompanyId', null);

  function load() {
    if (!companyId) return;
    Api.get('/api/companies/' + companyId + '/warehouses').done(function (resp) {
      if (!resp.ok) return;
      table.html(resp.data.map(function (w) {
        return '<tr><td>' + w.id + '</td><td>' + w.name + '</td><td>' + w.address + '</td><td><button class="btn btn-light" data-edit="' + w.id + '">Редактировать</button></td></tr>';
      }).join(''));
    });
  }

  load();

  $('#createWarehouse').on('click', function () {
    var overlay = Modal.open({
      title: 'Новый склад',
      content: '<form id="warehouseForm" class="form"><label>Название<input name="name" required /></label><label>Адрес<input name="address" /></label><button type="submit" class="btn btn-accent">Сохранить</button></form>'
    });
    overlay.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();
      var data = { name: this.name.value, address: this.address.value };
      Api.request('POST', '/api/companies/' + companyId + '/warehouses', data).done(function () {
        Toast.show('Склад создан');
        Modal.close(overlay);
        load();
      });
    });
  });
});
