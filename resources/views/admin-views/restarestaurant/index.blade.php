@extends('layouts.admin.app')

@section('title',translate('messages.account_transaction'))

@push('css_or_js')
<style>
    /* .table-width{
        width: 100px;
        border: 1px;
        border-color: 000000;
     } */
    .text-transform thead th{
        text-transform: none;
    }
    .thead-light th{
        background-color:white !important;
    };
    .thead-light td{
      color:red;
    };
    .checkbox-container {
        position: relative;
        padding-left: 30px;
    }
    .checkbox {
        position: absolute;
        top: 0;
        left: 0;
        width: 20px;
        height: 20px;
        opacity: 0;
    }
    .custom-checkbox {
        top: 0;
        left: 0;
        width: 20px;
        height: 20px;
        background-color: #D9D9D9;
    }
    .checkbox:checked + .custom-checkbox {
        background-color: black;
    }
    .color{
        background-color: #D9D9D9 !important;
    }
    a[class*="btn--"], button[class*="btn--"] {
        font-weight: 500 !important;
    }
    .btn--reset {
        border-radius: 0 !important;
    }
    .table-container {
        /* max-width: 900px; */
        max-height:280px;
        overflow-x: auto !important;
        overflow: auto;
    }
    .table-container::-webkit-scrollbar {
        width: 12px !important;
    }
    .btn-flex{
        display: flex;
        justify-content: space-between;
    }
    .span-color{
        color: black;
        font-size: 13px;
        font-weight: 500;
    }
    /* .layouts-thead{
        width: 50px;
        height: 15px;
        top: 271px;
        left: 279px;
    }
    .color-thead{
        font-family: Inter;
        font-size: 12px;
        font-weight: 400;
        line-height: 15px;
        letter-spacing: 0em;
        text-align: left;

    }
    .layouts{
        width: 36px;
        height: 15px;
    }
    .font-color{
        font-family: Inter;
        font-size: 12px;
        font-weight: 400;
        line-height: 15px;
        letter-spacing: 0em;
        text-align: left;
        color: #000000;
    }
    .heading{
        width: 103px;
        height: 15px;
        top: 567px;
        left: 219px;

    }
    .font-style{
        font-family: Inter;
        font-size: 12px;
        font-weight: 400;
        line-height: 15px;
        letter-spacing: 0em;
        text-align: left;

    } */
</style>
@endpush

