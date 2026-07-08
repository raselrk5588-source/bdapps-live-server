@extends('admin.layout.default')
@section('title_area')
    Categories
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
                <!-- Add Category Form -->
                <div class="col-md-4">
                    <div class="panel panel-border panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Add New Category</h3>
                        </div>
                        <div class="panel-body">
                            <form action="{{ route('admin.category.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" name="name" class="form-control" required placeholder="Enter category name">
                                </div>
                                <button type="submit" class="btn btn-primary w-md waves-effect waves-light">Add</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Categories List -->
                <div class="col-md-8">
                    <div class="panel panel-border panel-info">
                        <div class="panel-heading">
                            <h3 class="panel-title">Categories List</h3>
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
                                        @forelse($categories as $key => $category)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->created_at ? $category->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                <td>
                                                    <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No categories found.</td>
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
