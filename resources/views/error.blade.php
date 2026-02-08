@extends('partials.master')

@section('body_class', 'h-100')

@section('title', trans('general.error.'.$code))

@section('body')
    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="error-content">
                        <div class="card mb-0">
                            <div class="card-body text-center pt-5">
                                <h1 class="error-text text-primary">{{$code}}</h1>
                                <h4 class="mt-4">
                                    <i class="fa fa-thumbs-down text-danger"></i> {{trans('general.error.'.$code)}}
                                </h4>
                                <p>{{$message}}</p>
                                <form class="mt-5 mb-5">
                                    <div class="text-center mb-4 mt-4">
                                        <a href="{{ route('home') }}" class="btn btn-primary">
                                            {{ trans('general.go_home') }}
                                        </a>
                                    </div>
                                </form>
                                <div class="text-center">
                                    <p>{!! str_replace([':link', ':year', ':url', ':text'], ['<a class="text-primary" href=":url">:text</a>', date("Y"), 'http://skymaxcontactcenter.com/', 'Skymax Services'], trans('general.copyright')) !!}</p>
                                    <ul class="list-inline">
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0)" class="btn btn-facebook">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0)" class="btn btn-twitter">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0)" class="btn btn-linkedin">
                                                <i class="fa fa-linkedin"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0)" class="btn btn-google-plus">
                                                <i class="fa fa-google-plus"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop