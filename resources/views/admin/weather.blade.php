@extends('layouts.admin')

@section('content')
<h4>一周气温展示</h4>
城市：<input type="text" name="city">
<input type="button" value="搜索" id="search">(城市名可以为拼音和汉字)
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

<script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
<script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
<script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
<script src=" {{ asset('admin/js/jquery.min.js') }} "></script>
<script>
$('#search').click(function(){

var city = $('[name="city"]').val();

if(city == ""){
    alert('请输入城市');
    return;
}
// alert(city);return false;
// 正则
var reg = /^[a-zA-Z]+$|^[\u4e00-\u9fa5]+$/;
var res = reg.test('city');
if(!res){
    alert('只能输入汉字和拼音');
    return;
}
$.ajax({
    url:"{{url('admin/weather_do')}}",
    data:{city:city},
    dataType:"json",
    success:function(res){
        weather(res.result);
    }


})
});


function weather(weatherData){
    console.log(weatherData);
    var categories=[];
    var data=[];
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
            text: '一周天气预报'
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
            $.ajax({
    url:"{{url('admin/weather_do')}}",
    data:{city:'北京'},
    dataType:"json",
    success:function(res){
        weather(res.result);
    }


})


</script>

@endsection