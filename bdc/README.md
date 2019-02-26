# 复习单词参数说明

## 章节单词列表
1. 方式：get，
2. 请求参数：
```
{
  "c": 1
}
```
3. 响应数据：
```
{
  "code": 0,
  "errorMsg": "",
  "data": [
    {
      "order": "1",
      "word": "the",
      "phonetic": "ðә",
      "explain": "art. 那"
    }
    ...
  ]
}
```

## 搜索结果
1. 方式：get，
2. 请求参数：
```
{
  "s": "ab"
}
```
3. 响应数据：
```
{
  "code": 0,
  "errorMsg": "",
  "data": [
    {
      "order": "11207",
      "word": "aback",
      "phonetic": "ә'bæk",
      "explain": "ad. 向后, 朝后, 突然, 船顶风地"
    }
    ...
  ]
}
```