@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', 'Push Notification')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bell"></i>
        Push Notification
    </h1>
@stop

@section('content')
<div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form"
                            class="form-edit-add"
                            action="/vendor/voyager/pushnotification"
                            method="POST">
                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}

                        <div class="panel-body">

                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- custom fields -->
                            <div class="form-group col-md-12">
                                    <label class="control-label" for="notification_name">Notification Type</label>
                                    {!! Form::select('notification_name',array('give_promotion'=>'give_promotion','send_gratisan'=>'send_gratisan'),null,['id'=>'notification_name','class'=>'form-control']); !!}
                            </div>
                            <div class="form-group col-md-12">
                                    <label class="control-label" for="title">Title</label>
                                    {!! Form::text('title',null,['id'=>'title','class'=>'form-control']); !!}
                            </div>                            
                            <div class="form-group col-md-12">
                                    <label class="control-label" for="body">Body</label>
                                    {!! Form::textarea('body',null,['id'=>'body','class'=>'form-control']); !!}
                            </div>
                            <div class="form-group col-md-12">
                                    <label class="control-label" for="name">merchants</label>
                                    {!! Form::select('merchant_id',App\Merchant::pluck('name','id'),null,['id'=>'merchant_id','class'=>'form-control']); !!}
                            </div>                            
                            <div class="form-group col-md-12">
                                    <label class="control-label" for="name">stores</label>
                                    {!! Form::select('store_id',['' => 'Select Merchant'],null,['id'=>'store_id','class'=>'form-control']); !!}
                            </div>
                            <div class="form-group col-md-12">
                                    <label class="control-label" for="name">products</label>
                                    {!! Form::select('product_id',['' => 'Select Merchant'],null,['id'=>'product_id','class'=>'form-control']); !!}
                            </div>

                        </div><!-- panel-body -->

                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">Send Push Notification</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('#merchant_id').change();
        });

        $('#merchant_id').on('change', function() {
            $('#store_id').empty();
            $('#product_id').empty();
            $.ajax({
                type: "POST",
                url: "/admin/store-product-stocks/get-store",
                data: { 'merchant_id': $('#merchant_id').children("option:selected").val() }, 
                success: function(data){
                    $.each(data, function(i, d) {
                        $('#store_id').append('<option value="' + d.id + '">' + d.name + '</option>');
                    });
                }
            });
            $.ajax({
                type: "POST",
                url: "/admin/store-product-stocks/get-product",
                data: { 'merchant_id': $('#merchant_id').children("option:selected").val() }, 
                success: function(data){
                    $.each(data, function(i, d) {
                        $('#product_id').append('<option value="' + d.id + '">' + d.name + '</option>');
                    });
                }
            });
        });
    </script>
@stop