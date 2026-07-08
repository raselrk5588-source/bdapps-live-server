@extends('layout.default')
@section('title_area')
{{$content[0]->installApp->app_name}}
@endsection
@section('main_section')

    <div class="content">
        <div class="container">
            <!-- Start Widget -->
                <div class="row">
                    <div class="col-md-12">
                          <div class="panel panel-border panel-info">
                               <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion-test" href="#collapseOne" class="collapsed">
                                           Schedule SMS Content List
                                        </a>
                                    </h3>
                                </div>
                              <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                <tr>
                                                    <th colspan="4" class="text-center">{{$content[0]->installApp->app_name}}</th>
                                                </tr>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Content</th>
                                                    <th>Sending Date</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    @isset($content)
                                                        @foreach($content as $key=>$value)
                                                            <tr>
                                                                <td class="text-center">{{$sl_counter++}}</td>
                                                                <td width="80%">{{$value->content}}</td>
                                                                <td class="text-center" >{{date("d-m-Y",strtotime($value->content_date))}}</td>
                                                                <td class="text-center" >X</td>
                                                            </tr>
                                                        @endforeach
                                                    @endisset
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-center">{{$content->links()}}</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                          </div>
                    </div>
                </div>
        </div> <!-- container -->
    </div>
@endsection
