@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Treatment</h6>
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
                            <h3 class="mb-0">Add new Treatment </h3>
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
                    <form method="post" action="{{ route('treatment.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Title</label>

                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                        @error('title')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Category</label>
                                        <select name="category" class="form-control">
                                            <option value="">Category</option>
                                            @foreach($categories as $key=>$val)
                                            <option value="{{$val->id}}">{{$val->name}}</option>
                                            @endforeach
                                        </select>

                                        @error('category')<div class="text-danger">{{ $message }}*</div>@enderror
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

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Short description</label>
                                        <textarea id="short_description" name="short_description" class="summernote form-control"></textarea>

                                        @error('short_description')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Description</label>
                                        <textarea id="summernote" name="description" class="summernote form-control"></textarea>

                                        @error('description')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Image</label>
                                       <input type="file" name="image">
                                        @error('image')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
							
							<!-- treatment option form start--->
							
							<div class="row">
								<div class="col-lg-9"></div>
								<div class="col-lg-3">
									<div class="addtreamentoption btn-dark">Add Treatment Option</div>
								</div>
							</div>
							
							<div class="treatmentoption">
								<div class="row">
								 <div class="card-header">
									<div class="row align-items-center">
										<div class="col-12">
											<h3 class="mb-0">Treatment Option</h3>
										</div>
									</div>
								  </div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label">Title</label>
											<input type="text" name="treamenttitle" class="form-control" value="{{ old('treamenttitle') }}">
											@error('treamenttitle')<div class="text-danger">{{ $message }}*</div>@enderror
										</div>
									</div>
									</div>
									<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label">Image</label>
											<input type="file" name="timage">
											@error('timage')<div class="text-danger">{{ $message }}*</div>@enderror
										</div>
									</div>
								</div>
								<div class="row">
								 <div class="card-header">
									<div class="row align-items-center">
										<div class="col-12">
											<h3 class="mb-0">Package</h3>
										</div>
									</div>
								  </div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label">Title</label>
											<input type="text" name="packagename" class="form-control" value="{{ old('packagename') }}">
											@error('packagename')<div class="text-danger">{{ $message }}*</div>@enderror
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label class="form-control-label">Package Type</label>
											 <select name="packagetype" class="form-control">
													<option value="">Select Package Type</option>
													<option value="front">Front</option>
													<option value="back">Back</option>
											 </select>

                                        @error('status')<div class="text-danger">{{ $message }}*</div>@enderror
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Sub Package</label>
											 <input type="text" name="subpackage" class="form-control" value="{{ old('subpackage') }}">
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label class="form-control-label">Price</label>
											 <input type="text" name="price" class="form-control" value="{{ old('price') }}">
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label class="form-control-label">Unit<small>(hint: max-13 and min-10)</small></label>
											 <input type="text" name="subpackage" class="form-control" value="{{ old('subpackage') }}">
										</div>
									</div>
									<div class="col-lg-2">
										<a href="javascript:void(0);" class="add_button" title="Add field"><img src="add-icon.png"/></a>
									</div>
								</div>
							</div>
							<!--------end of treatmentoption ------->
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
jQuery(document).ready(function(){
  jQuery(".addtreamentoption").click(function(){
    alert("The paragraph was clicked.");
  });
});
</script>
@stop