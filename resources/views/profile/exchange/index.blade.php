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
                        <form action="{{ route('profile.exchange.order') }}" method="post">
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
                                    <input class="form-check-input" type="radio" name="operation_type" id="inlineRadio1" value="{{ \App\Exchange::OPERATION_TYPE_BUY }}" checked>
                                    <label class="form-check-label" for="inlineRadio1">@lang('profile.BUY')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="operation_type" id="inlineRadio2" value="{{ \App\Exchange::OPERATION_TYPE_SELL }}">
                                    <label class="form-check-label" for="inlineRadio2">@lang('profile.SELL')</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="order_type" id="inlineRadio1" value="{{ \App\Models\Order::TYPE_LIMIT }}" checked>
                                    <label class="form-check-label" for="inlineRadio1">@lang('profile.LIMIT')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="order_type" id="inlineRadio2" value="{{ \App\Models\Order::TYPE_MARKET }}">
                                    <label class="form-check-label" for="inlineRadio2">@lang('profile.MARKET')</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <input name="price" placeholder="@lang('profile.Price')" class="form-control">
                            </div>

                            <div class="form-group">
                                <input name="qty" placeholder="@lang('profile.Qty')" class="form-control">
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
</div>
</body>
</html>
