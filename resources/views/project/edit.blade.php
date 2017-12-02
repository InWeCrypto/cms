<form action="{{ URL::to('/project', $info->id) }}" enctype="multipart/form-data" method="post" class="form-horizontal" style="margin: 25px auto;">
    {!! method_field('put') !!}
    {!! csrf_field() !!}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">项目状态: </label>
                <div class="col-md-8">
                    <select name="type" class="form-control">
                        @foreach($type as $k => $v)
                        @if($k == $info->type)
                        <option selected value="{{ $k }}">{{ $v }}</option>
                        @else
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">项目名称: </label>
                <div class="col-md-8">
                      <input type="text" name="name" value="{{ $info->name }}" class="form-control" placeholder="项目名称">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">项目名称( 长 ): </label>
                <div class="col-md-8">
                      <input type="text" name="long_name" value="{{ $info->long_name }}" class="form-control" placeholder="项目名称(长)">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">项目评分: </label>
                <div class="col-md-8">
                      <input type="number" name="score" value="{{ $info->score }}" class="form-control" placeholder="项目评分">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">项目官网: </label>
                <div class="col-md-8">
                      <input type="text" name="website" value="{{ $info->website }}" class="form-control" placeholder="项目官网">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-2 control-label">实时价格( 连接 ) : </label>
                <div class="col-md-10">
                    <input type="text" name="url" value="{{ $info->url }}" class="form-control" placeholder="项目名称(连接)">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-md-2 control-label">Twitter ( 内容 ) : </label>
                <div class="col-md-10">
                    <textarea class="form-control" rows="3" name="desc" placeholder="Twitter (内容)">{{ $info->desc }}</textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">颜色 ( 首页块 ): </label>
                <div class="col-md-8">
                      <input type="color" name="color" value="{{ $info->color }}" class="form-control" placeholder="项目名称(连接)">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">大小 ( 首页块 ): </label>
                <div class="col-md-8">
                    <select name="grid_type" class="form-control">
                        @foreach($grid_type as $k => $v)
                        @if($k == $info->grid_type)
                        <option selected value="{{ $k }}">{{ $v }}</option>
                        @else
                        <option value="{{ $k }}">{{ $v }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">排序 ( 首页块 ): </label>
                <div class="col-md-8">
                        <input type="number" name="sort" value="{{ $info->sort }}" class="form-control" placeholder="排序 (首页块)">
                    
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">图标 ( 首页块 ): </label>
                <div class="col-md-8">
                    <input type="file" name="img" accept="image/png,image/gif,image/jpeg" />
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-md-4 control-label">显示 ( 首页块 ): </label>
                <div class="col-md-8">
                    <label class="radio-inline">
                        <input type="radio" @if($info->enable)
                        checked
                        @endif name="enable" value="1"> 显示
                    </label>
                    <label class="radio-inline">
                        <input type="radio" @if(!$info->enable)
                        checked
                        @endif name="enable" value="0"> 不显示
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-offset-2">
            <button type="submit" class="btn btn-info btn-sm">确认修改</button>                
            <a href="{{ URL::to('/project') }}" class="btn btn-default btn-sm">取消</a>
        </div>
    </div>
</form>
