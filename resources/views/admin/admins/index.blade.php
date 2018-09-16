@extends('admin.index')
@section('content')
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">{{$title}}</h3> <!--Admin Control => in AdminController-->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      {!!Form::open(['id'=>'form_data', 'url'=>aurl('admin/destroy/all')])!!}
      {!!Form::hidden('_method', 'delete')!!}
      {!!$dataTable->table(['class'=>'dataTable table table-striped table-hover table-bordered'],true)!!}
      {!!Form::close()!!}
    </div>
  </div> 

<!------------------------------------------------------->
<!-- Modal -->
<div id="multipleDelete" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{trans('admin.delete')}}</h4>
      </div>
      <div class="modal-body">
        <!--alert messages-->
        <div class="alert alert-danger">

          <div class="empty_record hidden ">
            <h1>{{trans('admin.check_records')}}</h1>  
          </div>
          <div class="not_empty_record hidden">
            <h1>{{trans('admin.ask_delete_item')}} <span class="record_count">5</span>?</h1>  
          </div>
          
        </div>
      </div>
      <div class="modal-footer">
        <div class="empty_record hidden">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('admin.close')}}</button>
        </div>  
        <div class="not_empty_record hidden">
          <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('admin.No')}}</button>
          <input type="submit" name="del_all" value="{{trans('admin.Yes')}}" class="btn btn-danger del_all">
        </div>
      </div>
    </div>

  </div>
</div>
@push('js')
<script>
  delete_all();
</script> 
    {!!$dataTable->scripts()!!}
  @endpush  
@endsection