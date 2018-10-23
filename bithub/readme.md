- 接口说明


- 同步服务器时间:
    - 接口地址：http://localhost/api/request/requesttime
    - 请求方式：GET
    - 返回:
        <pre><code>
        {
           "success": true,
           "data": {
               "datetime": 1531965766
           }
        }
        </code></pre>
        
- 请求图形验证码所需Token:
    - 接口地址：http://localhost/api/user/usertoken
    - 请求方式：GET
    - 返回:
        <pre><code>
        {
           "success": true,
           "data": {
               "request_token": "0b03d3b8a11b11e8b3990242ac140004"
           }
        }
        </code></pre>

- 图形验证码:
    - 接口地址：http://localhost/api/user/captcha
    - 请求方式：GET
    - 提交变量：
        <pre><code>
        {
           "request_token": "0b03d3b8a11b11e8b3990242ac140004"
        }
        </code></pre>
    - 返回:
        <pre><code>
        {
           "success": true,
           "data": null
        }
        </code></pre>

- 注册接口

    - 接口地址：http://localhost/api/user/signup
    - 提交变量：
        <pre><code>
        {
            "username": "adminrest",
            "password": "p6ssword",
            "verifyimg": "6rbg1",
            "request_token": "0b03d3b8a11b11e8b3990242ac140004"
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
           "success": true,
           "data": null
        }
        </code></pre>

- 登录接口

    - 接口地址：http://localhost/api/user/login
    - 提交变量：
        <pre><code>
        {
            "username": "adminrest",
            "password": "p6ssword",
            "verifyimg": "6rbg1",
            "request_token": "0b03d3b8a11b11e8b3990242ac140004"
        }
        </code></pre>
        
    - 成功返回：
        <pre><code>
        {
           "success": true,
           "data": {
               "user_id": 1,
               "auth_key": "520cd01a8afb11e8b1030242ac120003",
               "auth_secret": "520e956c8afb11e8a74a0242ac120003"
           }
        }
        </code></pre>

- 密码修改

    - 接口地址：http://localhost/api/user/setpassword
    - 提交变量：
        <pre><code>
        {
            "auth_key": "d8978f77cdd5764c5744182e3b2e29f9",
            "new_password": "123456",
            "old_password": "p6ssword",
            "request_date":  1507436564,
            "auth_sign":"3a63464e48936ea8ec3208399c475cdb",
            "request_token":"0b03d3b8a11b11e8b3990242ac140004",
            "verifyimg": "6rbg1",
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
           "success": true,
           "data": null
        }
        </code></pre>
        
- 获取绑定邮箱的激活码
    - 接口地址：http://localhost/api/user/getcode
    - 提交变量：
        <pre><code>
        {
            "user_id": 12,
            "email": "704910054@qq.com"
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
            "success": true,
            "data": {
                "token": "bfd399"
            }
        }
        </code></pre>
- 绑定邮箱
    - 接口地址：http://localhost/api/user/bindemail
    - 提交变量：
        <pre><code>
        {
            "user_id": 12,
            "email": "704910054@qq.com",
            "token": "bfd399",
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
            "success": true,
            "data": null
        }
        </code></pre>
        
- 获取绑定手机号码的激活码
    - 接口地址：http://localhost/api/user/getcode
    - 提交变量：
        <pre><code>
        {
            "user_id": 12,
            "telephone": "18668171320"
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
            "success": true,
            "data": {
                "token": "bfd399"
            }
        }
        </code></pre>
- 绑定手机号
    - 接口地址：http://localhost/api/user/bindtelephone
    - 提交变量：
        <pre><code>
        {
            "user_id": 12,
            "telephone": "18668171320",
            "token": "bfd399",
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
            "success": true,
            "data": null
        }
        </code></pre>

