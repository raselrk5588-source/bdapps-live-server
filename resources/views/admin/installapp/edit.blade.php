@extends('admin.layout.default')
@section('title_area')
    Edit Installed App
@endsection
@section('main_section')
    <div class="content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-border panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit App: {{ $app->app_name }}</h3>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('admin.installapp.update', $app->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="form-group">
                                    <label for="app_name">App Name</label>
                                    <input type="text" name="app_name" value="{{ old('app_name', $app->app_name) }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="app_id">App ID</label>
                                    <input type="text" name="app_id" value="{{ old('app_id', $app->app_id) }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" name="password" value="{{ old('password', $app->password) }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="ussd_code">USSD Code</label>
                                    <input type="text" name="ussd_code" value="{{ old('ussd_code', $app->ussd_code) }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="sms_keyword">SMS Keyword</label>
                                    <input type="text" name="sms_keyword" value="{{ old('sms_keyword', $app->sms_keyword) }}" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">-- Select Category --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ $app->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="sms_time">SMS Time</label>
                                    <input type="number" name="sms_time" value="{{ old('sms_time', $app->sms_time) }}" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="sms_time_format">SMS Time Format</label>
                                    <select name="sms_time_format" class="form-control" required>
                                        <option value="1" {{ $app->sms_time_format == 1 ? 'selected' : '' }}>Minutes</option>
                                        <option value="2" {{ $app->sms_time_format == 2 ? 'selected' : '' }}>Hours</option>
                                        <option value="3" {{ $app->sms_time_format == 3 ? 'selected' : '' }}>Days</option>
                                    </select>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary">Update App</button>
                                    <a href="{{ route('admin.installapp.index') }}" class="btn btn-default">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container -->
    </div>
@endsection
