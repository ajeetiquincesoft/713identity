@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Available Time Slot</h6>
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
                            <h3 class="mb-0">Add new Available Time Slot </h3>
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
                    <form method="post" action="{{ route('postcreatavailability') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="pl-lg-4">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Days</label>
                                        <select name="days[]" class="form-control">
                                            <option value="{{$data->days}}" selected>{{$data->days}}</option>

                                        </select>

                                        @error('days')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <?php
                            $morning_slots = array('08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30');
                            $morning_slot_selected = ($data->morning_time) ? unserialize($data->morning_time) : [];

                            $afternoon_slots = array('12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30');
                            $afternoon_slot_selected = ($data->afternoon_time) ? unserialize($data->afternoon_time) : [];


                            $evening_slots = array('16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30','20:00','20:30');
                            $evening_slot_selected = ($data->evening_time) ? unserialize($data->evening_time) :[];

                         
                            ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Morning Time</label>
                                        <select name="morning_time[]" class="form-control" multiple>
                                            @foreach($morning_slots as $morning_slot)
                                            <option value="{{$morning_slot}}" <?php echo (in_array($morning_slot, $morning_slot_selected)) ? "selected" : ""; ?>>{{$morning_slot}}</option>
                                            @endforeach


                                        </select>

                                        @error('morning_time')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Afetr Noon Time</label>
                                        <select name="afternoon_time[]" class="form-control" multiple>
                                            @foreach($afternoon_slots as $afternoon_slot)
                                            <option value="{{$afternoon_slot}}" <?php echo (in_array($afternoon_slot, $afternoon_slot_selected)) ? "selected" : ""; ?>>{{$afternoon_slot}}</option>
                                            @endforeach

                                        </select>

                                        @error('morning_time')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Evening Time</label>
                                        <select name="evening_time[]" class="form-control" multiple>
                                        @foreach($evening_slots as $evening_slot)
                                            <option value="{{$evening_slot}}" <?php echo (in_array($evening_slot, $evening_slot_selected)) ? "selected" : ""; ?>>{{$evening_slot}}</option>
                                            @endforeach


                                        </select>

                                        @error('evening_time')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="1" {{($data->status==1)?'selected':''}}>Active</option>
                                            <option value="0" {{($data->status==0)?'selected':''}}>InActive</option>
                                        </select>

                                        @error('status')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>



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