- 用户信息、资产以及邮箱、手机号绑定状态信息
    - 接口地址：http://localhost/api/user/userinfo
    - 提交变量：
        <pre><code>
        {
           "auth_key": "520cd01a8afb11e8b1030242ac120003"
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
            "success": true,
            "data": {
                "username": "小仙女",
                "login_ip": "172.18.0.1",
                "login_date": "2018-08-09 10:50:17",
                "tel_status": 1,
                "email_status": 0,
                "coin": 0,
                "cash": 0
            }
        }
        </code></pre>
        
## 设备管理

- 绑定钱包地址：
    - 接口地址：http://localhost/api/device/setaddress 
    - 提交变量：
        <pre><code>
        {
            "mac": "F82BC800F001",
            "sn": "MJ54YMEZ",
            "address":"1Dhg2qJCFZyXL2ysRsiDgr4DYxLmEwFKJh"
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
           "success": true,
           "data": null
        }
        </code></pre>
        
- 获取钱包地址：
    - 接口地址：http://localhost/api/device/getaddress 
    - 提交变量：
        <pre><code>
        {
            "mac": "F82BC800F001"
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
           "success": true,
           "data": {
               "address": "1Dhg2qJCFZyXL2ysRsiDgr4DYxLmEwFKJh"
           }
        }
        </code></pre>

## 钱包

- 获取收款码地址：
    - 接口地址：http://localhost/api/wallet/qrcode 
    - 提交变量：
        <pre><code>
        {
            "auth_key": "520cd01a8afb11e8b1030242ac120003"
        }
        </code></pre>
    - 成功返回：
        <pre><code>
    {
           "success": true,
           "data": {
               "code_url": "http://localhost/qrcodes/620520a871be7fe508ad3a3a050ed801.png"
           }
    }
        </code></pre>
        
- 转账接口：
    - 接口地址：http://localhost/api/wallet/pay 
    - 提交变量：
        <pre><code>
        {
            "pay_user_id": 13,
            "receipt_user_id": 16,
            "price": 5,
        }
        </code></pre>
    - 成功返回：
        <pre><code>
    {
          "success": true,
          "data": null
    }
        </code></pre>
        
- 获取用户欧币交易记录：
    - 接口地址：http://localhost/api/wallet/traderecord 
    - 提交变量：
        <pre><code>
        {
            "user_id": 32
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
               "success": true,
               "data": [
                   {
                       "id": 13,
                       "pay_user_id": 33,
                       "receipt_user_id": 32,
                       "price": "+0",
                       "create_datetime": "2018-08-08 13:57:12",
                       "pay_username": "安小然",
                       "receipt_username": "lalala"
                   },
                   {
                       "id": 12,
                       "pay_user_id": 32,
                       "receipt_user_id": 33,
                       "price": "-1",
                       "create_datetime": "2018-08-08 11:14:08",
                       "pay_username": "lalala",
                       "receipt_username": "安小然"
                   }
               ]
        }
        </code></pre>

## 现金
- 银行账号管理：

    - 接口地址：http://localhost/api/cash/bankcardmanage 
    - 提交变量：
        <pre><code>
        {
            "type": "add" ["delete","edit","show"],
            "card_number": "123456545156425255",
            "user_id": "32",
            "card_id": "1",[null]
        }
        </code></pre>
    - 成功返回：
        <pre><code>
    {
            "success": true,
            "data": true
    }
        </code></pre>
    
- 获取用户现金充值提现交易记录：

    - 接口地址：http://localhost/api/cash/cashtraderecord 
    - 提交变量：
        <pre><code>
        {
            "user_id": 32
        }
        </code></pre>
    - 成功返回：
        <pre><code>
        {
            "success": true,
            "data": [
                {
                    "id": 2,
                    "order_id": "DD201808141552248827",
                    "trade_type": 0,   【0:充值;1:提现】
                    "ordercash": "100.00",
                    "bank_card": "123456789",
                    "mark": "null",
                    "status": 0,  【订单状态{0:待审核1:审核成功,2:审核失败,3:操作异常,4:正在处理}】
                    "created_at": "2018-08-14 15:52:24"
                }
            ]
        }
        </code></pre>

                
