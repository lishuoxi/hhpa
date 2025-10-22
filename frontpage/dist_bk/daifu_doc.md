# 对接文档

不管是商户请求本平台，还是本平台回调商户订单状态，其http报文的格式都是  content-type： **application/json,**

返回数据的基本格式:

```javascript
{
    code:0, 				// 0 代表正常, 其它值代表有错误
    message:"返回信息", 	  // 返回的文字信息
    data: {}				// 返回的数据
}
```

**下面对接口进行说明时,返回数据都为data字段的值.**

## 提交订单

**<a id="create">请求类型:</a>** post

**请求地址:** http://api-pp.motuo789.com/daifu/create

**数据格式:** 

名称 | 类型 | 是否必须 | 是否参与签名 | 说明                                  
--- | --- | ---| ---| ---
merchant_id | string | 是 | 是 | 商户号,如: 2024010101                 
out_daifu_id | string | 是 | 是 | 商户订单号,必须要唯一,不能重复提交,如 
amount | double | 是 | 是 | 金额,两位小数, 如: 100.00 
account | string | 否 | 是 | 账号, 如123 456 789 00007 
account_name | string | 否 | 是 | 卡号名称, 如 李三 
bank | string | 否 | 是 | 银行卡开户行, 如是支付宝,就写alipay, 微信就写 wechat 
timestamp | long   | 是 | 是 | 十位utc时间戳,如: 1714599106 
notify_url | string | 是 | 是 | 回调地址 
sign | string | 是 | | 签名 

**请求示例:**

```javascript
{
		merchant_id: 2024010101,
		out_daifu_id: '',
		amount:100.00,
		account: '12345678900001',
         account_name: '李三',
         bank: '工商银行',
		timestamp:1714599106
		notify_url:'',
		sign:''  
}
```

**返回示例DATA:**

```javascript
{
		daifu_id: '',
		out_daifu_id: '',
		amount: 100.00,
         account: '12345678900001',
         account_name: '李三',
         bank: '工商银行',
		status: 1,  // 见附录说明
		sign: ''
}
```



## 查询订单

**<a id="query">请求类型:</a>** post

**请求地址:** http://api-pp.motuo789.com/daifu/query

**数据格式:** 

| 名称           | 类型   | 是否必须 | 是否参与签名 | 说明                         |
| -------------- | ------ | -------- | ------------ | ---------------------------- |
| daifu_id | string | 是       | 是           | 订单号,如: 2024010101        |
| timestamp      | long   | 是       | 是           | 十位utc时间戳,如: 1714599106 |
| sign           | string | 是       |              | 签名                         |

**请求示例:**

```javascript
{
		timestamp: '',
		daifu_id: '',
		sign: ''
}
```

**返回示例DATA:**

```javascript
{
    	 daifu_id: '',
		out_daifu_id: '',
		amount: 100.00,
         account: '12345678900001',
         account_name: '李三',
         bank: '工商银行',
		status: 1,  // 见附录说明
		sign: ''
}
```




## 查询余额

**<a id="query">请求类型:</a>** post

**请求地址:** http://api-pp.motuo789.com/daifu/money_query

**数据格式:** 

| 名称           | 类型   | 是否必须 | 是否参与签名 | 说明                         |
| -------------- | ------ | -------- | ------------ | ---------------------------- |
| merchant_id | string | 是       | 是           | 商户号,如: 2024010101        |
| timestamp      | long   | 是       | 是           | 十位utc时间戳,如: 1714599106 |
| sign           | string | 是       |              | 签名                         |

**请求示例:**

```javascript
{
		timestamp: '',
		merchant_id: '',
		sign: ''
}
```

**返回示例DATA:**

```javascript
{
    	merchant_id: '',
		money: '100.00',
		sign: ''
}
```




## 订单回调

**订单回调数据格式:** 

| 名称               | 类型   | 是否必须 | 是否参与签名 | 说明                         |
| ------------------ | ------ | -------- | ------------ | ---------------------------- |
| daifu_id     | string | 是       | 是           | 订单号                       |
| out_daifu_id | string | 是       | 是           | 商户订单号                   |
| amount             | double | 否       | 是           | 金额,两位小数, 如: 100.00    |
| account_name | string | 否       | 是           | 账户名                       |
| account | string | 否 | 是 | 收款账户 |
| bank               | string | 否       | 是           | 开户行                       |
| timestamp          | long   | 是       | 是           | 十位utc时间戳,如: 1714599106 |
| sign               | string | 是       |              | 签名                         |

**订单回调数据示例:**

订单成功后,会通过notify_url通知用户, 处理完成后,请返回字符串"SUCCESS".

若收不到"SUCCESS", 系统会进行5次尝试或直至收到"SUCCESS"为至.

```javascript
{
		daifu_id: '',	
         out_daifu_id: '',	
		timestamp: 123,
		amount: 100.00,
         account: 123123123,
         account_name: '李三',
         bank: '广东东莞',
		status: 1,  // 见附录说明
		sign: ''
}
```





## 签名算法

**<a id="signature">签名步骤说明:</a>**

1. 取api所需的加密字段（必填 + 可选传参数）组成为字典m:   dict m= [{k1,v1},{k2,v2}.....{kn,vn}]

2. 根据字典m的key ascii排序; 从小到大   dict m= [{k1,v1},{k2,v2}.....{kn,vn}]

3. 获取prestring1 字段, 将已排序的字典m按querystring拼接:  $str = k1=v1&k2=v2&k3=v3&.....kn=vn

4.  加上密钥对secretkey, 得到$str2 =   $str + "&key=密钥"

5. 计算str2的md5 即可得到sign值  sign= md5($str2). 注意为小写

   

**php代码**

```php
    public function sign($data, $secret)
    {
        if (isset($data['sign'])) unset($data['sign']);
        $signStr = '';
        ksort($data);
        foreach ($data as $key => $value) {
            if ($value !== '' && $value !== null) {
                $signStr .= $key . '=' . $value . '&';
            }
        }
        $signStr .= 'key=' . $secret;

        return strtolower(md5($signStr));
    }

```





#### 附录1: 返回状态status代码说明

| 代码 | 说明                         |
| ---- | ---------------------------- |
| 1    | 订单创建成功, 等待反查       |
| 2    | 反查成功,等待支付            |
| 4    | 反查失败, 系统会尝试继续反查 |
| 8    | 订单失败                     |
| 16   | 订单成功                     |

