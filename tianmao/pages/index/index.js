import util from '../../utils/util.js'
/*index.js 
奖品顺序是顺时针
 1级   4个  每个概率  20%   （80%）  
 2级  2个  每个 5% （10%） 
 3级 2个 5% (10%)
 1,3,5,7 为1级奖品
 2,6为2级奖品
 4,8为2级奖品
 [20,5,20,5,20,5,20,5]
 */
//获取应用实例
var app = getApp()
Page({
  data: {
    openid:'',
    prizeSuccess:'',
    imgurl: util.imgurl,
    circleList: [],//圆点数组
    awardList: [],//奖品数组
    colorCircleFirst: '#fff',//圆点颜色1
    colorCircleSecond: '#fe7a22',//圆点颜色2
    colorAwardDefault: '#fff',//奖品默认颜色
    colorAwardSelect: '#fedc7a',//奖品选中颜色
    indexSelect: -1,//被选中的奖品index
    isRunning: true,//是否正在抽奖
    level1: [0, 1, 2, 3, 4],
    level2: 5,
    level3: 6,
    level4: 7,
    prize:{},
    showmask:false,
    imageAward: [],//奖品图片数组
  },
  onLoad: function () {
    this.getPrizeList();//获取奖品列表
    this.initCirclePos();//初始化圆点位置
    this.circleshanshuo();//圆点闪烁
    
  },
  /*7-1%  6-2%  5-3%  0-4:*/
  randomIndex() {
    var ran = Math.ceil(Math.random() * 100);
    if (ran <= 94) {  //通过概率判断选取数字
      var needNum = Math.floor(Math.random() * 5);
      if (needNum >= 0) {
        return this.data.level1[needNum];
      } else {
        return this.data.level1[0];
      }
    } else if (94 < ran <= 97) {
       return this.data.level2;
    } else if (97 < ran <= 99) {
      return this.data.level3;
    } else if (ran == 100) {
      return this.data.level4;
    }
  },
  onclickgetuserinfo() {
    //去领奖
    wx.reLaunch({
      url: '/pages/prize/index',
    })
  },
  getPrizeList() {
    var prizeList = wx.getStorageSync('prizelist');
    if (prizeList) {
      prizeList = JSON.parse(prizeList);
      if (prizeList && prizeList.length > 0){
        this.setData({
          imageAward: prizeList,
          isRunning: false
        });
        this.setPrizeitem();//奖品item设置
      }
    } else {
      util.http('getPrizeLists.php').then((res) => {
        if (res.result && res.result.length > 0) {
          var result = res.result
          this.setData({
            imageAward: result,
            isRunning: false
          });
          this.setPrizeitem();//奖品item设置
          wx.setStorage({
            key: 'prizelist',
            data: JSON.stringify(result),
          })
        }
      });
    }
  },
  //开始抽奖
  startGame: function () {
   /*
    for(var i=0;i<500;i++){
      var nn = this.randomIndex();
      console.log(nn, this.data.imageAward[nn].title);
    }
    return;
    */
    var _this = this;
    wx.getStorage({
      key: 'actived',
      success: function(res) {
        if(res.data){//true 表示已经参与过
          wx.showModal({
            title: '提示',
            showCancel: false,
            content: '您已经参与过此活动',
          })
        }else{
          if (_this.data.isRunning) return
          _this.setData({
            isRunning: true
          })
          var finalIndex = _this.randomIndex() || 2;
        
          _this.addLuckyData(finalIndex);//添加中奖信息到数据库
          _this.rotateFn(finalIndex);
        }
      },
    })
   
  },
  addLuckyData(finalIndex){
    var _this = this;
    //openid  skey  nickName avatarUrl  phone   prize  
    var userinfo = wx.getStorageSync('userinfo');
    var awradObj = this.data.imageAward[finalIndex];
    if (userinfo){
      userinfo = userinfo && JSON.parse(userinfo);
      var result = {
        openid: this.data.openid,
        nickName: userinfo.nickName,
        avatarUrl: userinfo.avatarUrl,
        prize: awradObj.id,
      };
      this.addluckyhttp(result, awradObj);
    }else{
      wx.getUserInfo({
        success(res) {
          console.log(res.userInfo)
          userinfo = res.userInfo;
          var result = {
            openid: _this.data.openid,
            nickName: userinfo.nickName,
            avatarUrl: userinfo.avatarUrl,
            prize: awradObj.id,
          };
          _this.addluckyhttp(result, awradObj);
        }
      })
    }
  },
  addluckyhttp(result, awradObj){
    console.log(result);
    util.http('addLucky.php', result).then((res) => {
      console.log(res);
      if (res.status == 0) {
        awradObj = { src: 'error.png' };
      } else if (res.status == 2) {
        awradObj = { src: 'nochance.png' };
      } else if (res.status == 1){
        wx.setStorageSync('actived',true)
      }
      this.setData({
        prize: awradObj,
        prizeSuccess: res.status
      })
    })
  },
  rotateFn(finalIndex){
    var indexSelect = 0;
    var i = 0;
    var _this = this;
    var timer = setInterval(function () {
      if (indexSelect >= 8) {
        indexSelect = 0;
      } else {
        indexSelect++;
      }
      i += 100;
      if (i > 3000) {
        //去除循环
          clearInterval(timer)
          //获奖提示
          if (_this.data.prizeSuccess == 0){
            _this.setData({
              isRunning: false,
            });
          }
          _this.setData({
            showmask: true,
          });
      }
      _this.setData({
        indexSelect: indexSelect
      })
    }, (100 + i))
  },
  getuserinfo(e) {
    var _this = this;
    if (e.detail.userInfo) {
      wx.getStorage({
        key: 'openid',
        success: function (res) { 
          _this.setData({
            openid:res.data
          });
          _this.startGame();
        },
      })
    }
    wx.setStorage({
      key: 'userinfo',
      data: JSON.stringify(e.detail.userInfo),
    })
  },
  setPrizeitem(){
    var _this = this;
    //奖品item设置
    var awardList = [];
    //间距,怎么顺眼怎么设置吧.
    var topAward = 15;
    var leftAward = 15;
    for (var j = 0; j < 8; j++) {
      if (j == 0) {
        topAward = 15;
        leftAward = 15;
      } else if (j < 3) {
        topAward = topAward;
        //166.6666是宽.15是间距.下同
        leftAward = leftAward + 166.6666 + 22;
      } else if (j < 5) {
        leftAward = leftAward;
        //150是高,15是间距,下同
        topAward = topAward + 165 + 22;
      } else if (j < 7) {
        leftAward = leftAward - 165 - 22;
        topAward = topAward;
      } else if (j < 8) {
        leftAward = leftAward;
        topAward = topAward - 165 - 22;
      }
      var imageAward = this.data.imageAward[j];
      awardList.push({ topAward: topAward, leftAward: leftAward, imageAward: imageAward });
    }
    this.setData({
      awardList: awardList
    })
  },
  circleshanshuo(){
    var _this = this;
    //圆点闪烁
    setInterval(function () {
      if (_this.data.colorCircleFirst == '#fff') {
        _this.setData({
          colorCircleFirst: '#fe7a22',
          colorCircleSecond: '#fff',
        })
      } else {
        _this.setData({
          colorCircleFirst: '#fff',
          colorCircleSecond: '#fe7a22',
        })
      }
    }, 500)//设置圆点闪烁的效果
  },
  initCirclePos(){
    //圆点设置
    var leftCircle = 7.5;
    var topCircle = 7.5;
    var circleList = [];
    for (var i = 0; i < 24; i++) {
      if (i == 0) {
        topCircle = 15;
        leftCircle = 25;
      } else if (i < 6) {
        topCircle = 7.5;
        leftCircle = leftCircle + 102.5;
      } else if (i == 6) {
        topCircle = 15
        leftCircle = 610;
      } else if (i < 12) {
        topCircle = topCircle + 94;
        leftCircle = 623;
      } else if (i == 12) {
        topCircle = 615;
        leftCircle = 610;
      } else if (i < 18) {
        topCircle = 622;
        leftCircle = leftCircle - 102.5;
      } else if (i == 18) {
        topCircle = 565;
        leftCircle = 10;
      } else if (i < 24) {
        topCircle = topCircle - 94;
        leftCircle = 7.5;
      } else {
        return
      }
      circleList.push({ topCircle: topCircle, leftCircle: leftCircle });
    }
    this.setData({
      circleList: circleList
    })
  },
 
  closemask(){
    this.setData({
      showmask:false,
    });
  },
  onShow(){
    wx.showShareMenu({
      withShareTicket: true,
    })
  },
  onShareAppMessage(res){
      let that = this;
      return {
        title: '100%中奖,礼品免费送，不要白不要',
        path: '/pages/index/index',
        imageUrl: that.data.imgurl + 'link1.png',
      }
  },
})

