var Api = {
  request: function (method, url, data) {
    return $.ajax({
      method: method,
      url: url,
      data: data ? JSON.stringify(data) : null,
      contentType: 'application/json',
      dataType: 'json'
    });
  },
  get: function (url, data) {
    return $.ajax({
      method: 'GET',
      url: url,
      data: data,
      dataType: 'json'
    });
  }
};
