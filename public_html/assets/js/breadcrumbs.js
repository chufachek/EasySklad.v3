var Breadcrumbs = {
  build: function () {
    var container = document.getElementById('breadcrumbs');
    if (!container) return;
    var page = document.querySelector('.page');
    if (!page) {
      container.textContent = '';
      return;
    }
    var name = page.getAttribute('data-page');
    var map = {
      dashboard: 'Дашборд',
      profile: 'Профиль',
      company: 'Компания',
      warehouses: 'Склады',
      products: 'Товары',
      categories: 'Категории',
      income: 'Приходы',
      orders: 'Заказы',
      services: 'Услуги'
    };
    var company = AppState.get('activeCompanyName', null);
    var warehouse = AppState.get('activeWarehouseName', null);
    var parts = ['Easy. склад'];
    if (company) parts.push(company);
    if (warehouse) parts.push(warehouse);
    if (map[name]) parts.push(map[name]);
    container.textContent = parts.join(' / ');
  }
};
