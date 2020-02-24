function formatTime(date) {
  var year = date.getFullYear()
  var month = date.getMonth() + 1
  var day = date.getDate()

  var hour = date.getHours()
  var minute = date.getMinutes()
  var second = date.getSeconds()


  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

function formatNumber(n) {
  n = n.toString()
  return n[1] ? n : '0' + n
}
function http(url, data = '', method){
  if (!method) {
    if (data) {
      method = 'POST';
    } else {
      method = 'GET';
    }
  }
  return new Promise((resolve, reject) => {
    wx.request({//向服务器发送请求
      url: this.baseurl + url, //请求地址
      data: data, //传递给后台的参数
      method: method, //请求方法，默认是GET
      dataType: 'json', //返回的数据类型，默认是json格式
      success: res => {
        resolve(res.data);
      },
    })
  })
}
module.exports = {
  formatTime: formatTime,
  baseurl: 'https://www.liubai.shop/lucky/api/',
  // baseurl: 'http://localhost/lucky/api/',
  imgurl:'https://www.liubai.shop/lucky/',
  http: http
}
