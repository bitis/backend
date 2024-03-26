# 身份校验

Bearer Token
示例：
`Authorization: Bearer <Your API key>`

# 响应格式

```json
{
    "code": 0,
    "msg": "ok",
    "data": {}
}
```

# 错误代码

> 0 表示成功 非0表示失败

| CODE | 含义                |
|------|-------------------|
| 0    | 成功                |
| -1   | 当前提交业务处理失败（一般性错误） |
| 403  | 身份校验失败（token过期）   |
