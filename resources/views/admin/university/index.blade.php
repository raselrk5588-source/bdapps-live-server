@extends('admin.layout.default')
@section('title_area')
    Universities
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
                <!-- Add University Form -->
                <div class="col-md-4">
                    <div class="panel panel-border panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add New University</h3>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('admin.university.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">University Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter university name">
                                </div>
                                <button type="submit" class="btn btn-primary w-md waves-effect waves-light">Add</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Universities List -->
                <div class="col-md-8">
                    <div class="panel panel-border panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Universities List</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Added On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($universities as $key => $university)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $university->name }}</td>
                                                <td>{{ $university->created_at ? $university->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                <td>
                                                    <form action="{{ route('admin.university.destroy', $university->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this university?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No universities found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container -->
    </div>

@endsection
