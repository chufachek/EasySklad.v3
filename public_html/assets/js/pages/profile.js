$(function () {
  if (!$('[data-page="profile"]').length) return;

  Api.get('/api/me').done(function (resp) {
    if (!resp.ok) return;
    $('#profileForm [name="email"]').val(resp.data.email);
    $('#profileForm [name="first_name"]').val(resp.data.first_name);
    $('#profileForm [name="last_name"]').val(resp.data.last_name);
    $('#profileForm [name="username"]').val(resp.data.username);
    $('#profileUserId').text(resp.data.id);
    $('#profileTariff').text(resp.data.tariff);
    $('#profileBalance').text(resp.data.balance);
  });

  $('#profileForm').on('submit', function (e) {
    e.preventDefault();
    var data = {
      email: this.email.value,
      first_name: this.first_name.value,
      last_name: this.last_name.value,
      username: this.username.value
    };
    Api.request('PUT', '/api/me', data).done(function () {
      Toast.show('Профиль обновлен');
    });
  });
});
