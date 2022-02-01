@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">UnAvailable  Time Slot</h6>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="mb-0">Add new UnAvailable Time Slot </h3>
                        </div>
                        <!--                        <div class="col-4 text-right">-->
                        <!--                            <a href="#!" class="btn btn-sm btn-primary">Settings</a>-->
                        <!--                        </div>-->
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form method="post" action="{{ route('unavailability.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="pl-lg-4">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Date</label>

                                        <input type="text" name="date" class="form-control" value="{{ old('date') }}" placeholder="dd-mm-YYYY">
                                        @error('date')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row full_day">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Full day Close</label>
                                        <select name="full_day" class="form-control" id="full_day">
                                            <option value="0">No</option>
                                            <option value="1">yes</option>
                                        </select>

                                        @error('status')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row time">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Morning Time</label>
                                        <select name="time[]" class="form-control" multiple>
                                            @foreach($time_slotes as $time)
                                            <option value="{{$time}}">{{$time}}</option>
                                            @endforeach


                                        </select>

                                        @error('time')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>




                            <!-- <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>

                                        @error('status')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div> -->



                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="submit" class="btn btn-dark">Submit</button>
                                </div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $("#full_day").on('change', function() {
        if ($(this).val() == 1) {
            $(".time").hide();
        } else {
            $(".time").show();
        }
    });
</script>
@stop