## 资讯管理
- 获取行情信息列表：
    - 接口地址：http://localhost/api/information/main
    - 提交方式：get 
    - 成功返回：
        <pre><code>
        {
            "success": true,
            "data": [
                {
                    "name": "BTC",
                    "price": "€7648.39",
                    "float_rate": "3.30%"
                },
                {
                    "name": "ETH",
                    "price": "€466.04",
                    "float_rate": "0.59%"
                },
                {
                    "name": "ETC",
                    "price": "€16.51",
                    "float_rate": "1.54%"
                },
                {
                    "name": "BCH",
                    "price": "€818.61",
                    "float_rate": "3.62%"
                },
                {
                    "name": "LTC",
                    "price": "€85.19",
                    "float_rate": "2.81%"
                }
            ]
        }
        </code></pre>
        
        
- 获取资讯列表：

    - 接口地址：http://localhost/api/information/news 
    - 请求方式：get 
    - 提交变量：(默认不传参，滚动加载时传入当前页最后一条资讯的id)
        <pre><code>
        {
            "number": 5
        }
        </code></pre>
    - 成功返回：
        <pre><code>
      {
          "success": true,
          "data": [
              {
                  "id": 6,
                  "title": "【区块链】",
                  "img_url": "//www.baidu.com/img/bd_logo1.png",
                  "details": "如果说蒸汽机释放了人们的生产力，电力解决了人们基本的生活需求，互联网彻底改变了信息传递的方式，那么区块链作为构造信任的机器，可能彻底改变人类社会价值传递的方式。"
              },
              {
                  "id": 7,
                  "title": "【区块链】",
                  "img_url": "//www.baidu.com/img/bd_logo1.png",
                  "details": "如果说蒸汽机释放了人们的生产力，电力解决了人们基本的生活需求，互联网彻底改变了信息传递的方式，那么区块链作为构造信任的机器，可能彻底改变人类社会价值传递的方式。"
              }
          ]
      }
        </code></pre>   
             
- 获取资讯详情：
    - 接口地址：http://localhost/api/information/detail 
    - 请求方式：get 
    - 提交变量：
        <pre><code>
        {
            "id": 6
        }
        </code></pre>
    - 成功返回：
        <pre><code>
       {
             "success": true,
             "data": [
                 {
                     "title": "【区块链】",
                     "details": "如果说蒸汽机释放了人们的生产力，电力解决了人们基本的生活需求，互联网彻底改变了信息传递的方式"
                 }
             ]
       }
        </code></pre>
        
- 获取网站公告信息：
    - 接口地址：http://localhost/api/information/announcement 
    - 请求方式：get 
    
    - 成功返回：
        <pre><code>
        {
           "success": true,
           "data": [
               {
                   "message": "亲爱的甲森友，拉和宽松的卡和尚卡号打开了",
                   "publish_time": "2018-08-15 17:53:37"
               }
           ]
        }
        </code></pre>
        
## 备注
- 签名说明
    - 将POST参数组成一个map。然后把key按照字典序排序。
    - 将参数的key和value拼接成一个字符串之后再连接上加密的密钥组成待签名字符串。
    - 取待签名字符串的md5值作为签名。
    - 如加密迷药是888，提交参数有a,b,c三个参数。三个参数的值分别是1，2，3。那么组成的map值为：
{a:1,b:2,c:3} 这个就是按照字典序排列好的参数。
然后按照key和value与密钥拼接成待签名字符串。a1b2c3888。
签名=md5("a1b2c3888")

        <pre><code>
        a:1
        b:2
        c:3
        auth_sign:aaaaaaa
        </code></pre>

- 请求时间：request_date 环境初始化的时候取服务端的时间戳。本地的时间差。+10 -12。
120秒区间。

- 所有接口错误返回模板

    <pre><code>
    {
        "success": false,
        "data": {
            "code": "R100003",
            "msg": "请求超时"
        }
    }
    </code></pre>
