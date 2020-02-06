@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading" style="font-size:20px">Teacher Registration</div>
                <div class="panel-body">
                    <form class="form-horizontal" style="padding:0px 15px 0px 15px " method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('id_number') ? ' has-error' : '' }}">
                            <label for="id_number" class="control-label">ID Number <span style="color:red">*</span></label>
                            <input type="hidden" name="name" value="1234567890" />
                            <input id="id_number" type="number" class="form-control" name="id_number" value="{{ old('id_number') }}" required autofocus>

                            @if ($errors->has('id_number'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('id_number') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="control-label">Last Name <span style="color:red">*</span></label>

                            <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus>

                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="control-label">First Name <span style="color:red">*</span></label>

                            <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>

                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                                
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('degree') ? ' has-error' : '' }}">
                            <label for="degree" class="control-label">Highest Degree <small>Only the acronym. This will be displayed after your name.</small> <span style="color:red">*</span></label>

                            <input id="degree" type="text" class="form-control" name="degree" value="{{ old('degree') }}" required autofocus>

                            @if ($errors->has('degree'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('degree') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('cp_number') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Cellphone Number <span style="color:red">*</span></label>

                                <input id="password" type="text" size="11" class="form-control" name="cp_number" required value="{{ old('cp_number') }}" >

                                @if ($errors->has('cp_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cp_number') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }} ">
                            <label for="username" class="control-label">Username <span style="color:red">*</span></label>

                            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>

                            @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="control-label">Password <span style="color:red">*</span></label>

                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>

                        

                        <div class="form-group">
                            <label for="password-confirm" class="control-label">Confirm Password <span style="color:red">*</span></label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                                <button type="submit" class="btn btn-success" style="width:120px">
                                    Register
                                </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
