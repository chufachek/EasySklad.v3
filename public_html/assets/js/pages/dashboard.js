$(function () {
  if (!$('[data-page="dashboard"]').length) return;

  function load(range) {
    var companyId = AppState.get('activeCompanyId', null);
    var warehouseId = AppState.get('activeWarehouseId', null);
    Api.get('/api/dashboard', { companyId: companyId, warehouseId: warehouseId, range: range }).done(function (resp) {
      if (!resp.ok) return;
      var data = resp.data;
      $('#metricRevenue').text(data.revenue_total + ' ₽');
      $('#metricOrders').text(data.orders_count);
      $('#metricStock').text(data.stock_total_sku + ' SKU');
      $('#metricOps').text(data.last_ops.length);

      var stats = [
        { label: 'Средний чек', value: data.avg_check },
        { label: 'Оплачено', value: data.paid_count },
        { label: 'Черновики', value: data.draft_count },
        { label: 'Низкий остаток', value: data.stock_low_count }
      ];
      $('#orderStats').html(stats.map(function (s) {
        return '<div class="stat"><span>' + s.label + '</span><strong>' + s.value + '</strong></div>';
      }).join(''));

      $('#lastOrders tbody').html(data.last_orders.map(function (o) {
        return '<tr><td>#' + o.id + '</td><td>' + o.customer_name + '</td><td>' + o.total + '</td><td><span class="badge">' + o.status + '</span></td><td>' + o.created_at + '</td></tr>';
      }).join(''));

      $('#topProducts').html(data.top_products.map(function (p) {
        return '<li>' + p.name + ' — ' + p.qty + '</li>';
      }).join(''));
      $('#lowStock').html(data.low_stock_products.map(function (p) {
        return '<li>' + p.name + ' — ' + p.qty + '</li>';
      }).join(''));
      $('#activity').html(data.last_ops.map(function (op) {
        return '<li>' + op.type + ' ' + op.qty + ' (' + op.created_at + ')</li>';
      }).join(''));

      Charts.render(data.revenue_series, data.pie_series);
    });
  }

  var currentRange = '7d';
  load(currentRange);

  $('#dashboardRange button').on('click', function () {
    $('#dashboardRange button').removeClass('active');
    $(this).addClass('active');
    currentRange = $(this).data('range');
    load(currentRange);
  });
});
