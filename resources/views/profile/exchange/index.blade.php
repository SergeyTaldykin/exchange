<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>profile edit data and skills - Bootdey.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: #f7f7ff;
            margin-top:20px;
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid transparent;
            border-radius: .25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
        }
        .me-2 {
            margin-right: .5rem!important;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="main-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="float-right">{{ $pair->getName() }}</h3>
                        <h3>@lang('profile.Trades')</h3>
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Количество</th>
                                <th>Цена</th>
                                <th>Тип</th>
                                <th>Время</th>
                            </tr>
                            </thead>
                            @foreach ($filledOrders as $filledOrder)
                                <tr class="{{ $filledOrder->isBuy() ? 'table-success' : 'table-danger' }}">
                                    <td>{{ $filledOrder->qty }}</td>
                                    <td>{{ $filledOrder->makerOrder->price }}</td>
                                    @if ($filledOrder->isBuy())
                                        <td>BUY</td>
                                    @else
                                        <td>SELL</td>
                                    @endif
                                    <td>{{ $filledOrder->created_at }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3>@lang('profile.Order Book')</h3>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Цена</th>
                                    <th>Количество</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sellLimitOrders as $price => $qty)
                                    <tr>
                                        <td>{{ $price }}</td>
                                        <td>{{ $qty }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr class="hr">

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>Цена</th>
                                <th>Количество</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($buyLimitOrders as $price => $qty)
                                <tr>
                                    <td>{{ $price }}</td>
                                    <td>{{ $qty }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('profile.exchange.order', [$pair]) }}" method="post" id="order-form">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <input type="hidden" name="pair_id" value="{{ $pair->id }}">
                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input operation_type" type="radio" name="operation_type" id="operation_type1" value="{{ \App\Exchange::OPERATION_TYPE_BUY }}" checked>
                                    <label class="form-check-label" for="operation_type1">@lang('profile.BUY')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input operation_type" type="radio" name="operation_type" id="operation_type2" value="{{ \App\Exchange::OPERATION_TYPE_SELL }}">
                                    <label class="form-check-label" for="operation_type2">@lang('profile.SELL')</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input order_type" type="radio" name="order_type" id="order_type1" value="{{ \App\Models\Order::TYPE_LIMIT }}" checked>
                                    <label class="form-check-label" for="order_type1">@lang('profile.LIMIT')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input order_type" type="radio" name="order_type" id="order_type2" value="{{ \App\Models\Order::TYPE_MARKET }}">
                                    <label class="form-check-label" for="order_type2">@lang('profile.MARKET')</label>
                                </div>
                            </div>

                            <div>Доступно: <span id="available-qty">{{ $qtyRight }}</span></div>

                            <div class="form-group">
                                <input name="price" id="price" placeholder="@lang('profile.Price')" class="form-control">
                            </div>

                            <div class="form-group">
                                <input name="qty" id="qty" placeholder="@lang('profile.Qty')" class="form-control">
                            </div>

                            <div class="form-group">
                                <input type="submit" value="@lang('profile.Execute')">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="position: absolute; bottom: 0; width: 98%" class="orders">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-responsive" style="height: 229px;
    overflow-y: scroll;
    overflow-x: hidden;
    margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th>Время</th>
                                    <th>Тип</th>
                                    <th>Цена</th>
                                    <th>Количество</th>
                                    <th>Заполнено</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usersOrders as $order)
                                    <tr class="{{ $order->isBuy() ? 'table-success' : 'table-danger' }}">
                                        <td>{{ $order->created_at->format('H:i:s d-m-Y') }}</td>
                                        <td>{{ $order->isBuy() ? 'BUY' : 'SELL' }}</td>
                                        <td>{{ $order->price }}</td>
                                        <td>{{ $order->qty }}</td>
                                        <td>{{ \App\Models\Balance::format((float)$order->filled) }}</td>
                                        <td><a href="#">Cancel</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        class OrderForm {
            constructor($form, qtyLeft, qtyRight, operationTypeBuyId, operationTypeSellId) {
                this.context = this
                this.$form = $form;
                this.qtyLeft = qtyLeft;
                this.qtyRight = qtyRight;

                this.currentOperationType = this.operationTypeBuyId = operationTypeBuyId;
                this.operationTypeSellId = operationTypeSellId;

                this.$availableQty = $('#available-qty')

                this.$price = $form.find('#price');
                this.$qty = $form.find('#qty');

                this.$operationType = this.$form.find('.operation_type').change($.proxy(this.changeOperationType, this));
                this.$form.submit($.proxy(this.validate, this))
            }

            changeOperationType() {
                this.currentOperationType = parseInt(this.$operationType.filter(":checked").val())
                this.updateDisplayedQty()
            }

            getOrderPrice() {
                return this.$price.val();
            }

            getOrderQty() {
                return this.$qty.val();
            }

            getOperationVolume() {
                if (this.currentOperationType === this.operationTypeBuyId) {
                    return this.getOrderPrice() * this.getOrderQty();
                }

                return this.getOrderQty();
            }

            getQtyByOperationType(operationType) {
                return operationType === this.operationTypeBuyId ? this.qtyRight : this.qtyLeft;
            }

            updateDisplayedQty() {
                return this.$availableQty.text(this.getQtyByOperationType(this.currentOperationType));
            }

            validate($e) {
                if (this.getOperationVolume() > this.getQtyByOperationType(this.currentOperationType)) {
                    $e.preventDefault();
                    return;
                }

            }
        }

        let orderForm = new OrderForm(
            $('#order-form'),
            '{{ $qtyLeft }}',
            '{{ $qtyRight }}',
            {!! \App\Exchange::OPERATION_TYPE_BUY !!},
            {!! \App\Exchange::OPERATION_TYPE_SELL !!}
        );
    });
</script>
</body>
</html>
