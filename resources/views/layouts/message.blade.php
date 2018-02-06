@if (count($errors) > 0)
<div class="alert alert-danger">
    <strong> 操作失败！ </strong>
    <ul>
    @foreach($errors->all() as $message)
        <li>{{ $message }}</li>
    @endforeach
    </ul>
</div>
@elseif (Session::has('message.success'))
    <div class="alert alert-success" role="alert">{{ Session::get('message.success') }}</div>
@elseif (Session::has('message.fail'))
    <div class="alert alert-warning" role="alert">{{ Session::get('message.success') }}</div>
@endif