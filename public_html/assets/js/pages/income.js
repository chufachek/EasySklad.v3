$(function () {
  if (!$('[data-page="income"]').length) return;

  var table = $('#incomeTable tbody');
  var warehouseId = AppState.get('activeWarehouseId', null);

  function load() {
    if (!warehouseId) return;
    Api.get('/api/warehouses/' + warehouseId + '/income').done(function (resp) {
      if (!resp.ok) return;
      table.html(resp.data.map(function (i) {
        return '<tr><td>' + i.id + '</td><td>' + i.supplier + '</td><td>' + i.date + '</td><td>' + i.total_cost + '</td></tr>';
      }).join(''));
    });
  }

  load();

  $('#createIncome').on('click', function () {
    var overlay = Modal.open({
      title: 'Новый приход',
      content: '<form id="incomeForm" class="form">' +
        '<label>Поставщик<input name="supplier" required /></label>' +
        '<label>Дата<input name="date" type="date" required /></label>' +
        '<label>Сумма<input name="total_cost" type="number" step="0.01" /></label>' +
        '<button type="submit" class="btn btn-accent">Сохранить</button></form>'
    });
    overlay.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();
      var data = {
        supplier: this.supplier.value,
        date: this.date.value,
        total_cost: this.total_cost.value,
        items: []
      };
      Api.request('POST', '/api/warehouses/' + warehouseId + '/income', data).done(function () {
        Toast.show('Приход создан');
        Modal.close(overlay);
        load();
      });
    });
  });
});
