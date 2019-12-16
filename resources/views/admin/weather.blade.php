@extends('layouts.admin')
@section('content')
<meta name="csrf-token" content="{{csrf_token()}}">
  <div class="form-group">
  <h2><center><b>一周气温展示</b></center></h2>
  城市天气：<input type="text" name="city">
  <input type="button" id="button" class='search btn btn-info' value='搜索'>
<!-- 天气图标容器 -->
        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<!-- 天气图标 插件 -->
        <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
        <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
        <script src="/admin/js/jquery.min.js"></script>

<script>
        $.ajaxSetup({ 
            headers: { 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                }
            });
            $.ajax({
            url:"weather_do",
            data:{city:"北京"},
            dataType:"json",
            success:function(res){
                //展示天气图标
                weather(res.result);
            }
        })
    $('#button').on('click',function(){
        // alert(1);
        // 城市名
        var city = $('[name="city"]').val();
        // console.log(city);
        if (city == '') {
            alert('请填写城市名');
            return;
        }
        // 正则 校验 只能是汉字和拼音
        var reg = /^[a-zA-Z]+$|^[\u4e00-\u9fa5]+$/;
        var res = reg.test(city);
        if (!res) {
            alert('城市名只能填写汉字或者拼音');
            return;
        }
        $.ajax({
            url:"weather_do",
            data:{city:city},
            dataType:"json",
            success:function(res){
                //展示天气图标
                weather(res.result);       
            }
        })
    })
    function weather(weatherData) {
        console.log(weatherData);
        var categories = [];
        var data = [];
        $.each(weatherData,function(i,v){
            categories.push(v.days);
            var arr = [parseInt(v.temp_low),parseInt(v.temp_high)];
            data.push(arr)
        })
        var chart = Highcharts.chart('container', {
            chart: {
                type: 'columnrange', // columnrange 依赖 highcharts-more.js
                inverted: true
            },
            title: {
                text: '一周温度变化范围'
            },
            subtitle: {
                text: weatherData[0]['citynm']
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                title: {
                    text: '温度 ( °C )'
                }
            },
            tooltip: {
                valueSuffix: '°C'
            },
            plotOptions: {
                columnrange: {
                    dataLabels: {
                        enabled: true,
                        formatter: function () {
                            return this.y + '°C';
                        }
                    }
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: '温度',
                data: data
            }]
        });
    }
</script>
  