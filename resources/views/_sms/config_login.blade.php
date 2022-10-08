@extends('layouts.backend')

@section('content')

<div class="container">
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.menu_sms')}} mr-2"></i>{{ ucfirst(__('sms.heading')) }}
        <div class="clearfix"></div>
    </h2>
    <!-- END Content Heading -->

    @if (session('error'))
    <div class="alert alert-danger alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h3 class="alert-heading font-size-h5 font-w700 mb-5">Error</h3>
        <p class="mb-0">
            {{ session('error') }}
        </p>
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success alert-dismissable" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h3 class="alert-heading font-size-h5 font-w700 mb-5">Success</h3>
        <p class="mb-0">
            {{ session('success') }}
        </p>
    </div>
    @endif
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
            
            <form action="{{ route('config-sms') }}" method="POST" class="needs-validation" enctype="application/x-www-form-urlencoded" id="form" novalidate>
                {{ csrf_field() }}
                
                <div class="form-group row">
                    <label class="col-12" for="username">{{ ucfirst(__('sms.config.username')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-lg" id="username" name="username" required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.username')) ])}}
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-12" for="password">{{ ucfirst(__('sms.config.password')) }} 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-md-12">
                        <input type="password" class="form-control form-control-lg" id="password" name="password"  required placeholde="xxxx-xxxx">
                        <div class="invalid-feedback animated fadeInDown">
                            {{__('validation.required',['attribute'=> ucfirst(__('sms.config.password'))])}}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col">
                        <button type="submit" class="btn btn-primary min-width-125 js-click-ripple-enabled" data-toggle="click-ripple" style="overflow: hidden; position: relative; z-index: 1;">
                            <i class="fa fa-key mr-2"></i> {{ ucfirst(__('sms.btn.login')) }}
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