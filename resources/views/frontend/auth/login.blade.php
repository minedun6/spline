@extends('frontend.layouts.app')

@section('content')
   
   {{ Form::open(['route' => 'frontend.auth.login', 'class' => 'login-form']) }}
               <img src="/img/logo.png" alt="logo" class="logo-default" style="
                    -webkit-filter: invert(.8);
                    filter: invert(.8);
                    margin: auto;
                    width: 128px;
                    display: block;
                " />
                <h3 class="form-title">Se connecter</h3>
                @if (count($errors))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Username</label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        {{ Form::input('email', 'email', null, ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('validation.attributes.frontend.email')]) }}
                </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        {{ Form::input('password', 'password', null, ['class' => 'form-control placeholder-no-fix', 'placeholder' => trans('validation.attributes.frontend.password')]) }}
                </div>
                </div>
                <div class="form-actions">
                    <label class="checkbox">
                        {{ Form::checkbox('remember') }} {{ trans('labels.frontend.auth.remember_me') }} </label>
                    {{ Form::submit(trans('labels.frontend.auth.login_button'), ['class' => 'btn green pull-right']) }}  
                </div>
                {{-- <div class="forget-password">
                    <h4>Forgot your password ?</h4>
                    <p> no worries, click
                        <a href="javascript:;" id="forget-password"> here </a> to reset your password. </p>
                </div> --}}
               
            {{ Form::close() }}

@endsection