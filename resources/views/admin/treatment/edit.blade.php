@extends('layouts.admin')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h6 class="h2 text-white d-inline-block mb-0">Treatment Category</h6>
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
                            <h3 class="mb-0">Edit Treatment Category </h3>
                        </div>
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
                    <form method="post" action="{{ route('treatment.update',$data->id) }}" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}

                        <div class="pl-lg-4">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Name</label>

                                        <input type="text" name="title" class="form-control" value="{{ $data->title }}">
                                        @error('title')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
							
                           <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Category</label>
										
                                        <select name="category" class="form-control">
                                            
                                            @foreach($categories as $key=>$val)
											
											
										<option value="{{$val->id}}" {{( $data->category->id == $val->id)? "selected" :' ' }} >{{$val->name}}</option>
											
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
                                            <option value="1" {{($data->status==1)?"selected":''}}>Active</option>
                                            <option value="0" {{($data->status==0)?"selected":''}}>InActive</option>
                                        </select>

                                        @error('status')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Short description</label>
                                        <textarea id="short_description" name="short_description" class="summernote form-control">{{ $data->short_description }}</textarea>

                                        @error('short_description')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label">content</label>
                                        <textarea id="summernote" name="description" class="summernote form-control">{{ $data->description }}</textarea>

                                        @error('description')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
							<div class="row">
							<div class="col-lg-2"></div>
								<div class="col-lg-4">
									<image  src="{{ $data->img }}" class="img-responsive" width="100%"/>
									
								</div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Upload New Image</label>
											<input type="file" name="treatmentimagenew" style="width: 100%;padding-top: 40px;">
                                        @error('image')<div class="text-danger">{{ $message }}*</div>@enderror
                                    </div>
                                </div>
                            </div>
							
							<!-- treatment option form start--->
							<br/>
							
							<div class="row">
							 <div class="card-header">
								<div class="row align-items-center">
									<div class="col-12">
										<h3 class="mb-0">Treatment Package</h3>
									</div>
								</div>
							  </div>
							</div>
					 @php 
						$counter=1;
					 @endphp
						@foreach($data->TreatmentOption as $T_options)
					
						<div class="addnewtreatmentpackage">
						<div class="row">
						<div class="col-lg-11">
						<div class="treatmentoption">
							<div class="treatmentpkg">
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<label class="form-control-label">Title</label>
											<input type="text" name="box{{ $counter }}[option_title][]" class="form-control" value="{{ $T_options->name }}">
											<input type="hidden" name="box{{ $counter }}[option_id][]" class="form-control" value="{{ $T_options->id }}">
											<input type="hidden" name="boxcounter" value="{{ $counter }}" class="boxcounter">
											
										</div>
									</div>
									<div class="col-lg-6">
									    <div class="row">
												<div class="col-md-6">
												<img src="{{ $T_options->img }}" class="img-responsive" width="100%"/>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-control-label">Update Image</label>
														<input type="file" name="box{{ $counter }}[option_imagenew][]">
													</div>
												</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											<label class="form-control-label">Status</label>
											<select name="box{{ $counter }}[option_status][]" class="form-control">
											<option value="1" {{($T_options->status==1)?"selected":''}}>Active</option>
                                            <option value="0" {{($T_options->status==0)?"selected":''}}>InActive</option>
											</select>
										</div>
									</div>
								</div>
							
							@foreach($T_options->treatmentOptionPackage as $T_optionspackage)
								
								<div class="package">
								<div class="row">
								 <div class="col-lg-10">
								 <div class="subpackages">
										<div class="row">
											<div class="col-lg-4 packagetype" id="packagetype">
												<div class="form-group">
													<label class="form-control-label">Package Type</label>
													 <select name="box{{ $counter }}[option_package_type][]" class="form-control" counter='{{ $counter }}'>
															<option value="">Select Package Type</option>
															<option value="front" {{($T_optionspackage->packagetype=="front")?"selected":''}}>Front</option>
															<option value="back" {{($T_optionspackage->packagetype=="back")?"selected":''}}>Back</option>
													 </select>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="form-group subpackagetype" id="subpackagetype">
													<label class="form-control-label">Subpackage</label>
													<select name="box{{ $counter }}[option_subpackage][]" class="form-control" >
														<option value="">Select Subpackage</option>
														@if($T_optionspackage->packagetype=='front')
															@php
																$tsmalname = $T_optionspackage->small_name;
																$j='';
															@endphp
															
															@for($i = 1; $i<11; $i++)
															@php
																$j = 'f'.$i;
															@endphp
															
																<option value="f{{ $i }}" {{($tsmalname==$j)?"selected":''}} >f{{ $i }}</option>
																	
															@endfor	
														@endif
														@if($T_optionspackage->packagetype=='back')
															@php
																$tsmalname = $T_optionspackage->small_name;
																$j='';
															@endphp
															
															@for($i = 1; $i < 11; $i++)
															@php
																$j = 'b'.$i;
															@endphp
																<option value="b{{ $i }}" {{($tsmalname==$j)?"selected":''}} >b{{ $i }}</option>
																	
															@endfor	
														@endif
													 </select>
												</div>
											</div>
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Title</label>
													<input type="text" name="box{{ $counter }}[option_package_title][]" class="form-control" value="{{ $T_optionspackage->name }}">
													<input type="hidden" name="box{{ $counter }}[option_package_id][]" class="form-control" value="{{ $T_optionspackage->id }}">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Price</label>
													<input type="text" name="box{{ $counter }}[option_package_price][]" class="form-control" value="{{ $T_optionspackage->price }}">
												</div>
											</div>
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Max unit</label>
													<input type="text" name="box{{ $counter }}[option_package_max][]" class="form-control" value="{{ $T_optionspackage->max }}">
												</div>
											</div>
											<div class="col-lg-4">
												<div class="form-group">
													<label class="form-control-label">Min Unit</label>
													<input type="text" name="box{{ $counter }}[option_package_min][]" class="form-control" value="{{ $T_optionspackage->min }}">
												</div>
											</div>
										</div>
									</div>
								   </div>
								   
									<div class="col-lg-2">
										<a href="javascript:void(0);" class="add_button" title="Add field"><img src="{{asset('/admin_assets/img/add-icon.png')}}"/></a>
									</div>
									
								</div>
								</div>
							
							@endforeach	
							
							</div>
							</div>
							</div>
							<div class="col-lg-1">
								<a href="javascript:void(0);" class="add_button_new" title="Add field"><img src="{{asset('/admin_assets/img/add-icon.png')}}"/></a>
							</div>
							</div>
							</div>
							
							@php 
							 $counter=$counter+1;
							 @endphp
							 
							@endforeach
							
							<!--------end of treatmentoption ------->
                            <div class="row">
                                <div class="col-lg-4">
                                    <button type="submit" class="btn btn-dark">Update</button>
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
			$('body').find('.boxcounter').val(x);
				$(newpackage).append('<div class="newtpackage"><div class="row"><div class="card-header"><div class="row align-items-center"><div class="col-12"><h3 class="mb-0">Treatment Package</h3></div></div></div></div><div class="treat1"><div class="row"><div class="col-lg-11"><div class="treatmentoption"><div class="treatmentpkg"><div class="row"><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Title</label><input type="text" name="box'+x+'[option_title][]" class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label" >Image</label><input type="file" name="box'+x+'[option_image][]"></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Status</label><select name="box'+x+'[option_status][]" class="form-control"><option value="1">Active</option><option value="0">InActive</option></select></div></div></div><div class="package"><div class="row"><div class="col-lg-10"><div class="subpackages"><div class="row"><div class="col-lg-4 packagetype" id="tpack"><div class="form-group"><label class="form-control-label">Package Type</label><select name="box'+x+'[option_package_type][]" class="form-control" counter='+x+'><option value="">Select Package Type</option><option value="front">Front</option><option value="back">Back</option></select></div></div><div class="col-lg-4"><div class="form-group subpackagetype" id="tsubpackagetype"><label class="form-control-label">Subpackage</label><select name="box'+x+'[option_subpackage][]"  class="form-control"><option value="">Select Subpackage</option><option value="pf1">f1</option><option value="pf2">f2</option></select></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Title</label><input type="text" name="box'+x+'[option_package_title][]"  class="form-control" value=""></div></div></div><div class="row"><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Price</label><input type="text" name="box'+x+'[option_package_price][]"  class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Max unit</label><input type="text" name="box'+x+'[option_package_max][]"  class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Min Unit</label><input type="text" name="box'+x+'[option_package_min][]"  class="form-control" value=""></div></div></div></div></div><div class="col-lg-2"><a href="javascript:void(0);" class="add_button box'+x+'" box="box'+x+'" boxcounter="'+x+'" title="Add field"><img src="{{asset("/admin_assets/img/add-icon.png")}}"/></a></div></div></div></div></div></div><div class="col-lg-1"><a href="javascript:void(0);" class="ntpremove_button"><img src="{{asset("/admin_assets/img/remove-icon.png")}}"/></a></div></div></div></div>');
		
        }
    });
    $(newpackage).on('click', '.ntpremove_button', function(e){
        e.preventDefault();
		$countervalue = $('body').find('.boxcounter').val();
		$('body').find('.boxcounter').val($countervalue-1);
		$(this).closest(".newtpackage").fadeOut(1000, function(){
			$(this).closest(".newtpackage").remove();
		});
        x--;
    });
});

