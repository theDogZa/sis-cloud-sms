@extends('layouts.backend')
@section('content')
    <div class="container">
        <div class="content" style="margin-top: 150px !important;">
            <div class="offset-md-3 col-md-6">
                @if($isConnect)
                <small class="text-muted"> {{ $connect }}</small>
                <div class="block {{config('theme.layout.main_block')}}">
                    <div class="block-header text-center {{config('theme.layout.main_block_header')}}">
                        <h3 class="block-title">{{ ucfirst(__('sms.head_title.send')) }}</h3>
                    </div>
                    <div class="block-content">
                        <form action="#" method="POST" class="needs-validation"
                            enctype="application/x-www-form-urlencoded" id="form" novalidate>
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label class="col-12" for="NumAtCard">{{ ucfirst(__('sms.cusid.label')) }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-12 input-group">
                                    <input type="text" class="form-control form-control-lg" id="NumAtCard"
                                        name="NumAtCard" maxlength="{{ $config->cusid_maxlength }}" required placeholde="xxxx-xxxx">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-chk"
                                            class="btn btn-primary btn-lg js-tooltip-enabled" data-toggle="tooltip"
                                            title="check" data-original-title="check">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12" for="tel_1">{{ ucfirst(__('sms.tel.label')) }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-12 input-group">
                                    <input type="number" class="form-control form-control-lg input-tel" disabled
                                        id="tel_1" name="Tel_1" required placeholde="xxxxxxxxxx">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-send_1" data-row="1" disabled
                                            class="btn btn-secondary btn-lg js-tooltip-enabled btn-send"
                                            data-toggle="tooltip" title="check" data-original-title="check">
                                            <i class="fa fa-send"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="pl-4"><small class="text-muted message-box" id="Old_Tel_1"></small></div>
                            </div>

                            <div class="form-group row row_tel" id="row_tel_2" style="display: none">
                                <label class="col-12" for="tel_2">{{ ucfirst(__('sms.tel.label')) }}</label>
                                <div class="col-md-12 input-group">
                                    <input type="number" class="form-control form-control-lg input-tel" disabled
                                        id="tel_2" name="Tel_2" required placeholde="xxxxxxxxxx">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-send_2" data-row="2" disabled
                                            class="btn btn-secondary btn-lg js-tooltip-enabled btn-send"
                                            data-toggle="tooltip" title="check" data-original-title="check">
                                            <i class="fa fa-send"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="pl-4"><small class="text-muted message-box" id="Old_Tel_2"></small></div>
                            </div>

                            <div class="form-group row row_tel" id="row_tel_3" style="display: none">
                                <label class="col-12" for="tel_3">{{ ucfirst(__('sms.tel.label')) }}</label>
                                <div class="col-md-12 input-group">
                                    <input type="number" class="form-control form-control-lg input-tel" disabled
                                        id="tel_3" name="Tel_3" required placeholde="xxxxxxxxxx">
                                    <div class="input-group-append">
                                        <button type="button" id="btn-send_3" data-row="3" disabled
                                            class="btn btn-secondary btn-lg js-tooltip-enabled btn-send"
                                            data-toggle="tooltip" title="check" data-original-title="check">
                                            <i class="fa fa-send"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="pl-4"><small class="text-muted message-box" id="Old_Tel_3"></small></div>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <h4 class="text-danger"> {{ $connect }}</h4>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js_after')
<script>
    var isError = true

    document.getElementById('NumAtCard').addEventListener('keydown', function (event) {
        var len = $('#NumAtCard').val().length;
        var v = $('#NumAtCard').val()
        if (event.code == 'Delete') {
            $('.row_tel').hide()
            reSetInputTel();
        }
        if (event.code == 'Backspace') {
            reSetInputTel()
        } 
    });

    $(document).on('keypress', '#NumAtCard' ,function(event) {
        var len = $(this).val().length;
        if(len==4){
            var newv = $(this).val()+'-'
            $(this).val(newv)
        }
        if(len<9){
            reSetInputTel();
        }
    });

    $(document).on('click', '#btn-chk' ,function() {
        var len = $('#NumAtCard').val().length;
        var v = $('#NumAtCard').val()
        $('.row_tel').hide()
        if(len>=9){
            getTel();
        }
    });

    $(document).on('click', '.btn-send' ,async function() {
        var i = $(this).data('row');
        var tel = $('#tel_'+i).val();
        var NumAtCard = $('#NumAtCard').val();
        var gCode = Math.floor(1000 + Math.random() * 9000);
        
        var title = '{{ __("sms.confirm.title") }}'
        var message = '{{ __("sms.confirm.messages") }}'+tel + '<br><br><h3>' +gCode +'</h3>'
        var icon = 'warning'
        var con = await confirmMessage(title,message,icon);

        if(con==true){
            sentSms(NumAtCard,tel,gCode);
        }   
    });

    async function sentSms(NumAtCard,tel,gCode) {
        var res = [];
        var issent = false;
        var smsReturnId;
        var smsReturnStr;

        var api = '{{ $config->url }}'
        var username = '{{ $config->username }}'
        var password = '{{ $config->password }}'
        var txtMobile = '66'+ tel.substring(1, 10);
        var sender = '{{ $config->sender }}'      
        var txtSMS = '{{ $config->messages_1 }}'+' '+NumAtCard+' '+'{{ $config->messages_2 }}'+' '+gCode
        //--- fix test
        //txtMobile = '66819094220'

        var url = api+'?'+'username='+username+'&password='+password+'&txtMobile='+txtMobile+'&sender='+sender+'&txtSMS='+txtSMS;

        $.ajax({
            url :url,
            type : 'GET',
            async: false,
            cache: false,
            success: async function (xml) {
                var doc = $(xml.documentElement); 
                doc.find("SmsReturn").each(function (index, el) {   
                    res.push( {issent: true, Id: $(this).children('Id').text(), Str: $(this).children('strReturn').text()} ); 
                })   
            }
        });

        issent = res[0]['issent'];
        smsReturnId = res[0]['Id'];
        smsReturnStr = res[0]['Str'];

        addLog(NumAtCard,txtMobile,gCode,smsReturnId,smsReturnStr)
        if(smsReturnStr == 'OK'){
            var title =  '{{ __("sms.noit_message.success.title") }}'
            var message = '{{ __("sms.noit_message.success.messages") }}'
            var icon = 'success'     
        }else{
            var title =  '{{ __("sms.noit_message.error.title") }}'
            var message = '{{ __("sms.noit_message.error.messages") }}'+' : '+smsReturnStr
            var icon = 'error'
        }

        noitMessage(title,icon,message);
        reSetInput();

        return await true;
    }

    async function addLog(cusid,tel,code,smsReturnId,smsReturnStr) {
        var token = $("[name='_token']").val();
        return await $.ajax({
            url :'/api/v1/add-log',
            type : 'POST',
            data : {
                _token: token,
                cusid: cusid,
                tel: tel,
                code: code,
                sms_return_id: smsReturnId,
                sms_return_str: smsReturnStr,
            },
            success: function (response) {
                var i = 1;
                if(response.status==200){

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                alert(textStatus + ": " + jqXHR.status + " " + errorThrown + " " +'Please contact Admin !');
            }
        })
    }

    async function getTel() {
        var NumAtCard = $('#NumAtCard').val();
        var token = $("[name='_token']").val();
        
        return await $.ajax({
            url :'/api/v1/get-tel',
            type : 'POST',
            data : {
                _token: token,
                NumAtCard: NumAtCard
            },
            success: function (response) {
                var i = 1;
                if(response.status==200){
                    response.data.forEach(function(r) {  
                        changeInput(r,i,r.status)
                        i = i+1;
                    });
                }else{
                    var title =  response.title
                    var type = 'error'
                    noitMessage(title, type, response.message)
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                // alert(textStatus + ": " + jqXHR.status + " " + errorThrown + " " +'Please contact Admin !');
                var title =  response.status
                var type = 'error'
                var message = textStatus + ": " + jqXHR.status + " " + errorThrown + " " +'Please contact Admin !';
                noitMessage(title, type, message)
            }
        })
    }

    function changeInput(r,i,s) {

        if(i>1){           
            $('#row_tel_'+i).show();
        }

        if(r.status == 200){
            var message = r.old + ' - ' +r.message
            $('#Old_Tel_'+i).text(message);
            $('#tel_'+i).val(r.new);
            $('#btn-send_'+i).attr('disabled',false)
            $('#btn-send_'+i).removeClass( 'btn-secondary' ).addClass( 'btn-primary' );
            $('#Old_Tel_'+i).removeClass( 'text-danger' ).removeClass( 'text-muted' ).addClass( 'text-success' );
            $('#Old_Tel_'+i).show(); 
        }else {
            
            if(r.status == 400){
                var message = r.old + ' - ' +r.message
            }else if(r.status == 404){
                var message = r.message;
            }
            $('#Old_Tel_'+i).text(message);
            $('#tel_'+i).val('');
            $('#btn-send_'+i).attr('disabled',true)
            $('#btn-send_'+i).removeClass( 'btn-primary' ).addClass( 'btn-secondary' );
            $('#Old_Tel_'+i).removeClass( 'text-muted' ).removeClass( 'text-success' ).addClass( 'text-danger' );
            $('#Old_Tel_'+i).show();
        }
    }

    function reSetInput() {

        $('#NumAtCard').val('');
        reSetInputTel()
    }

    function reSetInputTel(){
        $('.input-tel').val('');
        $('.message-box').removeClass( 'text-danger' ).removeClass( 'text-success' ).addClass( 'text-muted' );
        $('.btn-send').attr('disabled',true)
        $('.btn-send').removeClass( 'btn-primary' ).addClass( 'btn-secondary' )
        $('.message-box').hide()
        $('.row_tel').hide()
    }

    async function confirmMessage(title, message, status = "warning") {
        return await swal({
            title: title,
            html: message,
            type: status,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes!",
        }).then((confirmed) => {
            if (confirmed.value == true) {
                return true;
            } else {
                return false;
            }
        }); 
    }

    async function noitMessage(title, type, message) {
        return await Swal.fire({
            title: title,
            type: type,
            html: message,
            showConfirmButton: true,
        }).then((confirmed) => {
            if (confirmed.value == true) {
                return true;
            } else {
                return false;
            }
        });
    }
</script>
@endsection
