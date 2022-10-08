@extends('layouts.backend')

@section('content')
   
   <div class="container">
    <!-- Content Heading -->
    <h2 class="content-heading pt-2">
        <i class="{{config('theme.icon.menu_sms')}} mr-2"></i>{{ ucfirst(__('sms.heading')) }}
        <div class="block" style="clear: both; float: right; margin-bottom: 0px;">
            <div class="block-content block-content-full">
                <form class="form-inline" action="{{ route('log-sms') }}" method="post">
                    {{ csrf_field() }}
                    <label for="cus_id">{{ ucfirst(__('sms.cusid.label')) }} : </label>
                    <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0  ml-2" id="NumAtCard" value="{{$cusId}}" maxlength="{{ Config::get('sms.cusid_maxlength') }}" name="cus_id" placeholder="xxxx-xxxx">
                    <label for="date">{{ ucfirst(__('sms.date.label')) }} : </label>
                    <input type="text" class="form-control bg-white js-datepicker-enabled ml-2" 
                        data-date-format="yyyy-mm-dd" 
                        data-week-start="1" 
                        data-autoclose="true" 
                        data-today-highlight="true"
                        data-date-end-date="0d"
                        data-date-start-date="-90d"
                        value="{{$sDate}}"
                        id="date" name="date" placeholder="yyyy-mm-dd" readonly="readonly">
                    <button type="submit" class="btn btn-alt-primary ml-2"> <i class="fa fa-search"></i> {{ ucfirst(__('core.button_search')) }}</button>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
    </h2>
    <!-- END Content Heading -->
           
    <!-- Content Main -->
    <div class="block {{config('theme.layout.main_block')}}">
        <div class="block-header {{config('theme.layout.main_block_header')}}">
            <h3 class="block-title">
                <i class="{{config('theme.icon.item_list')}} mr-2"></i>
                {{ ucfirst(__('sms.head_title.history')) }}
                <small> ( {{$total}} {{ ucfirst(__('core.data_total_records')) }} ) </small>
            </h3>
        </div>

        <div class="block-content">
            <!-- ** Content Data ** -->
            <table class="table table-striped {{config('theme.layout.table_list_item')}}">
                <thead class="thead-light {{config('theme.layout.table_list_item_head')}}">
                    <tr>
                        <th>{{ucfirst(__('core.th_iteration_number'))}}</th>
                        <th>
                            {{ ucfirst(__('sms.date.th')) }}
                        </th>
                        <th>
                            {{ ucfirst(__('sms.cusid.th')) }}
                        </th>
                        <th>
                            {{ ucfirst(__('sms.tel.th')) }}
                        </th>
                        <th>
                            {{ ucfirst(__('sms.code.th')) }}
                        </th>
                        <th>
                            {{ ucfirst(__('sms.ip.th')) }}
                        </th>
                        <th>
                            {{ ucfirst(__('sms.sms_return_id.th')) }}
                        </th>
                        <th>
                            {{ ucfirst(__('sms.sms_return_str.th')) }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($collection)
                    @foreach ($collection as $item)
                    <tr id="row_{{$loop->iteration}}">
                        <td>{!! $loop->iteration !!}</td>
                        <td>{!! $item->date !!}</td>
                        <td>{!! $item->cusid !!}</td>
                        <td>{!! $item->tel !!}</td>
                        <td>{!! $item->code !!}</td>
                        <td>{!! $item->ip !!}</td>
                        <td>{!! $item->sms_return_id !!}</td>
                        <td>{!! $item->sms_return_str !!}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="8" class="text-center">{{ ucfirst(__('core.no_records')) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <!-- END Content Data --> 
        </div>
    </div>
    <!-- END Content Main -->
</div>
   
@endsection
@section('js_after')

    <script src="{{ asset('js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" id="css-main" href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <script>
        $('.js-datepicker-enabled').datepicker();

        $(document).on('keypress', '#NumAtCard' ,function() {
            var len = $(this).val().length;
            if(len==4){
                var newv = $(this).val()+'-'
                $(this).val(newv)
            }
        });
</script>
@endsection
@section('css_after')
<style>
.bock-sub-menu {
    float: right;
    margin-bottom: 0.25rem !important;
}

.bock-sub-menu a {
    margin-top: -5px;
}
</style>
@endsection