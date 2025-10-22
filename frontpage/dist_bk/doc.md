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

**请求地址:** http://api_h.mqipay.com/trade/create

**数据格式:** 

名称 | 类型 | 是否必须 | 是否参与签名 | 说明                                  
--- | --- | ---| ---| ---
merchant_id | string | 是 | 是 | 商户号,如: 2024010101                 
out_trade_id | string | 是 | 是 | 商户订单号,必须要唯一,不能重复提交,如 
amount | double | 是 | 是 | 金额,两位小数, 如: 100.00 
pay_type | string | 是 | 是 | 通道编码, 如: alipay_h5 
timestamp | long   | 是 | 是 | 十位utc时间戳,如: 1714599106 
notify_url | string | 是 | 是 | 回调地址 
return_url | string | 否 | 是 | 同步回调地址 
sign | string | 是 | | 签名 

**请求示例:**

```javascript
{
		merchant_id: 2024010101,
		out_trade_id: '',
		amount:100.00,
		pay_type: 'alipay_h5',
		timestamp:1714599106
		notify_url:'',
		return_url:'',
		sign:''  
}
```

**返回示例DATA:**

```javascript
{
		trade_id: '',
		out_trade_id: '',
		amount: 100.00,
		pay_url: '',
		sign: ''
}
```



## 查询订单

**<a id="query">请求类型:</a>** post

**请求地址:** http://api_h.mqipay.com/trade/query

**数据格式:** 

| 名称      | 类型   | 是否必须 | 是否参与签名 | 说明                         |
| --------- | ------ | -------- | ------------ | ---------------------------- |
| merchant_id| string | 是       | 是           | 商户号,如: 2024010101        |
| out_trade_id  | string | 是       | 是           | 商户订单号,如: 2024010101        |
| timestamp | long   | 是       | 是           | 十位utc时间戳,如: 1714599106 |
| sign      | string | 是       |              | 签名                         |

**请求示例:**

```javascript
{
		merchant_id: '',
		out_trade_id: '',
		timestamp: '',
		sign: ''
}
```

**返回示例DATA:**

```javascript
{
		status: '', // 支付成功,支付失败,等待支付, 共3种状态.
		trade_id: '', // 订单号,支付失败,等待支付, 共3种状态.
		out_trade_id: '', // 商户订单号
		amount: '', // 金额
		sign: ''
}
```


## 订单回调

**订单回调数据格式:** 

| 名称              | 类型   | 是否必须 | 是否参与签名 | 说明                                  |
| ----------------- | ------ | -------- | ------------ | ------------------------------------- |
| merchant_id       | string | 是       | 是           | 商户号,如: 2024010101                 |
| out_trade_id | string | 是       | 是           | 商户订单号,必须要唯一,不能重复提交,如 |
| amount            | double | 是       | 是           | 金额,两位小数, 如: 100.00             |
| pay_type          | string | 是       | 是           | 通道编码, 如: alipay_h5               |
| timestamp         | long   | 是       | 是           | 十位utc时间戳,如: 1714599106          |
| notify_url        | string | 是       | 是           | 回调地址                              |
| return_url        | string | 否       | 是           | 同步回调地址                          |
| sign              | string | 是       |              | 签名                                  |

**订单回调数据示例:**

订单成功后,会通过notify_url通知用户, 处理完成后,请返回字符串"SUCCESS".

若收不到"SUCCESS", 系统会进行5次尝试或直至收到"SUCCESS"为至.

```javascript
{
		trade_id: '',	
		timestamp: 123,
		amount: 100.00,
		status: '支付成功', // 支付成功
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

