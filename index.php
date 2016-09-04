<!DOCTYPE html>
<html>
<head>
	<title>百度API翻译 - Magento</title>
	<link rel="icon" href="assets/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="assets/favicon.ico"/>
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/styles.css">
</head>
<body>
	
	<center>
		<!-- HEADER -->
		<header>
			<p class="bg-primary">百度API翻译 - Magento</p>
		</header>

		<!-- STEP ONE -->
		<div class="jumbotron first">
	      	<h4>STEP ONE</h4>
	   	</div>

		<blockquote>
		  	<p>请确认在config.php已配置好数据库信息</p>
		  	<p><code>path: [Your_App_Name]/config.php</code></p>
		</blockquote>

		<button id="crDB" class="btn btn-info btn-xs">建立数据库</button>
		
		<!-- STEP TWO -->
		<div class="jumbotron">
	      	<h4>STEP TWO</h4>
	   	</div>

		<blockquote>
			<p>选取填写好的csv文档</p>
			<p>格式请查看根目录下的sample.csv</p>
		  	<p><code>path: [Your_App_Name]/sample.csv</code></p>
		</blockquote>

		<form method="post" enctype="multipart/form-data" id="upload" name="upload">
		  	<div class="form-group">
			    <input type="file" id="csvFile">
			</div>
			<button type="submit" id="subTra" class="btn btn-info btn-xs">上传保存</button>
		</form>

		<!-- STEP THERR -->
		<div class="jumbotron">
	      	<h4>STEP THREE</h4>
	   	</div>

		<blockquote>
		  	<p>调用百度API接口进行翻译</p>
		  	<select class="form-control" name="translate_to">
				<option value="">请选择您需要将英文翻译至哪种语言</option>
			  	<option value="zh">中文</option>
			  	<option value="cht">繁体中文</option>
			  	<option value="jp">日语</option>
			  	<option value="kor">韩语</option>
			  	<option value="fra">法语</option>
			  	<option value="spa">西班牙语</option>
			  	<option value="th">泰语</option>
			  	<option value="ara">阿拉伯语</option>
			  	<option value="ru">俄语</option>
			  	<option value="pt">葡萄牙语</option>
			  	<option value="de">德语</option>
			  	<option value="it">意大利语</option>
			  	<option value="el">希腊语</option>
			  	<option value="nl">荷兰语</option>
			  	<option value="pl">波兰语</option>
			  	<option value="bul">保加利亚语</option>
			  	<option value="est">爱沙尼亚语</option>
			  	<option value="dan">丹麦语</option>
			  	<option value="fin">芬兰语</option>
			  	<option value="cs">捷克语</option>
			  	<option value="rom">罗马尼亚语</option>
			  	<option value="slo">斯洛文尼亚语</option>
			  	<option value="swe">瑞典语</option>
			  	<option value="hu">匈牙利语</option>
			</select>
			<p>该翻译列表最后更新为2016-08-14，可前往百度API开发文档查看更新.</p>
			<p>如有更新,修改以下文件即可</p>
		  	<p><code>path: [Your_App_Name]/index.php</code></p>
		</blockquote>
		<p class="help-block">
			<img class="loading-img" src="assets/loading.gif"/>
		</p>
		<button id="translate" class="btn btn-info btn-xs">翻译</button>

		<!-- STEP FOUR -->
		<div class="jumbotron">
	      	<h4>STEP FOUR</h4>
	   	</div>

		<blockquote>
		  	<p>生成csv翻译文档</p>
		  	<p><code>sample_path: [Your_App_Name]/uploads/20160814203055_translate.csv</code></p>
		</blockquote>

		<button id="download" class="btn btn-info btn-xs">下载csv</button>

		<!-- FOOTER -->
		<footer>
			<p class="bg-primary">Powered by Mark - &lt;mark@zhaomark.com&gt;</p>
		</footer>

	</center>

<script src="//cdn.bootcss.com/jquery/1.12.2/jquery.min.js"></script>
<script>

	$(function(){

		//生成数据库表
		$('#crDB').click(function(){
			$.ajax({
				url:"helper.php",
				data:{action:"genDB"},
				type:"GET",
				success:function(data){
					alert(data);
				},error:function(){
					alert('系统异常');
				}
			});
		});

		//上传文件
		$('#subTra').click(function(){
			$('#upload').submit(function(e) {
				var fd = new FormData();
				var file = $('#csvFile')[0].files[0];
				if(!file){
					alert('请先选择文件！');
					return false;
				}
				fd.append('file',file);
			    $.ajax({
			      	url: 'helper.php?action=saveCSV',
			      	type: 'POST',
			      	data: fd,
			      	cache: false,
			     	processData: false,
			     	contentType: false,
					type: 'POST',
					success:function(data){
						alert(data);
						location.reload();
					}
			    });
			    e.preventDefault();
	  		});
		});

		//调用接口翻译
		$('#translate').click(function(){
			var to = $('select[name="translate_to"]').find('option:selected').val();
			if(to == ''){
				alert('Please Select Language!');
				return false;
			}
		    $('.loading-img').show();
			$.ajax({
				url:"helper.php",
				type: "GET",
				data:{action:"startTrans",to:to},
				success:function(data){
					alert(data);
				},
				complete: function(){
			        $('.loading-img').hide();
			    }
				,error:function(){
					alert('Internal Sever Error!');
				}
			});
		});

		//生成Magento标准语言翻译文件
		$('#download').click(function(){
			$.ajax({
				url:"helper.php",
				data:{action:"outputCSV"},
				type:"GET",
				success:function(data){
					alert(data);
				},error:function(){
					alert('Internal Sever Error!');
				}
			});
		});

	})

</script>
</body>
</html>