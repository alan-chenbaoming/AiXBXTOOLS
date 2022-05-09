<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <!-- import CSS -->
<!--  <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">-->
    <link href="https://cdn.bootcdn.net/ajax/libs/element-ui/2.15.7/theme-chalk/index.css" rel="stylesheet">
  <link href="{{ URL::asset('/CAboutAutoCode/prism.css') }}" rel="stylesheet" />
  <link rel="icon" href="{{ URL::asset('/CAboutAutoCode/auto.jpeg') }}">
</head>
<body>
  <div id="app">

    <el-tabs type="border-card">
        <el-tab-pane label="表格文档TOElement页面"><pre><code class="line-numbers language-php">{{$data["detail"]}}</code></pre>
        </el-tab-pane>
        <el-tab-pane label="表格文档TO驼峰C#(自动调整首字母大小写)"><pre><code class="line-numbers language-php">{{$data["Base_field"]}}</code></pre>
        </el-tab-pane>
    </el-tabs>
  </div>
<!--  <script src="https://unpkg.com/vue/dist/vue.js"></script>-->
<!--  <script src="https://unpkg.com/element-ui/lib/index.js"></script>-->
<!--  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>-->

  <script src="https://cdn.bootcdn.net/ajax/libs/vue/2.6.8/vue.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/element-ui/2.15.7/index.js"></script>
  <script src="https://cdn.bootcdn.net/ajax/libs/axios/0.26.1/axios.js"></script>
  <script src="{{ URL::asset('/CAboutAutoCode/prism.js') }}"></script>

  <script>
    new Vue({
      el: '#app',
      data: function() {
        return {
          data: '',
          // a:"echo 1",
            text:'',
          table:'',
          tables:[],
		  prefix:'xzh_',
		  prefixs:[
			  'xzh_',
			  'ng_',
			  'ds_'
		  ],
		  table_keywords:'',
		   }
      },
      created(){
        // this.a = '$id = Input::get("id","");//'
        // var o = document.getElementById('js1');
        // o.setAttribute('src', 'prism.js');
        //this.getData();
        //this.getTables();
      },
	  updated(){
		  console.warn('DOM刷新，执行updated操作')
		  Prism.highlightAll();//用来再次执行prism，高亮效果
	  },
	  watch:{
		 //  data(){
			// console.warn('发现数据变化，进行高亮操作')
			// Prism.highlightAll();//用来再次执行prism，高亮效果
		 //  }
	  },
      methods:{
        getData(){
          url = 'http://127.0.0.1:8000/getExcelToData';
        //   if(this.table){
        //     url += '?table='+this.table
        //   }
          axios.get(url)
                  .then(res => {
                      console.log(res.data)
                      this.data = res.data
                })

        },
        getTables(query){
		  console.log('prefix',this.prefix,'table_keywords',query)
          url = 'http://localhost/demo/autoCode/tables.php?prefix='+this.prefix;
		  if(query){
			  url +='&table='+query
		  }
          axios.get(url)
                  .then(res => {
                      console.log(res.data)
                      this.tables = res.data
                })
        }
      }
    })
  </script>
</body>
</html>
