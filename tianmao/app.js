import util from 'utils/util.js'
App({
   onLaunch: function () {
   this.login();
  },
  login(){
    wx.login({
      success(res){
        util.http('login.php',{
          code: res.code
        }).then((res)=>{
          if (res.status == 1){
            wx.setStorageSync('actived', res.actived)
            wx.setStorageSync('openid', res.openid)
            wx.setStorageSync('skey', res.skey)
          }else{
            wx.showToast({
              title: '网络错误',
              icon:'none'
            })
          }
          
        })
      }
    })
  },
  globalData:{
    userInfo:null
  }
})