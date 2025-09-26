# 对接文档

[不](#query)管是商户请求本平台，还是本平台回调商户订单状态，其http报文的格式都是  content-type： **application/json,**

返回数据的基本格式:

```javascript
{
    code:0, 				// 0 代表正常, 其它值代表有错误
    message:"返回信息", 	  // 返回的文字信息
    data: {}				// 返回的数据
}
```



## 提交订单

**[请](#create)求类型:** post

**请求地址:** http://www.henghaochi.com/trade/create

**数据格式:** 

名称 | 类型 | 是否必须 | 是否参与签名 | 说明                                  
--- | --- | ---| ---| ---
merchant_id | string | 是 | 是 | 商户号,如: 2024010101                 
merchant_trade_id | string | 是 | 是 | 商户订单号,必须要唯一,不能重复提交,如 
amount | double | 是 | 是 | 金额,两位小数, 如: 100.00 
pay_type | string | 是 | 是 | 通道编码, 如: alipay_h5 
timestamp | long   | 是 | 是 | 十位utc时间戳,如: 1714599106 
notify_url | string | 是 | 是 | 回调地址 
return_url | string | 否 | 是 | 同步回调地址 
sign | string | 是 | | 签名 

**请求示例:**

```javascript
{
	merchant_id
}
```

**返回示例:**

```javascript
{

}
```



## 查询订单

**[请](#query)求类型:** post

**请求地址:** http://www.henghaochi.com/trade/create

**数据格式:** 

| 名称              | 类型   | 是否必须 | 是否参与签名 | 说明                                  |
| ----------------- | ------ | -------- | ------------ | ------------------------------------- |
| merchant_id       | string | 是       | 是           | 商户号,如: 2024010101                 |
| merchant_trade_id | string | 是       | 是           | 商户订单号,必须要唯一,不能重复提交,如 |
| amount            | double | 是       | 是           | 金额,两位小数, 如: 100.00             |
| pay_type          | string | 是       | 是           | 通道编码, 如: alipay_h5               |
| timestamp         | long   | 是       | 是           | 十位utc时间戳,如: 1714599106          |
| notify_url        | string | 是       | 是           | 回调地址                              |
| return_url        | string | 否       | 是           | 同步回调地址                          |
| sign              | string | 是       |              | 签名                                  |

**请求示例:**

```javascript
{
	merchant_id
}
```

**返回示例:**

```javascript
{

}
```





## 订单回调

**订单回调数据格式:** 

| 名称              | 类型   | 是否必须 | 是否参与签名 | 说明                                  |
| ----------------- | ------ | -------- | ------------ | ------------------------------------- |
| merchant_id       | string | 是       | 是           | 商户号,如: 2024010101                 |
| merchant_trade_id | string | 是       | 是           | 商户订单号,必须要唯一,不能重复提交,如 |
| amount            | double | 是       | 是           | 金额,两位小数, 如: 100.00             |
| pay_type          | string | 是       | 是           | 通道编码, 如: alipay_h5               |
| timestamp         | long   | 是       | 是           | 十位utc时间戳,如: 1714599106          |
| notify_url        | string | 是       | 是           | 回调地址                              |
| return_url        | string | 否       | 是           | 同步回调地址                          |
| sign              | string | 是       |              | 签名                                  |

**订单回调数据示例:**

```javascript
{
	merchant_id
}
```

**返回示例:**

```javascript
{

}
```





## 签名算法

**[签](#signature)名步骤说明:**

1. 所有值排序
2. 城
3. 城苦
4. 阿斯蒂芬
5. 堪
6. 阿斯蒂芬

**示例**



**php代码**

```php
$i=0;
```

