$(function () {
  if (!$('[data-page="orders"]').length) return;

  var table = $('#ordersTable tbody');
  var warehouseId = AppState.get('activeWarehouseId', null);

  function load() {
    if (!warehouseId) return;
    Api.get('/api/warehouses/' + warehouseId + '/orders').done(function (resp) {
      if (!resp.ok) return;
      table.html(resp.data.map(function (o) {
        return '<tr><td>#' + o.id + '</td><td>' + o.customer_name + '</td><td>' + o.total + '</td><td><span class="badge">' + o.status + '</span></td><td>' + o.created_at + '</td></tr>';
      }).join(''));
    });
  }

  load();

  $('#createOrder').on('click', function () {
    var overlay = Modal.open({
      title: 'Новый заказ',
      content: '<form id="orderForm" class="form">' +
        '<label>Клиент<input name="customer_name" /></label>' +
        '<label>Оплата<select name="payment_method"><option value="cash">Наличные</option><option value="card">Карта</option></select></label>' +
        '<label>Скидка<input name="discount" type="number" step="0.01" value="0" /></label>' +
        '<label>Итого<input name="total" type="number" step="0.01" /></label>' +
        '<label>Статус<select name="status"><option value="draft">Черновик</option><option value="paid">Оплачен</option></select></label>' +
        '<button type="submit" class="btn btn-accent">Сохранить</button></form>'
    });
    overlay.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();
      var data = {
        customer_name: this.customer_name.value,
        payment_method: this.payment_method.value,
        discount: this.discount.value,
        total: this.total.value,
        status: this.status.value,
        items: [],
        services: []
      };
      Api.request('POST', '/api/warehouses/' + warehouseId + '/orders', data).done(function () {
        Toast.show('Заказ создан');
        Modal.close(overlay);
        load();
      });
    });
  });
});
