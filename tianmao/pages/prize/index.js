import util from '../../utils/util.js'
//获取应用实例
var app = getApp()
Page({
  data: {
    showmask:false,
    prizeData:null,
    imgurl: util.imgurl,
    src: util.imgurl + 'saoma.jpg',
    showcode:false,
  },
  onLoad() {
    this.getPrize();
  },
  onclickcopy(){
    //点击复制按钮
    var _this = this;
    wx.setClipboardData({
      data: _this.data.prizeData.code,
      success(){
        wx.showToast({
          title: '复制成功',
          icon:'none'
        })
      },
    })
  },
  getPrize(){
    var openid = wx.getStorageSync('openid');
    if (openid){
      this.getPrizehttp(openid);
    }else{
      this.login();
    }
  },
  getPrizehttp(openid){
    util.http('getAward.php', { openid: openid }).then((res) => {
      console.log(res);
      if (res.result) {
        this.setData({
          prizeData: res.result
        })
      }

    })
  },
  login() {
    var _this = this;
    wx.login({
      success(res) {
        util.http('login.php', {
          code: res.code
        }).then((res) => {
          if (res.status == 1) {
            _this.getPrizehttp(res.openid);
            wx.setStorageSync('actived', res.actived)
            wx.setStorageSync('openid', res.openid)
            wx.setStorageSync('skey', res.skey)
          } else {
            wx.showToast({
              title: '网络错误',
              icon: 'none'
            })
          }
        })
      }
    })
  },
  saveimage(n){
    console.log(n);
    console.log(this.data.src);
    var _this = this;
    wx.getImageInfo({
      src: _this.data.src,
      success(res) {
        wx.saveImageToPhotosAlbum({
          filePath: res.path,
          success(res) { 
            _this.hidemask();
          },
          fail() {
            wx.showToast({
              title: '保存失败',
              icon:'none',
            })
          },
        })  
      }
    })
  },
  getPhoneNumber(res){
    console.log(res);
  },
  showmask(){
    this.setData({
      showmask: true,
    });
  },
  hidemask(){
    this.setData({
      showmask:false,
    });
  },
  showactionsheet(){
    var _this = this;
    wx.showActionSheet({
      itemList: ['保存图片'],
      success(res) {
        if (res.tapIndex == 0){
          _this.shouquanSave();
        }
      },
    })
  },
  shouquanSave(){
    var _this = this;
    wx.getSetting({
      success(res) {
        if (res.authSetting['scope.writePhotosAlbum']) {
          _this.saveimage(1);
        } else {
          wx.authorize({
            scope: "scope.writePhotosAlbum",
            success(){
              _this.saveimage(2);
            },
          })
        }
      }
    })
  },
  gotoChou(){
    wx.reLaunch({
      url: '/pages/index/index',
    })
  },
  onclickshowcode(){
    this.setData({
      showcode: !this.data.showcode,
    });
  },
})
