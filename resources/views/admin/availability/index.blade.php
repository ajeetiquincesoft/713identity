@extends('layouts.admin')

@section('content')

<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Available slots</h6>
                </div>
                <div class="col-lg-6 col-5 text-right">
                    <a href="{{ route('unavailability.index') }}" class="btn btn-neutral">Unavailability dates</a>
                    <a href="{{ route('creatavailability') }}" class="btn btn-neutral">Add/update New Time Slot</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow">
                <div class="card-header bg-transparent border-0">
                    <h3 class="text-white mb-0">Time Slotes</h3>
                    @if(session('success'))
                    <div class="alert alert-success">{{session('success')}}</div>
                    @endif
                </div>
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Day Name</th>
                                <th scope="col">Morning Slot</th>
                                <th scope="col">AfterNoon Slot</th>
                                <th scope="col">Evening Slot</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                        @if(count($data)> 0)
                        @foreach($data as $key =>$datas)
                        <tr>
                            <td class="budget">{{ ++$key }}</td>
                            <td>{{ $datas->days }}</td>                            
                            <td>{{ (unserialize($datas->morning_time))?implode(',',unserialize($datas->morning_time)):'' }}</td>
                            <td>{{ (unserialize($datas->afternoon_time))?implode(',',unserialize($datas->afternoon_time)):'' }}</td>
                            <td>{{ (unserialize($datas->evening_time))?implode(',',unserialize($datas->evening_time)):'' }}</td>
                            <td>{{($datas->status==1)?'Active':'InActive' }}</td>

                            <td>
                                <a href="{{ route('availability.edit', $datas->id) }}" class="text-white">
                                    <span class="mr-2"><i class="fa fa-edit" title="Edit User"></i></span>
                                </a>
                               <span> {!! Form::open([
                                          'method'=>'DELETE',
                                          'route' => ['availability.destroy', $datas->id],
                                          'style' => 'display:inline'
                                          ]) !!}
                                            {!! Form::button('<i class="fa fa-trash text-danger" aria-hidden="true"></i>', array(
                                          'type' => 'submit',
                                          'class' => 'btn',
                                          'title' => 'Delete',
                                          'onclick'=>'return confirm("Are you sure about deleting?")'
                                          )) !!}
                                          {!! Form::close() !!}</span>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="7" class="text-center">No Record Found</td></tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="card-footer py-4">

                </div>

            </div>
        </div>
    </div>

</div>

@endsection