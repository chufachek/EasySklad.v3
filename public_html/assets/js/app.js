$(function () {
  var theme = localStorage.getItem('theme') || 'light';
  $('#themeToggle').on('click', function () {
    theme = theme === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
  });

  var sidebar = $('#sidebar');
  var collapsed = AppState.get('sidebarCollapsed', false);
  if (collapsed) sidebar.addClass('collapsed');
  $('#sidebarToggle').on('click', function () {
    sidebar.toggleClass('collapsed');
    AppState.set('sidebarCollapsed', sidebar.hasClass('collapsed'));
  });

  $('#userMenu').on('click', function () {
    $(this).toggleClass('open');
  });

  var page = $('.page').data('page');
  $('.nav-item[data-page="' + page + '"]').addClass('active');

  Breadcrumbs.build();

  initSelects();
  loadUserMeta();

  $('[data-modal="incomeQuick"]').on('click', function () {
    openQuickIncome();
  });
  $('[data-modal="orderQuick"]').on('click', function () {
    openQuickOrder();
  });
  $('[data-modal="pos"]').on('click', function () {
    openPos();
  });
});

function initSelects() {
  var companySelect = document.getElementById('companySelect');
  var warehouseSelect = document.getElementById('warehouseSelect');
  if (!companySelect || !warehouseSelect) return;

  var companyChoices = new Choices(companySelect, { searchEnabled: true, shouldSort: false });
  var warehouseChoices = new Choices(warehouseSelect, { searchEnabled: true, shouldSort: false });

  Api.get('/api/companies').done(function (resp) {
    if (!resp.ok) return;
    companyChoices.clearChoices();
    companyChoices.setChoices(resp.data.map(function (c) {
      return { value: c.id, label: c.name };
    }), 'value', 'label', true);
    var activeCompanyId = AppState.get('activeCompanyId', resp.data[0] ? resp.data[0].id : null);
    if (activeCompanyId) {
      companyChoices.setChoiceByValue(String(activeCompanyId));
      AppState.set('activeCompanyId', activeCompanyId);
      AppState.set('activeCompanyName', resp.data.find(function (c) { return c.id == activeCompanyId; }).name);
      loadWarehouses(activeCompanyId, warehouseChoices);
    }
  });

  companySelect.addEventListener('change', function () {
    var companyId = companySelect.value;
    AppState.set('activeCompanyId', companyId);
    AppState.set('activeCompanyName', companySelect.options[companySelect.selectedIndex].text);
    loadWarehouses(companyId, warehouseChoices, true);
    Breadcrumbs.build();
  });

  warehouseSelect.addEventListener('change', function () {
    AppState.set('activeWarehouseId', warehouseSelect.value);
    AppState.set('activeWarehouseName', warehouseSelect.options[warehouseSelect.selectedIndex].text);
    Breadcrumbs.build();
  });
}

function loadWarehouses(companyId, choices, reset) {
  Api.get('/api/companies/' + companyId + '/warehouses').done(function (resp) {
    if (!resp.ok) return;
    choices.clearChoices();
    choices.setChoices(resp.data.map(function (w) {
      return { value: w.id, label: w.name };
    }), 'value', 'label', true);
    var activeWarehouseId = reset ? null : AppState.get('activeWarehouseId', resp.data[0] ? resp.data[0].id : null);
    if (activeWarehouseId) {
      choices.setChoiceByValue(String(activeWarehouseId));
      AppState.set('activeWarehouseId', activeWarehouseId);
      AppState.set('activeWarehouseName', resp.data.find(function (w) { return w.id == activeWarehouseId; }).name);
    } else if (resp.data[0]) {
      AppState.set('activeWarehouseId', resp.data[0].id);
      AppState.set('activeWarehouseName', resp.data[0].name);
    }
    Breadcrumbs.build();
  });
}

function loadUserMeta() {
  Api.get('/api/me').done(function (resp) {
    if (!resp.ok) return;
    $('#userId').text(resp.data.id);
    $('#userTariff').text(resp.data.tariff);
    $('#userBalance').text(resp.data.balance);
  });
}

function openQuickIncome() {
  Modal.open({
    title: 'Быстрый приход',
    content: '<form id="quickIncome" class="form"><label>Поставщик<input name="supplier" /></label><label>Дата<input name="date" type="date" /></label><button type="submit" class="btn btn-accent">Создать</button></form>'
  });
}

function openQuickOrder() {
  Modal.open({
    title: 'Быстрый заказ',
    content: '<form id="quickOrder" class="form"><label>Клиент<input name="customer_name" /></label><label>Оплата<select name="payment_method"><option>cash</option><option>card</option></select></label><button type="submit" class="btn btn-accent">Создать</button></form>'
  });
}

function openPos() {
  Modal.open({
    title: 'Касса',
    fullscreen: true,
    content: '<div class="pos-layout"><div><input id="posSearch" placeholder="Поиск товара" /><div class="pos-items" id="posItems"></div></div><div class="pos-summary"><div><strong>Итого:</strong> <span id="posTotal">0</span> ₽</div><button class="btn btn-accent" id="posPay">Оплатить</button></div></div>'
  });
}
