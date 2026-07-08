@extends('admin.layout.default')
@section('title_area')
    Installed Apps
@endsection
@section('main_section')

    <div class="content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-border panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Installed Apps List</h3>
                        </div>
                        <div class="panel-body">
                            <!-- Search Form -->
                            <form action="{{ route('admin.installapp.index') }}" method="GET" class="form-inline mb-3 m-b-10" style="margin-bottom: 15px;">
                                <div class="form-group">
                                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Search by App Name or User Name">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                                @if(!empty($search))
                                    <a href="{{ route('admin.installapp.index') }}" class="btn btn-default">Clear</a>
                                @endif
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User</th>
                                            <th>App Name</th>
                                            <th>App ID</th>
                                            <th>Category</th>
                                            <th>USSD Code</th>
                                            <th>SMS Keyword</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($apps as $key => $app)
                                            <tr>
                                                <td>{{ $apps->firstItem() + $key }}</td>
                                                <td>{{ optional($app->user)->name ?? 'Unknown User' }} ({{ optional($app->user)->phone }})</td>
                                                <td>{{ $app->app_name }}</td>
                                                <td>{{ $app->app_id }}</td>
                                                <td>{{ optional($app->category)->name ?? 'N/A' }}</td>
                                                <td>{{ $app->ussd_code ?? 'N/A' }}</td>
                                                <td>{{ $app->sms_keyword ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('admin.installapp.edit', $app->id) }}" class="btn btn-sm btn-info" title="Edit App"><i class="fa fa-edit"></i></a>
                                                    <form action="{{ route('admin.installapp.destroy', $app->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this app?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete App"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No installed apps found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination -->
                            <div class="text-center">
                                {{ $apps->appends(['search' => $search])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container -->
    </div>

@endsection
