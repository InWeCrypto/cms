@extends('layouts.main')
@section('content')
<div style="margin: 25px auto;">
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a href="#desc" data-toggle="tab">{{ $info->name }}</a></li>
        <li><a href="#describe" data-toggle="tab">项目介绍</a></li>
        <li><a href="#time_prices" data-toggle="tab">项目行情</a></li>
        <li><a href="#markets" data-toggle="tab">交易市场</a></li>
        <li><a href="#medias" data-toggle="tab">更多资讯</a></li>
        <li><a href="#wallets" data-toggle="tab">钱包</a></li>
        <li><a href="#explorers" data-toggle="tab">浏览器</a></li>
    </ul>

     <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="desc">
        @include('project.edit')
        </div>
        <div class="tab-pane" id="describe">项目介绍</div>
        <div class="tab-pane" id="time_prices">项目行情</div>
        <div class="tab-pane" id="markets">交易市场</div>
        <div class="tab-pane" id="medias">更多资讯</div>
        <div class="tab-pane" id="wallets">钱包</div>
        <div class="tab-pane" id="explorers">浏览器</div>
    </div>        
</div>
@include('layouts.message')
@stop
@push('script')
<script>
$(function(){
var tabsData = [{
        "id" : "describe",
        "url" : "{{ URL::to('project', $info->id) }}"
    };
    $(tabsData).each(function(){
    //console.info(this.id + "--->" + this.url);
    $("a[href='#"+this.id+"']").bind('click',{
        id : this.id,
        url : this.url
    },tabsHandler);
    });
    function tabsHandler(event) {
        var data = event.data;
        showTabs(data.id,data.url);
        return false; //阻止默认a标签响应
    }
});
</script>
@endpush
