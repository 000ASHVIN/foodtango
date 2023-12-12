@extends('layouts.admin.app')

@section('title',translate('messages.Payment to Restaurant'))

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
    .checkbox-container, .new-checkbox-container {
        position: relative;
        padding-left: 30px;
    }
    .checkbox, .new-checkbox {
        position: absolute;
        top: 0;
        left: 0;
        width: 20px;
        height: 20px;
        opacity: 0;
    }
    .custom-checkbox, .new-custom-checkbox {
        top: 0;
        left: 0;
        width: 20px;
        height: 20px;
        background-color: #D9D9D9;
    }
    .checkbox:checked + .custom-checkbox {
        background-color: black;
    }
    .new-checkbox:checked + .new-custom-checkbox {
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
    .margin-right{
        margin-right: 70px;
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
                {{ translate('Restuant Payments') }}
            </span>
        </h1>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <form method='get' id="search_form">
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
                            <select id="restaurant_id" name="restaurant_id" data-placeholder="{{translate('messages.select restaurant')}}" onchange="getAccountData('{{url('/')}}/admin/restaurant/get-account-data/',this.value,'restaurant')" class="form-control h--48px" title="Select Restaurant">
                                @if (request()->has('restaurant_id') && !empty(request()->get('restaurant_id')) && $restaurant)
                                    <option value="{{ request()->get('restaurant_id') }}" selected="selected">{{ $restaurant->name }}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                   <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="start_date">{{ translate('messages.select start date') }}</label>
                            <input class="form-control h--48px datepicker" type="text" name="start_date" id="start_date" maxlength="191" value="{{ request()->has('start_date') ? request()->get('start_date') : '' }}" placeholder="{{ translate('Ex : 2023-2-20') }}" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="end_date">{{ translate('messages.select end date') }}</label>
                            <input class="form-control h--48px datepicker" type="text" name="end_date" id="end_date" maxlength="191" value="{{ request()->has('end_date') ? request()->get('end_date') : '' }}" placeholder="{{ translate('Ex : 2024-12-20') }}" autocomplete="off">
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
                            <input class="form-control h--48px" type="text" name="method" id="method" value="{{ request()->has('method') ? request()->get('method') : '' }}" maxlength="191" placeholder="{{ translate('Ex : Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="ref">{{translate('messages.reference')}}<span class="input-label-secondary"></span></label>
                            <input  class="form-control h--48px" type="text" name="ref" id="ref" value="{{ request()->has('ref') ? request()->get('ref') : '' }}" maxlength="191" placeholder="{{ translate('Ex : Collect Cash') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="input-label" for="amount">{{translate('messages.payment by')}}<span class="input-label-secondary"></span></label>
                            <select name="payment_by" id="payment_by" class="form-control h--48px select2">
                                <option value="">Select Payment by</option>
                                @foreach ($admins as $admin_user)
                                    <option value="{{ $admin_user->id }}" {{ request()->has('payment_by') && request()->get('payment_by') == $admin_user->id ? 'selected' : '' }}>{{ $admin_user->f_name }} {{ $admin_user->l_name }}</option>
                                @endforeach
                            </select>
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
                <div class="col-12 text-right">
                    <button type="submit" class="btn btn-secondary">Get Orders</button>
                </div>
            </form>
            <div class="">
                {{-- <div class="card-header py-2 border-0">
                    <div class="search--button-wrapper">
                       <h3 class="card-title">
                        </h3> 
                        <!-- Static Search Form -->
                        <form action="javascript:" id="search-form" class="my-2 ml-auto mr-sm-2 mr-xl-4 ml-sm-auto flex-grow-1 flex-grow-sm-0">
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ translate('Search by Reference') }}" aria-label="Search" required="">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div> --}}
                <div>
                    <hr>
                        <div class="mt-3">
                            <span class="span-color">Selected <span id="selectd-orders">0</span> Orders</span>
                        </div>
                        <div class="mt-3">
                                <span class="span-color">Order total payment: </span>
                               <span class="span-color" id="order-total">0</span>
                        </div>
                        <div class="mt-2">
                                <span class="span-color">Total service fees: </span>
                               <span class="span-color" id="order-fee">0</span>
                        </div>
                        <div class="mt-3">
                            <span class="span-color">Payment to Restaurant: </span>
                            <span class="span-color" id="pay-to-restaurant">0</span>
                            <div class="col-12 text-right">
                              <a href="{{ route('admin.payments-transaction.index') }}"> <button type="submit" class="btn btn-secondary mr-1">{{translate('messages.reset')}}</button></a>
                              <a href=""><button type="button" id="submit_btn" class="btn btn-secondary margin-right">{{translate('messages.conform payment:')}}</button></a>
                            </div>
                            {{-- <div class="">
                                <a href="{{ route('admin.payments-transaction.index') }}" class="btn btn--reset color">{{translate('messages.reset')}}</a>
                                <button type="button" id="submit_btn" class="btn btn--reset color ml-3 mr-5">{{translate('messages.confirm payment:')}}</button>
                            </div> --}}
                        </div>
                        <hr>
                </div>
                <div class="card-body p-0">
                    <div>
                        <div class="alert alert-danger" id="error-message" style="display: none;"></div>
                        <div class="alert alert-success" id="success-message" style="display: none;"></div>
                    </div>
                    <div class="table-responsive">
                        <div class="table-container"style="overflow-x:auto;">
                            <table id="datatable"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-transform table-width">
                            <thead class="">
                                <tr class="thead-light">
                                    <th>
                                        <label class="checkbox-container">
                                            <input type="checkbox" id="check-all-orders" class="" data-checked="0">
                                            {{-- <div class="new-custom-checkbox"></div> --}}
                                        </label>
                                    </th>
                                    <th>{{ translate('messages.Order id') }}</th>
                                    <th>{{ translate('messages.order total') }}</th>
                                    <th>{{ translate('messages.order commission') }}</th>
                                    <th>{{ translate('messages.Your payment') }}</th>
                                    {{-- <th>{{translate('messages.service fee')}}</th> --}}
                                    <th>{{translate('messages.date')}}</th>
                                    <th>{{translate('messages.order status')}}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows" class="">
                                @foreach($orders as $k => $order)
                                    @if ($order->payment_to_restaurant == 0)
                                        <tr class="thead-light">
                                            {{-- Your existing code --}}
                                            <th>
                                                <label class="new-checkbox-container">
                                                    <input type="checkbox" name="order[{{ $order->id }}]" class="order-checkbox order-{{ $order->id }}" data-id="{{ $order->id }}" data-total="{{ $order->order_amount }}" data-fee="{{ $order->delivery_charge }}">
                                                </label>
                                            </th>
                                            <td>
                                                <a href="{{ route('admin.order.details', ['id' => $order->id]) }}">{{ $order->id }}</a>
                                            </td>
                                            <td>{{ $order->order_amount }}</td>
                                            <td>{{ $order->order_amount * 0.12 }}</td>
                                            <td>{{ $order->order_amount - ($order->order_amount * 0.12) }}</td>
                                            {{-- <td>{{ $order->delivery_charge }}</td> --}}
                                            <td>{{ Carbon\Carbon::parse($order->created_at)->format('d-m-Y') }}</td>
                                            <td>{{ $order->order_status }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            
                            
                        </table>
                        </div>
                        
                        @if(count($orders) === 0)
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
        // $.get({
        //         url: route+data_id,
        //         dataType: 'json',
        //         success: function (data) {
        //             $('#account_info').html('({{translate('messages.cash_in_hand')}}: '+data.cash_in_hand+' {{translate('messages.earning_balance')}}: '+data.earning_balance+')');
        //         },
        //     });
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
{{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}

<!-- Include jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': `{{ csrf_token() }}`
        }
    });
    $(document).ready(function () {
        // Initialize the datepicker
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd', // Set the desired date format
            autoclose: true,
        });

        $('#check-all-orders').click(function(event) {
            var isChecked = $(this).data('checked');
            if(isChecked) {
                $('.order-checkbox').prop('checked', false);
                $(this).data('checked', 0);
            } else {
                $('.order-checkbox').prop('checked', true);
                $(this).data('checked', 1);
            }
        })
        $('input[type=checkbox]').click(function() {
            var selectedCheckboxes = $('.order-checkbox:checked');
            $('#selectd-orders').html(selectedCheckboxes.length)
            var orderTotal = orderFee = 0;
            selectedCheckboxes.each(function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var total = checkbox.data('total');
                var fee = checkbox.data('fee');

                orderTotal += total;
                orderFee += fee;
            });
            $('#order-total').html(orderTotal)
            var orderTotal12Percent = orderTotal * 0.12;
            $('#pay-to-restaurant').html(orderTotal12Percent);
            var orderTotalAfterDiscount = orderTotal - (orderTotal * 0.12);
             $('#order-fee').html(orderTotalAfterDiscount);
        })

        $('#restaurant_id').select2({
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

        $('#submit_btn').click(function() {
            var data = [];
            var selectedCheckboxes = $('.order-checkbox:checked');
            var selectedCheckboxArray = [];

            selectedCheckboxes.each(function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                selectedCheckboxArray.push(id);
            });
            $('#error-message').hide();
            $('#success-message').hide();

            let formData = {};
            $.each($('#search_form').serializeArray(), function(i, field) {
                formData[field.name] = field.value;
            });

            $.ajax({
                url: "{{ route('admin.payments-transaction.confirm') }}",
                method: "POST",
                "_token": "{{ csrf_token() }}",
                data: {
                    order_ids: selectedCheckboxArray,
                    form_data: formData
                },
                // beforeSend: function() {
                //     me.attr('disabled', true);
                //     me.html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
                // },
                success: function(data) {
                    if (data.status == 'success') {
                        $('#success-message').show();
                        $('#success-message').html('Payment done successfully')

                        setTimeout(() => {
                            $('#success-message').hide();
                        }, 3000);
                        window.location.reload();
                    }
                },        
                error: function(error) {
                    $('#error-message').show();
                    $('#error-message').html(error.responseJSON.message)
                    console.log('Something went wrong')
                    setTimeout(() => {
                        $('#error-message').hide();
                    }, 3000);
                },
                // complete: function() {
                //     me.attr('disabled', false);
                //     me.html('Submit');
                // }
            });
        })
    });
</script>
@endpush