$(document).ready(function(){
    var maxField = 10; 
    var addButton = $('.add_button'); 
    var wrapper = $('.package'); 
    var x = 1; 
    $('body').on('click','.add_button',function(){
		var box=$(this).attr('box');
		var boxcounter=($(this).attr('boxcounter'))?$(this).attr('boxcounter'):1;
		
        if(x < maxField){ 
            x++; 
			if(box){
				 var addButton = $('.'+box+''); 
			}else{
				var addButton = $('.add_button'); 
			}
			
			
			$(addButton).closest('.package').append('<div class="delete_row"><div class="row"><div class="col-lg-10"><div class="subpackages"><div class="row"><div class="col-lg-4 packagetype" id="packagetype"><div class="form-group"><label class="form-control-label">Package Type</label><select name="box'+boxcounter+'[option_package_type][]" class="form-control" counter='+boxcounter+'><option value="">Select Package Type</option><option value="front">Front</option><option value="back">Back</option></select></div></div><div class="col-lg-4"><div class="form-group subpackagetype" id="subpackagetype"><label class="form-control-label">Subpackage</label><select name="box'+boxcounter+'[option_subpackage][]" class="form-control" ><option value="">Select Subpackage</option><option value="f1">f1</option><option value="f2">f2</option></select></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Title</label><input type="text" name="box'+boxcounter+'[option_package_title][]"  class="form-control" value=""></div></div></div><div class="row"><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Price</label><input type="text" name="box'+boxcounter+'[option_package_price][]"  class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Max unit</label><input type="text" name="box'+boxcounter+'[option_package_max][]"  class="form-control" value=""></div></div><div class="col-lg-4"><div class="form-group"><label class="form-control-label">Min Unit</label><input type="text" name="box'+boxcounter+'[option_package_min][]"  class="form-control" value=""></div></div></div></div></div><div class="col-lg-2"><a href="javascript:void(0);" class="remove_button"><img src="{{asset("/admin_assets/img/remove-icon.png")}}"/></a></div></div></div>');
				
		
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
	var counter=$(this).attr('counter');
	if(val=='front'){
		$(this).parent().parent().parent().parent().parent().find('.subpackagetype').html('<label class="form-control-label">Subpackage</label><select name="box'+counter+'[option_subpackage][]"  class="form-control" ><option value="">Select Subpackage</option><option value="f1">f1</option><option value="f2">f2</option><option value="f3">f3</option><option value="f4">f4</option><option value="f5">f5</option><option value="f6">f6</option><option value="f7">f7</option><option value="f8">f8</option><option value="f9">f9</option><option value="f10">f10</option></select>');
	}
	if(val=='back'){
			$(this).parent().parent().parent().parent().parent().find('.subpackagetype').html('<label class="form-control-label">Subpackage</label><select name="box'+counter+'[option_subpackage][]" class="form-control" ><option value="">Select Subpackage</option><option value="b1">b1</option><option value="b2">b2</option><option value="b3">b3</option><option value="b4">b4</option><option value="b5">b5</option><option value="b6">b6</option><option value="b7">b7</option><option value="8">b8</option><option value="b9">b9</option><option value="b10">b10</option></select>');
	}
});
</script>
@stop