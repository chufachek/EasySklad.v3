$(function () {
  if (!$('[data-page="categories"]').length) return;

  var table = $('#categoriesTable tbody');
  var companyId = AppState.get('activeCompanyId', null);

  function load() {
    if (!companyId) return;
    Api.get('/api/companies/' + companyId + '/categories').done(function (resp) {
      if (!resp.ok) return;
      table.html(resp.data.map(function (c) {
        return '<tr><td>' + c.id + '</td><td>' + c.name + '</td><td><button class="btn btn-light" data-edit="' + c.id + '">Редактировать</button></td></tr>';
      }).join(''));
    });
  }

  load();

  $('#createCategory').on('click', function () {
    var overlay = Modal.open({
      title: 'Новая категория',
      content: '<form id="categoryForm" class="form"><label>Название<input name="name" required /></label><button type="submit" class="btn btn-accent">Сохранить</button></form>'
    });
    overlay.querySelector('form').addEventListener('submit', function (e) {
      e.preventDefault();
      var data = { name: this.name.value };
      Api.request('POST', '/api/companies/' + companyId + '/categories', data).done(function () {
        Toast.show('Категория создана');
        Modal.close(overlay);
        load();
      });
    });
  });
});