@section('content')
<div class="content container-fluid">

    <!-- Page Heading -->
    <div class="page-header">
        <h1 class="page-header-title mb-2 text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/collect-cash.png')}}" class="w-20px" alt="public">
            </div>
            <span>
                {{ translate('Cash Collection Transaction') }}
            </span>
        </h1>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{route('admin.account-transaction.store')}}" method='post' id="add_transaction">
                @csrf
                <div class="row">
                    {{-- <div class="col-md-4">
                        <div class="form-group">
                        <label class="input-label" for="type">{{translate('messages.type')}}<span class="input-label-secondary"></span></label>
                            <select name="type" id="type" class="form-control h--48px">
                                <option value="deliveryman">{{translate('messages.deliveryman')}}</option>
                                <option value="restaurant">{{translate('messages.restaurant')}}</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="restaurant">{{translate('messages.select restaurant')}}<span class="input-label-secondary"></span></label>
                            <select id="restaurant" name="restaurant_id" data-placeholder="{{translate('messages.select restaurant')}}" onchange="getAccountData('{{url('/')}}/admin/restaurant/get-account-data/',this.value,'restaurant')" class="form-control h--48px" title="Select Restaurant">
                            </select>
                        </div>
                    </div>
                   <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="start_date">{{ translate('messages.select start date') }}</label>
                            <input class="form-control h--48px datepicker" type="text" name="start_date" id="start_date" maxlength="191" placeholder="{{ translate('Ex : Collect Cash') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="end_date">{{ translate('messages.select end date') }}</label>
                            <input class="form-control h--48px datepicker" type="text" name="end_date" id="end_date" maxlength="191" placeholder="{{ translate('Ex : Collect Cash') }}" autocomplete="off">
                        </div>
                    </div>
                        {{-- <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="deliveryman">{{translate('messages.deliveryman')}}<span class="input-label-secondary"></span></label>
                                <select id="deliveryman" name="deliveryman_id" data-placeholder="{{translate('messages.select')}} {{translate('messages.deliveryman')}}" onchange="getAccountData('{{url('/')}}/admin/delivery-man/get-account-data/',this.value,'deliveryman')" class="form-control h--48px" title="Select deliveryman">
                                </select>
                            </div>
                        </div> --}}
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="method">{{translate('messages.method')}}<span class="input-label-secondary"></span></label>
                            <input class="form-control h--48px" type="text" name="method" id="method" required maxlength="191" placeholder="{{ translate('Ex : Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="ref">{{translate('messages.reference')}}<span class="input-label-secondary"></span></label>
                            <input  class="form-control h--48px" type="text" name="ref" id="ref" maxlength="191" placeholder="{{ translate('Ex : Collect Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="amount">{{translate('messages.payment by')}}<span class="input-label-secondary" id="account_info"></span></label>
                            <input class="form-control h--48px" type="number" min=".01" step="0.01" name="amount" id="amount" max="999999999999.99" placeholder="{{ translate('Ex : 100') }}">
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="deliveryman">{{translate('messages.add collected by')}}<span class="input-label-secondary"></span></label>
                            <select id="deliveryman" name="deliveryman_id" data-placeholder="{{translate('messages.select')}} {{translate('messages.deliveryman')}}" onchange="getAccountData('{{url('/')}}/admin/delivery-man/get-account-data/',this.value,'deliveryman')" class="form-control h--48px" title="Select deliveryman">                            </select>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                    <button type="submit" class="btn btn--primary">{{translate('messages.collect')}} {{translate('messages.cash')}}</button>
                </div> --}}
            </form>
            <div class="">
                <div class="card-header py-2 border-0">
                    <div class="search--button-wrapper">
                       <h3 class="card-title">
                            {{-- <span>{{ translate('messages.transaction')}} {{ translate('messages.table')}}</span>
                            <span class="badge badge-soft-secondary" id="itemCount" >{{$account_transaction->total()}}</span> --}}
                        </h3> 
                        <!-- Static Search Form -->
                        <form action="javascript:" id="search-form" class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('Search by Reference') }}" aria-label="Search" required="">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                        <!-- End Search -->
                        </form>
                        {{-- <div class="hs-unfold ml-3">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn btn-outline-primary btn--primary font--sm" href="javascript:;"
                                data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                            </a>
        
                            <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                                <a id="export-excel" class="dropdown-item" href="{{route('admin.export-account-transaction', ['type'=>'excel'])}}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                            alt="Image Description">
                                    {{translate('messages.excel')}}
                                </a>
                                <a id="export-csv" class="dropdown-item" href="{{route('admin.export-account-transaction', ['type'=>'csv'])}}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                            alt="Image Description">
                                    .{{translate('messages.csv')}}
                                </a>
                            </div>
                        </div> --}}
                        <!-- Static Export Button -->
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <div class="table-container"style="overflow-x:auto;">
                            <table id="datatable"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-transform table-width">
                            <thead class="">
                                <tr class="thead-light">
                                    <th>
                                        <label class="checkbox-container">
                                            <input type="checkbox" id="yourCheckboxId" name="yourCheckboxName" class="checkbox">
                                            <div class="custom-checkbox"></div>
                                        </label>
                                    </th>
                                    <th>{{ translate('messages.order id') }}</th>
                                    <th>{{ translate('messages.order totel') }}</th>
                                    <th>{{translate('messages.service fee')}}</th>
                                    <th>{{translate('messages.date')}}</th>
                                    <th>{{translate('messages.order status')}}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows" class="">
                                @foreach($account_transaction as $k=>$at)
                                    <tr class="thead-light">
                                        {{-- <td scope="row">{{$k+$account_transaction->firstItem()}}</td> --}}
                                        <th>
                                            <label class="checkbox-container">
                                                <input type="checkbox" id="yourCheckboxId" name="yourCheckboxName" class="checkbox">
                                                <div class="custom-checkbox"></div>
                                            </label>
                                        </th>
                                        <td>
                                        12345
                                        </td>
                                        <td>240</td>
                                        <td>
                                            {{-- {{  Carbon\Carbon::parse($at->created_at)->locale(app()->getLocale())->translatedFormat('d M Y '.config('timeformat')) }} --}}
                                            10
                                        </td>
                                        <td>12-08-2023</td> 
                                        <td>Completed</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <hr>
                        <div class="mt-3">
                            <span class="span-color">Selected 0 Orders</span>
                        </div>
                        <div class="mt-3">
                                <span class="span-color">Order total payment: </span>
                               <span class="span-color">total of selected payments here</span>
                        </div>
                        <div class="mt-2">
                                <span class="span-color">Total service fees: </span>
                               <span class="span-color">total of services pay of selected orders</span>
                        </div>
                        <div class="btn-flex mt-3">
                            <div class="">
                                <span class="span-color">Payment to Restaurant: </span>
                                <span class="span-color">Order total payment - Total service fees </span>
                            </div>
                            <div class="">
                                <button type="reset" id="reset_btn" class="btn btn--reset color">{{translate('messages.reset')}}</button>
                                <button type="reset" id="reset_btn" class="btn btn--reset color ml-3 mr-5">{{translate('messages.conform payment:')}}</button>
                            </div>
                        </div>
                        
                        @if(count($account_transaction) === 0)
                        <div class="empty--data">
                            <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                            <h5>
                                {{translate('no_data_found')}}
                            </h5>
                        </div>
                        @endif
                    </div>
                </div>
                {{-- <div class="card-footer border-0 pt-0">
                    <div class="page-area px-4 pb-0">
                        <div class="d-flex align-items-center justify-content-end">
                            <div>
                                {{$account_transaction->links()}}
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        {{-- <div class="">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="datatable"
                        class="table table-borderless  table-nowrap table-align-middle card-table text-transform table-width">
                        <thead class="">
                            <tr class="layouts-thead">
                                <th>
                                    <label class="checkbox-container">
                                        <input type="checkbox" id="yourCheckboxId" name="yourCheckboxName" class="checkbox">
                                        <div class="custom-checkbox"></div>
                                    </label>
                                </th>
                                <th class="color-thead">Order Id</th>
                                <th>Order Total</th>
                                <th>Service fee</th>
                                <th>Date</th>
                                <th>Order Status</th>
                            </tr>
                        </thead>
                        <tbody id="set-rows">
                            <tr class="layouts">
                                <td scope="row">
                                    <label class="checkbox-container">
                                        <input type="checkbox" id="yourCheckboxId" name="yourCheckboxName" class="checkbox">
                                        <div class="custom-checkbox"></div>
                                    </label>
                                </td>
                                <td class="font-color">12345</td>
                                <td class="font-color">240</td>
                                <td class="font-color">10</td>
                                <td class="font-color">12-08-2023</td>
                                <td class="font-color">Completed</td>
                            </tr>
                            <tr class="layouts">
                                <td scope="row">
                                    <label class="checkbox-container">
                                        <input type="checkbox" id="yourCheckboxId" name="yourCheckboxName" class="checkbox">
                                        <div class="custom-checkbox"></div>
                                    </label>
                                </td>
                                <td class="font-color">12345</td>
                                <td class="font-color">240</td>
                                <td class="font-color">10</td>
                                <td class="font-color">12-08-2023</td>
                                <td class="font-color">Completed</td>
                            </tr>
                        </tbody>
                    </table>
                    <div>    
                        <tr class="heading">
                            <td class="font-style">Selected 0 Orders</td>
                        </tr>
                         <tr class="heading">
                            <td class="font-style">Order total payment:total of selected payments here</td>
                        </tr>
                   </div>
                </div>
            </div>
            
        </div> --}}
    </div>
    
</div>
@endsection

@push('script_2')
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SELECT2
        // =======================================================
        $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });

        $('#type').on('change', function() {
            if($('#type').val() == 'restaurant')
            {
                $('#restaurant').removeAttr("disabled");
                $('#deliveryman').val("").trigger( "change" );
                $('#deliveryman').attr("disabled","true");
            }
            else if($('#type').val() == 'deliveryman')
            {
                $('#deliveryman').removeAttr("disabled");
                $('#restaurant').val("").trigger( "change" );
                $('#restaurant').attr("disabled","true");
            }
        });
    });
    $('#restaurant').select2({
        ajax: {
            url: '{{url('/')}}/admin/restaurant/get-restaurants',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    $('#deliveryman').select2({
        ajax: {
            url: '{{url('/')}}/admin/delivery-man/get-deliverymen',
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    function getAccountData(route, data_id, type)
    {
        $.get({
                url: route+data_id,
                dataType: 'json',
                success: function (data) {
                    $('#account_info').html('({{translate('messages.cash_in_hand')}}: '+data.cash_in_hand+' {{translate('messages.earning_balance')}}: '+data.earning_balance+')');
                },
            });
    }
</script>
<script>
    $('#add_transaction').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.account-transaction.store')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    toastr.success('{{translate('messages.transaction_saved')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function () {
                        location.href = '{{route('admin.account-transaction.index')}}';
                    }, 2000);
                }
            }
        });
    });
</script>
<script>
    $('#search-form').on('submit', function () {
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.search-account-transaction')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $('#set-rows').html(data.view);
                $('#itemCount').html(data.total);
                $('.page-area').hide();
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });

    $('#reset_btn').click(function(){
            $('#restaurant').val(null).trigger('change');
            $('#deliveryman').val(null).trigger('change');
        })
</script>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Include jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $(document).ready(function () {
        // Initialize the datepicker
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd', // Set the desired date format
            autoclose: true,
        });
    });
</script>
@endpush
