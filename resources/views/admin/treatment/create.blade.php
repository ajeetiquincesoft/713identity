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
							 <div class="card-header">
								<div class="row align-items-center">
									<div class="col-12">
										<h3 class="mb-0">Treatment Package</h3>
									</div>
								</div>
							  </div>
							</div>
						<div class="addnewtreatmentpackage">
						<div class="row">
						<div class="col-lg-11">
						<div class="treatmentoption">
							<div class="treatmentpkg">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Title</label>
											<input type="text" name="treamenttitle" class="form-control" value="{{ old('treamenttitle') }}">
											@error('treamenttitle')<div class="text-danger">{{ $message }}*</div>@enderror
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
								<label class="form-control-label" style="width:100%;margin-bottom:18px;">Image</label>
											
												
												<input type="file" name="timage">
										
											@error('timage')<div class="text-danger">{{ $message }}*</div>@enderror
										</div>
									</div>
									<div class="col-lg-4">
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
								<div class="package">
								<div class="row">
								 <div class="col-lg-10">
								 <div class="subpackages">
										<div class="row">
											<div class="col-lg-4 packagetype" id="packagetype">
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
											<div class="col-lg-4">
												<div class="form-group subpackagetype" id="subpackagetype">
													<label class="form-control-label">Subpackage</label>
													<select name="subpackagetype" class="form-control" >
														<option value="">Select Subpackage</option>
															<option value="pf1">f1</option>
															<option value="pf2">f2</option>	
													 </select>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Title</label>
													<input type="text" name="psubtitle" class="form-control" value="{{ old('psubtitle') }}">
													@error('psubtitle')<div class="text-danger">{{ $message }}*</div>@enderror
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Price</label>
													<input type="text" name="psubtitle" class="form-control" value="{{ old('psubtitle') }}">
													@error('psubtitle')<div class="text-danger">{{ $message }}*</div>@enderror
												</div>
											</div>
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Max unit</label>
													<input type="text" name="psubtitle" class="form-control" value="{{ old('psubtitle') }}">
													@error('psubtitle')<div class="text-danger">{{ $message }}*</div>@enderror
												</div>
											</div>
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Min Unit</label>
													<input type="text" name="psubtitle" class="form-control" value="{{ old('psubtitle') }}">
													@error('psubtitle')<div class="text-danger">{{ $message }}*</div>@enderror
												</div>
											</div>
										</div>
									</div>
								   </div>
									<div class="col-lg-2">
										<a href="javascript:void(0);" class="add_button" title="Add field"><img src="/713identity/public/admin_assets/img/add-icon.png"/></a>
									</div>
								</div>
								</div>
							</div>
							</div>
							</div>
							<div class="col-lg-1">
								<a href="javascript:void(0);" class="add_button_new" title="Add field"><img src="/713identity/public/admin_assets/img/add-icon.png"/></a>
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
<script type="text/javascript">
<!---------add new pakage---------->
$(document).ready(function(){
    var maxField = 10; 
    var addButton = $('.add_button_new'); 
    var newpackage = $('.addnewtreatmentpackage'); 
    var x = 1; 
    $(addButton).click(function(){
        if(x < maxField){ 
            x++; 
				$(newpackage).append('<div class="newtpackage"><div class="row"><div class="card-header"><div class="row align-items-center"><div class="col-12"><h3 class="mb-0">Treatment Package</h3></div></div></div></div><div class="treat1"><div class="row"><div class="col-lg-11"><div class="treatmentoption"><div class="treatmentpkg"><div class="row"><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Title</label><input type="text" name="treamenttitle" class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label" style="width:100%;margin-bottom:18px;">Image</label><input type="file" name="timage"></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Status</label><select name="status" class="form-control"><option value="1">Active</option><option value="0">InActive</option></select></div></div></div><div class="package"><div class="row"><div class="col-lg-10"><div class="subpackages"><div class="row"><div class="col-lg-4 packagetype" id="tpack"><div class="form-group"><label class="form-control-label">Package Type</label><select name="packagetype" class="form-control"><option value="">Select Package Type</option><option value="front">Front</option><option value="back">Back</option></select></div></div><div class="col-lg-4"><div class="form-group subpackagetype" id="tsubpackagetype"><label class="form-control-label">Subpackage</label><select name="subpackagetype" class="form-control"><option value="">Select Subpackage</option><option value="pf1">f1</option><option value="pf2">f2</option></select></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Title</label><input type="text" name="psubtitle" class="form-control" value=""></div></div></div><div class="row"><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Price</label><input type="text" name="psubtitle" class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Max unit</label><input type="text" name="psubtitle" class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Min Unit</label><input type="text" name="psubtitle" class="form-control" value=""></div></div></div></div></div><div class="col-lg-2"><a href="javascript:void(0);" class="add_button box'+x+'" box="box'+x+'" title="Add field"><img src="/713identity/public/admin_assets/img/add-icon.png"/></a></div></div></div></div></div></div><div class="col-lg-1"><a href="javascript:void(0);" class="ntpremove_button"><img src="/713identity/public/admin_assets/img/remove-icon.png"/></a></div></div></div></div>');
		
        }
    });
    $(newpackage).on('click', '.ntpremove_button', function(e){
        e.preventDefault();
		$(this).closest(".newtpackage").fadeOut(1000, function(){
			$(this).closest(".newtpackage").remove();
		});
        x--;
    });
});
/* $('#tpack select').on('change', function()
{
   var val= this.value;
	if(val=='front'){
		$("#tsubpackagetype").replaceWith('<div class="form-group" id="tsubpackagetype"><label class="form-control-label">Subpackage</label><select name="subpackagetype" class="form-control" ><option value="">Select Subpackage</option><option value="pf1">f1</option><option value="pf2">f2</option><option value="pf3">f3</option><option value="pf4">f4</option><option value="pf5">f5</option><option value="pf6">f6</option><option value="pf7">f7</option><option value="pf8">f8</option><option value="pf9">f9</option><option value="pf10">f10</option></select></div>');
	}
	if(val=='back'){
		$("#tsubpackagetype").replaceWith('<div class="form-group" id="tsubpackagetype"><label class="form-control-label">Subpackage</label><select name="subpackagetype" class="form-control" ><option value="">Select Subpackage</option><option value="pb1">b1</option><option value="pb2">b2</option><option value="pb3">b3</option><option value="pb4">b4</option><option value="pb5">b5</option><option value="pb6">b6</option><option value="pb7">b7</option><option value="pb8">b8</option><option value="pb9">b9</option><option value="pb10">b10</option></select></div>');
	}
}); */
<!---------add new pakage---------->




