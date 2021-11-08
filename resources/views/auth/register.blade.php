@extends('layouts.app')

@section('title', 'Registration')

@section('body')
    @parent

    <section class="site-section bg-light" id="contact-section" data-aos="fade">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title mb-3">@lang('Registration')</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mb-5">
                    <form method="post" class="p-5 bg-white">
                        @csrf
                        <h2 class="h4 text-black mb-5">Contact Form</h2>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row form-group">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="text-black" for="name">@lang('Name')</label>
                                <input name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="text-black" for="email">@lang('Email')</label>
                                <input name="email" id="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="password">@lang('Password')</label>
                                <input name="password" id="password" type="password" class="form-control">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="password_confirmation">@lang('Password confirmation')</label>
                                <input name="password_confirmation" id="password_confirmation" type="password" class="form-control">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="submit" value="Send Message" class="btn btn-primary btn-md text-white">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
