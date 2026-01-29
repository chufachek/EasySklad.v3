$(function () {
  if (!$('[data-page="products"]').length) return;

  var table = $('#productsTable tbody');
  var categorySelect = document.getElementById('categoryFilter');
  var categoryChoices = new Choices(categorySelect, { searchEnabled: true, shouldSort: false });
  var warehouseId = AppState.get('activeWarehouseId', null);
  var companyId = AppState.get('activeCompanyId', null);

  function loadCategories() {
    if (!companyId) return;
    Api.get('/api/companies/' + companyId + '/categories').done(function (resp) {
      if (!resp.ok) return;
      categoryChoices.clearChoices();
      categoryChoices.setChoices([{ value: '', label: 'Все категории' }].concat(resp.data.map(function (c) {
        return { value: c.id, label: c.name };
      })), 'value', 'label', true);
    });
  }

  function load() {
    if (!warehouseId) return;
    Api.get('/api/warehouses/' + warehouseId + '/products', {
      search: $('#productSearch').val(),
      category_id: categorySelect.value
    }).done(function (resp) {
      if (!resp.ok) return;
      table.html(resp.data.map(function (p) {
        return '<tr><td>' + p.sku + '</td><td>' + p.name + '</td><td>' + p.price + '</td><td>' + (p.qty || 0) + '</td><td><button class="btn btn-light">Редактировать</button></td></tr>';
      }).join(''));
    });
  }

  loadCategories();
  load();

  $('#productSearch').on('input', load);
  categorySelect.addEventListener('change', load);

  $('#createProduct').on('click', function () {
    var overlay = Modal.open({
      title: 'Новый товар',
      content: '<form id="productForm" class="form">' +
        '<label>Название<input name="name" required /></label>' +
        '<label>SKU<input name="sku" /></label>' +
        '<label>Категория<select name="category_id" class="select"></select></label>' +
        '<label>Цена<input name="price" type="number" step="0.01" /></label>' +
        '<label>Себестоимость<input name="cost" type="number" step="0.01" /></label>' +
        '<label>Ед. изм.<input name="unit" value="шт" /></label>' +
        '<label>Мин. остаток<input name="min_stock" type="number" value="0" /></label>' +
        '<button type="submit" class="btn btn-accent">Сохранить</button></form>'
    });
    var select = overlay.querySelector('select');
    var choices = new Choices(select, { searchEnabled: true, shouldSort: false });
    Api.get('/api/companies/' + companyId + '/categories').done(function (resp) {
      if (!resp.ok) return;
      choices.setChoices(resp.data.map(function (c) {
        return { value: c.id, label: c.name };
      }), 'value', 'label', true);
    });

    overlay.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();
      var data = {
        name: this.name.value,
        sku: this.sku.value,
        category_id: this.category_id.value || null,
        price: this.price.value,
        cost: this.cost.value,
        unit: this.unit.value,
        min_stock: this.min_stock.value
      };
      Api.request('POST', '/api/warehouses/' + warehouseId + '/products', data).done(function () {
        Toast.show('Товар создан');
        Modal.close(overlay);
        load();
      });
    });
  });
});