$(document).ready(function(){
    var maxField = 10; 
    var addButton = $('.add_button'); 
    var wrapper = $('.package'); 
    var x = 1; 
    $('body').on('click','.add_button',function(){
		var box=$(this).attr('box');
		
        if(x < maxField){ 
            x++; 
			if(box){
				 var addButton = $('.'+box+''); 
			}else{
				var addButton = $('.add_button'); 
			}
			
			$(addButton).closest('.package').append('<div class="delete_row"><div class="row"><div class="col-lg-10"><div class="subpackages"><div class="row"><div class="col-lg-4 packagetype" id="packagetype"><div class="form-group"><label class="form-control-label">Package Type</label><select name="packagetype" class="form-control"><option value="">Select Package Type</option><option value="front">Front</option><option value="back">Back</option></select></div></div><div class="col-lg-4"><div class="form-group subpackagetype" id="subpackagetype"><label class="form-control-label">Subpackage</label><select name="subpackagetype" class="form-control" ><option value="">Select Subpackage</option><option value="pf1">f1</option><option value="pf2">f2</option></select></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Title</label><input type="text" name="psubtitle" class="form-control" value=""></div></div></div><div class="row"><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Price</label><input type="text" name="psubtitle" class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Max unit</label><input type="text" name="psubtitle" class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Min Unit</label><input type="text" name="psubtitle" class="form-control" value=""></div></div></div></div></div><div class="col-lg-2"><a href="javascript:void(0);" class="remove_button"><img src="/713identity/public/admin_assets/img/remove-icon.png"/></a></div></div></div>');
				
		
        }
    });
    $('body').on('click', '.remove_button', function(e){
        e.preventDefault();
		$(this).closest(".delete_row").fadeOut(1000, function(){
			$(this).closest(".delete_row").remove();
		});
        x--;
    });
});

$('body').on('change','.packagetype select', function()
{
    var val= this.value;

	if(val=='front'){
		$(this).parent().parent().parent().parent().parent().find('.subpackagetype').html('<label class="form-control-label">Subpackage</label><select name="subpackagetype" class="form-control" ><option value="">Select Subpackage</option><option value="pf1">f1</option><option value="pf2">f2</option><option value="pf3">f3</option><option value="pf4">f4</option><option value="pf5">f5</option><option value="pf6">f6</option><option value="pf7">f7</option><option value="pf8">f8</option><option value="pf9">f9</option><option value="pf10">f10</option></select>');
	}
	if(val=='back'){
		
			$(this).parent().parent().parent().parent().parent().find('.subpackagetype').html('<label class="form-control-label">Subpackage</label><select name="subpackagetype" class="form-control" ><option value="">Select Subpackage</option><option value="pb1">b1</option><option value="pb2">b2</option><option value="pb3">b3</option><option value="pb4">b4</option><option value="pb5">b5</option><option value="pb6">b6</option><option value="pb7">b7</option><option value="pb8">b8</option><option value="pb9">b9</option><option value="pb10">b10</option></select>');
	}
});

</script>


@stop