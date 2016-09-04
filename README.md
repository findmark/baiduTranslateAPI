## 百度翻译API开源

#### 基本信息
- Author: Mark
- Email: sylar.developer@gmail.com
- Date: 2016/08/15
- Wechat: Golden_Park

#### 接口信息

- [申请百度翻译API接口](http://api.fanyi.baidu.com/api/trans/product/index '百度翻译API首页')
- [百度翻译开发文档](http://api.fanyi.baidu.com/api/trans/product/apidoc '接口文档')

#### 开发环境

- PHP: 5.6.10
- Mysql: 4.4.10

#### 应用场景
	
	1. 该开源插件适用于 Magento 1.X 版本语言翻译，其他场景请自行研究修改
	2. 插件配合 Mysql 操作

#### 插件使用方式

	1. 用浏览器打开应用根目录的index.php文件
	2. 按照页面提示执行即可

#### 原理

	1. 先把待翻译数据写入数据库
	2. 再从数据库调出数据通过百度API翻译后保存入库
	3. 最后导出CSV文档到应用目录下的uploads文件夹
	4. 文件名：[时间_translate.csv]

#### 备注

	1. 该Module原打算开发成专门翻译MAGENTO语言包,因此导出的csv格式可能不是你所需要的
	2. 如果嫌麻烦，可以通过修改代码取消数据库的操作，直接生成翻译文件
	3. 由于开发环境不同，代码需要修改以实现各自的实际需求
	4. 因原意用于MAGENTO翻译，因此该Module翻译原文本固定为英文,有需要的用户可自行更改Module.
	5. 使用中如有问题，可先检查文件路径是否正确。其他问题欢迎提交issue