# 诗词参数说明

## 首页请求

1. 方式：get
2. 请求参数：
```
{
  "home": "true"
}
```
3. 响应数据：
```
{
  "code": 0,
  "errorMsg": "",
  "data": {
    "poems": [],
    "authors": []
  }
}
```

## 获取某篇诗词

1. 方式：get
2. 请求参数：
```
{
  "id": 12
}
```
3. 响应数据
```
{
  "code": 0,
  "errorMsg": "",
  "data": {
    "zuozhe": "李白",
    "zhaiyao": "XXXX"
  }
}
```

## 获取作者列表
1. 方式：get
2. 请求参数：
```
{
  "author": "all",
  "p": 1
}
```
3. 响应数据
```
{
  "code": 0,
  "errorMsg": "",
  "data": {
    "total": 4356,
    "data": [
      "李白",
      "杜甫"
    ]
  }
}
```

## 获取诗词列表
1. 方式：get
2. 请求参数：
```
{
  "poem": "all",
  "p": 1
}
```
3. 响应数据
```
{
  "code": 0,
  "errorMsg": "",
  "data": {
    "total": 4356,
    "data": [
      {
        "_id": "1013",
        "mingcheng": "乐府杂曲。鼓吹曲辞。君马黄",
        "zuozhe": "李白",
        "shipin": "0",
        "ticai": "",
        "chaodai": "唐代",
        "guojia": "",
        "fenlei": "",
        "jieduan": "",
        "keben": "",
        "congshu": "#全唐诗#",
        "chuchu": "",
        "zhaiyao": "君马黄，我马白，马色虽不同，人心本无隔。",
        "yuanwen": "君马黄，\r\n我马白，\r\n马色虽不同，\r\n人心本无隔。\r\n共作游冶盘，\r\n双行洛阳陌。\r\n长剑既照曜，\r\n高冠何赩赫。\r\n各有千金裘，\r\n俱为五侯客。\r\n猛虎落陷阱，\r\n壮夫时屈厄。\r\n相知在急难，\r\n独好亦何益。\r\n",
        "voice": "0"
      }
    ]
  }
}
```

## 获取作者诗词列表
1. 方式：get
2. 请求参数
```
{
  "author": "李白",
  "p": 1
}
```
3. 响应数据
```
{
  "code": 0,
  "errorMsg": "",
  "data": {
    "total": 1123,
    "authorInfo": {
      "_id": "2019",
      "xingming": "李白",
      "xingpy": "L",
      "renpin": "0",
      "chenghao": "",
      "jianjie": "",
      ……
    },
    "poems": [
      [
        "_id": "1013",
        "mingcheng": "乐府杂曲。鼓吹曲辞。君马黄",
        "zuozhe": "李白",
        "shipin": "0",
        "ticai": "",
        "chaodai": "唐代",
        "guojia": "",
        "fenlei": "",
        "jieduan": "",
        "keben": "",
        "congshu": "#全唐诗#",
        "chuchu": "",
        "zhaiyao": "君马黄，我马白，马色虽不同，人心本无隔。",
        "yuanwen": "君马黄，\r\n我马白，\r\n马色虽不同，\r\n人心本无隔。\r\n共作游冶盘，\r\n双行洛阳陌。\r\n长剑既照曜，\r\n高冠何赩赫。\r\n各有千金裘，\r\n俱为五侯客。\r\n猛虎落陷阱，\r\n壮夫时屈厄。\r\n相知在急难，\r\n独好亦何益。\r\n",
        "voice": "0"
      ]
    ]
  }
}
```

## 关键字查询——作者、标题、内容

