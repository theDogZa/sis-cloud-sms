@extends('layouts.backend')

@section('content')

<div class="container">
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.menu_sms')}} mr-2"></i>{{ ucfirst(__('sms.heading')) }}
        <div class="clearfix"></div>
    </h2>
    <!-- END Content Heading -->

    <!-- Content Main -->
    <div class="block {{config('theme.layout.main_block')}}">
        <div class="block-header {{config('theme.layout.main_block_header')}}">
            <h3 class="block-title">
                <i class="{{config('theme.icon.item_list')}} mr-2"></i>
                {{ ucfirst(__('sms.head_title.config')) }}
                <small></small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <form action="{{ route('update-config-sms') }}" method="POST" class="needs-validation" enctype="application/x-www-form-urlencoded" id="form" novalidate>
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <div class="form-group row">
                    <label class="col-12" for="url">{{ ucfirst(__('sms.config.url')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="url" name="url" value="{{$url}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.url'))])}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="username">{{ ucfirst(__('sms.config.username')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="username" name="username" value="{{$username}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.username'))])}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="password">{{ ucfirst(__('sms.config.password')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="password" name="password" value="{{$password}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.password'))])}}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-12" for="sender">{{ ucfirst(__('sms.config.sms_sender')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="sender" name="sender" value="{{@$sender}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.sms_sender'))])}}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-12" for="messages_1">{{ ucfirst(__('sms.config.sms_messages')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12 form-group">
                        <div class="input-group input-group-lg" >
                            <input type="text" class="form-control form-control-lg" id="messages_1" name="messages_1" value="{{@$messages_1}}" required placeholde="xxxx-xxxx">
                            <div class="input-group-prepend input-group-append">
                                <span class="input-group-text font-w600">xxxx-xxxx</span>
                            </div>
                            <input type="text" class="form-control form-control-lg" id="messages_2" name="messages_2" value="{{@$messages_2}}" required placeholde="xxxx-xxxx">
                            <div class="input-group-prepend input-group-append ">
                                <span class="input-group-text font-w600">xxxx</span>
                            </div>
                        </div>
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.sms_messages'))])}}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-12" for="cusid_maxlength">{{ ucfirst(__('sms.config.cusid_maxlength')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="cusid_maxlength" name="cusid_maxlength" value="{{@$cusid_maxlength}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.cusid_maxlength'))])}}
                        </div>
                    </div>
                </div>
                {{-- db --}}
                <div class="form-group row">
                    <label class="col-12" for="db_host">{{ ucfirst(__('sms.config.db_host')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="db_host" name="db_host" value="{{@$db_host}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.db_host'))])}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="db_port">{{ ucfirst(__('sms.config.db_port')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="db_port" name="db_port" value="{{@$db_port}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.db_port'))])}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="db_database">{{ ucfirst(__('sms.config.db_database')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="db_database" name="db_database" value="{{@$db_database}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.db_database'))])}}
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-12" for="db_username">{{ ucfirst(__('sms.config.db_username')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="db_username" name="db_username" value="{{@$db_username}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.db_username'))])}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="db_password">{{ ucfirst(__('sms.config.db_password')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="db_password" name="db_password" value="{{@$db_password}}" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.db_password'))])}}
                        </div>
                    </div>
                </div>

                <hr>
                <div class="row mb-3">
                    <div class="col">
                        <button type="submit" class="btn btn-primary min-width-125 js-click-ripple-enabled" data-toggle="click-ripple" style="overflow: hidden; position: relative; z-index: 1;">
                            <i class="fa fa-save mr-2"></i> {{ ucfirst(__('sms.btn.save')) }} 
                        </button>
                    </div>
                </div>
            </form>
            <!-- END Content Data -->
        </div>
    </div>
    <!-- END Content Main -->
</div>

@endsection
@section('js_after')
<script>
(function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
            });
        }, false);
        })();
</script>
@endsection
@section('css_after')
<style>

</style>
@endsection