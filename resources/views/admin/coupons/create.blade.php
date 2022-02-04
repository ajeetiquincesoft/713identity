@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Coupons</h6>
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
                            <h3 class="mb-0">Add new Coupon </h3>
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
                                        <label class="form-control-label">Titlle</label>
                                        <input type="text" class="form-control" name="title" placeholder="" required>

                                        @error('title')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Coupon For</label>
                                        <select name="coupon_for" class="form-control" id="coupon_for" required>
                                            <option value="all">All</option>
                                            <option value="specific">Specific treatment</option>
                                        </select>

                                        @error('coupon_for')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row treatment_box" style="display: none;">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Select Treatments</label>
                                        <select name="treatment" class="form-control">
                                            <option value="">Select Treatment</option>
                                            @foreach($treatments as $treatment)
                                            <option value="{{$treatment->id}}">{{$treatment->title}}</option>
                                            @endforeach

                                        </select>

                                        @error('treatment')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Coupon Code</label>
                                        <input type="text" class="form-control" name="code" placeholder="Ex:xzy123456" required>

                                        @error('code')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Discount(%)</label>
                                        <input type="number" class="form-control" name="discount" required>

                                        @error('discount')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Expiry Date</label>
                                        <input type="text" class="form-control" name="expiry_date" placeholder="dd-mm-yyyy" required>

                                        @error('expiry_date')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
@section('scripts')
<script>
    $('#coupon_for').on('change', function() {
        if ($(this).val() === 'specific') {
            $('.treatment_box').show();
        } else {
            $('.treatment_box').hide();
        }

    });
</script>
@stop