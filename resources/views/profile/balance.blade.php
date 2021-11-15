@extends('profile.layout')

@section('title', 'Balance')

@section('body')
    @parent

    <div class="card">
        <div class="card-body">
            <ul>
                @foreach ($balances as $balance)
                    <li>{{ $balance->asset->name }}: {{ $balance->volume }}</li>
                @endforeach
            </ul>

            <form id="profile" action="{{ route('profile.balance.index') }}" method="post">
                @csrf

                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Asset type</h6>
                    </div>

                    <div class="col-sm-9 text-secondary">
                        <select name="asset_id" class="form-control">
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Amount</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input id="volume" name="volume" class="form-control" value="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                        <input type="submit" class="btn btn-primary px-4" value="Add balance">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
