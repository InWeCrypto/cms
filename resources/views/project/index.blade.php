@extends('layouts.main')
@section('content')
<form class="form-inline search-condition">
    <div class="form-group">
        <label>筛选条件 : </label>
        <select class="form-control change-redirect" data-url="{{ URL::to('/project') }}">
            <option value="">请选择...</option>
            @foreach($type as $k => $v)
                @if($k == \Request::get('type'))
                <option selected value="{{ $k }}">{{ $v }}</option>
                @else
                <option value="{{ $k }}">{{ $v }}</option>
                @endif
            @endforeach
        </select>
    </div>
</form>
<table class="table">
    <thead>
        <th>项目图标</th>
        <th>项目名称</th>
        <th>项目状态</th>
        <th>首页显示</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>操作</th>
    </thead>
    <tbody>
    @foreach($list as $li)
        <tr>
            <td><img height="49" src="{{ $li->img }}"></td>
            <td>{{ $li->name }}</td>
            <td>{{ $type[$li->type] }}</td>
            <td>{{ $li->enable ? '是' : '否' }}</td>
            <td>{{ $li->created_at }}</td>
            <td>{{ $li->updated_at }}</td>
            <td>
                <a class="btn btn-default btn-sm" href="{{ URL::to('/project', $li->id) }}">编辑</a>
                <a class="btn btn-default btn-sm" href="{{ URL::to('/project', $li->id) }}" data-method="delete" data-confirm="确认删除?">删除</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{!! $list->appends(\Request::query())->links() !!}

@include('layouts.message')
@stop
@push('script')
<script>
$(function(){
    $('.change-redirect').on('change', function(){
        var url = $(this).data('url');
        window.location.href = url + '?type=' + $(this).val();
    });
});
</script>
@endpush