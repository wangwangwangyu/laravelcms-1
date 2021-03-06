@extends("admin.include.mother")

@section("content")

    <div class="clearfix">
        <div class="float-left">
            @component('admin.component.breadcrumb')
                @slot('name')
                    管理员
                @endslot
            @endcomponent
        </div>
        <div class="float-right">
            <a role="button" class="btn btn-sm btn-primary" href="{{url('/admin/manager_user/create')}}"><i class="fa fa-plus"></i> 新增管理员</a>
        </div>
    </div>
    <div class="h15"></div>


    <table class="table table-sm table-hover table-bb">

        <tr>
            <th>ID</th>
            <th>用户名称</th>
            <th>账号</th>
            <th>关联角色</th>
            <th>最后更新</th>
            <th>操作</th>
        </tr>

        @foreach($list as $vo)
            <tr>
                <td>{{$vo->id}}</td>
                <td>{{$vo->name}}</td>
                <td>{{$vo->account}}</td>
                <td>
                    @foreach($vo->roles as $voo)
                        <span class="badge badge-pill badge-secondary">{{$voo->name}}</span>
                    @endforeach

                </td>
                <td>{{$vo->updated_at}}</td>
                <td>
                    <a class="btn btn-sm btn-light" href="{{url('/admin/manager_user/edit?id='.$vo['id'])}}" role="button" title="编辑"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-sm btn-light" href="#" role="button" onclick="return alert_powers('{{$vo['name']}}',{{$vo['id']}})" title="权限"><i class="fa fa-empire"></i></a>
                    <a class="btn btn-sm btn-light" href="#" role="button" onclick="return del_user({{$vo['id']}});" title="删除"><i class="fa fa-trash"></i></a>
                    <a class="btn btn-sm btn-light" href="#" role="button" onclick="return alert_win_repass({{$vo['id']}});" title="修改密码"><i class="fa fa-key"></i></a>

                </td>
            </tr>
        @endforeach

    </table>

    <div id="permission" class="hide">
        <div class="js-permission-con">
            权限
        </div>
    </div>
    <script>
        //显示用户权限
        function alert_powers(name,id){
            $.ajax({
                type:'post',
                url:'/admin/manager_user/ajax_page_powers',
                data:{id:id},
                success:function(res){
                    if(res.status == 0){
                        $boot.warn({text:res.msg});
                    }else{
                        $('.js-permission-con').html(res.body);
                        $boot.win({id:'#permission',size:'lg',title:name+'的权限'});
                    }
                }
            });
            return false;
        }
    </script>

    <div id="win_repass" class="d-none">
        <div class="js-win_repass">
            <input type="hidden" name="id" value="" />
            <div class="form-group">
                <label>新密码</label>
                <input type="text" class="form-control" name="password" placeholder="新密码">
                <small class="form-text text-muted">1-20个字符，选填</small>
            </div>
            <div class="h10"></div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" onclick="return submit_repass();">确认</button>
            </div>
        </div>
    </div>
    <script>
        //弹出修改密码窗口
        function alert_win_repass(id){
            $('.js-win_repass').find('input[name=id]').val(id);
            $boot.win({id:'#win_repass','title':'修改密码'});
            return false;
        }
        //修改密码提交
        function submit_repass(){
            if(!$('.js-win_repass').find('input[name=password]').val()){
                $boot.warn({text:'密码不能为空'});
                return false;
            }
            var data = {
                id:$('.js-win_repass').find('input[name=id]').val(),
                password:$('.js-win_repass').find('input[name=password]').val(),
            };
            $.ajax({
                type:'post',
                url:'/admin/manager_user/ajax_repass',
                data:data,
                success:function(res){
                    if(res.status == 0){
                        $boot.warn({text:res.msg},function(){
                            $('.js-win_repass').find('input[name='+res.field+']').focus();
                        });
                    }else{
                        $boot.success({text:res.msg},function(){
                            window.location = window.location;
                        });
                    }
                }
            });
            return false;
        }
    </script>



    <script>

        //删除角色
        function del_user(id){
            $boot.confirm({text:'确认删除该角色？'},function(){
                if(!id){
                    $boot.warn({text:'删除参数出错'});
                    return false;
                }
                $.ajax({
                    type:'post',
                    url:'/admin/manager_user/ajax_del',
                    data:{id:id},
                    success:function(res){
                        if(res.status == 0){
                            $boot.warn({text:res.msg});
                        }else{
                            $boot.success({text:res.msg},function(){
                                window.location = window.location;
                            });

                        }
                    }
                });
            });
            return false;
        }
    </script>

@endsection