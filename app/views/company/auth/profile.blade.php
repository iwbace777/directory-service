@extends('company.layout')

    @section('custom-styles')
        <style>
          #map-canvas {
            height: 300px;
          }
        </style>
        {{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
    @stop

    @section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Company Profile</span>
				</li>
			</ul>
		</div>
	</div>
    @stop
    
    @section('content')
        @if (isset($alert))
        <div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <p>
                {{ $alert['msg'] }}
            </p>
        </div>
        @endif 
                    
        <div class="tabbable-line">
			<ul class="nav nav-tabs ">
				<li class="{{ $tabNo == 1 ? 'active' : '' }}" id="js-li-tab" data-url="{{ URL::route('company.profile', 1) }}">
					<a href="#tab_1">General Information</a>
				</li>
				<li class="{{ $tabNo == 2 ? 'active' : '' }}" id="js-li-tab" data-url="{{ URL::route('company.profile', 2) }}">
					<a href="#tab_2">Opening Hours</a>
				</li>
				<li class="{{ $tabNo == 3 ? 'active' : '' }}" id="js-li-tab" data-url="{{ URL::route('company.profile', 3) }}">
					<a href="#tab_3">Profile &amp; Cover Photo</a>
				</li>									
				<li class="{{ $tabNo == 4 ? 'active' : '' }}" id="js-li-tab" data-url="{{ URL::route('company.profile', 4) }}">
					<a href="#tab_4">Change Password</a>
				</li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane {{ $tabNo == 1 ? 'active' : '' }}" id="tab_1">
                    <div class="portlet box red">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i>General Information
							</div>
						</div>
						<div class="portlet-body form">
							<form action="{{ URL::route('company.profile.updateCompany') }}" class="form-horizontal form-row-seperated" method="post">
								<div class="form-body">
                                    @foreach ([
                                        'email' => 'Email *',
                                        'name' => 'Name *',
                                        'phone' => 'Phone',
                                        'vat_id' => 'Vat ID *',
                                        'keyword' => 'Keyword',
                                    ] as $key => $value)                                    
                                    @if ($key == 'keyword')
									<div class="form-group">
										<label class="control-label col-md-3">{{ Form::label($key, $value).' : ' }}</label>
										<div class="col-md-8">
											<textarea class="form-control" name="{{ $key }}" rows="3">{{ $company->{$key} }}</textarea>
										</div>
									</div>
									@else
									<div class="form-group">
										<label class="control-label col-md-3">{{ Form::label($key, $value).' : ' }}</label>
										<div class="col-md-8">
											<input type="text" class="form-control" name="{{ $key }}" value="{{ $company->{$key} }}">
										</div>
									</div>
									@endif
									@endforeach
									<div class="form-group" id="js-div-sub-category">
									    <label class="control-label col-md-3">Category</label>
									    <div class="col-md-9">
									        <?php
									        $subCategories = [];
									        foreach ($company->subCategories as $item) {
                                                $subCategories[] = $item->sub_category_id;
                                            } 
									        ?>
									        
									        @foreach ($categories as $category)
									            <div class="col-md-4">
									                <p><b>{{ $category->name }}</b></p>
									                @foreach ($category->subCategories as $subCategory)
									                <p>
									                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}" 
									                        {{ in_array($subCategory->id, $subCategories) ? 'checked' : '' }}>&nbsp;{{ $subCategory->name }}
								                    </p>
									                @endforeach
									            </div>
									        @endforeach
									    </div>
									</div>									
								</div>
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="submit" onclick="return validate()" class="btn green"><i class="fa fa-save"></i> Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane {{ $tabNo == 2 ? 'active' : '' }}" id="tab_2">
					<div class="portlet box red">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i>Opening Hours
							</div>
						</div>
						<div class="portlet-body form">
							<form action="{{ URL::route('company.profile.updateOpeningHours') }}" class="form-horizontal form-row-seperated" method="post">
								<div class="form-body">
                                    @foreach ([
                                        'mon' => 'Mon',
                                        'tue' => 'Tue',
                                        'wed' => 'Wed',
                                        'thu' => 'Thu',
                                        'fri' => 'Fri',
                                        'sat' => 'Sat',
                                        'sun' => 'Sun',
                                    ] as $key => $value)								
									<div class="form-group">
										<label class="control-label col-md-3">{{ Form::label($key, $value).' : ' }}</label>
										<div class="col-md-3">
											<input type="text" placeholder="Start Time" class="form-control" name="{{ $key }}_start" value="{{ $company->opening->{$key.'_start'} }}">
										</div>
										<div class="col-md-3">
											<input type="text" placeholder="End Time" class="form-control" name="{{ $key }}_end" value="{{ $company->opening->{$key.'_end'} }}">
										</div>
									</div>
									@endforeach
								</div>
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="submit" class="btn green"><i class="fa fa-save"></i> Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane {{ $tabNo == 3 ? 'active' : '' }}" id="tab_3">
					<div class="portlet box red">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i>Photos
							</div>
						</div>
						<div class="portlet-body form">
							<form action="{{ URL::route('company.profile.updatePhoto') }}" class="form-horizontal form-row-seperated" method="post" enctype="multipart/form-data">
								<div class="form-body">
                                    @foreach ([
                                        'photo' => 'Photo',
                                    ] as $key => $value)
									<div class="form-group">
										<label class="control-label col-md-3">{{ Form::label($key, $value).' : ' }}</label>
										<div class="col-md-8">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
												<div class="fileinput-new thumbnail" style="width: 120px; height: 120px;">
													<img src="{{ HTTP_COMPANY_PATH.$company->photo }} " alt=""/>
												</div>
												<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 120px; max-height: 120px;"></div>
												<div>
													<span class="btn default btn-file">
													    <span class="fileinput-new">Select image </span>
													    <span class="fileinput-exists">Change </span>
													    <input type="file" name="{{ $key }}">
													</span>
													<a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">Remove </a>
												</div>
											</div>
										</div>
									</div>
									@endforeach
								</div>
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="submit" class="btn green"><i class="fa fa-save"></i> Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane {{ $tabNo == 4 ? 'active' : '' }}" id="tab_4">
					<div class="portlet box red">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i>Change Password
							</div>
						</div>
						<div class="portlet-body form">
							<form action="{{ URL::route('company.profile.changePassword') }}" class="form-horizontal form-row-seperated" method="post">
								<div class="form-body">
                                    @foreach ([
                                        'password_current' => 'Current Password',
                                        'password' => 'New Password',
                                        'password_confirmation' => 'Retype Password',
                                    ] as $key => $value)								
									<div class="form-group">
										<label class="control-label col-md-3">{{ Form::label($key, $value).' : ' }}</label>
										<div class="col-md-6">
											<input type="password" class="form-control" name="{{ $key }}">
										</div>
									</div>
									@endforeach
								</div>
								<div class="form-actions fluid">
									<div class="row">
										<div class="col-md-12 text-center">
											<button type="submit" class="btn green"><i class="fa fa-save"></i> Save</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>				
			</div>
		</div>    
    @stop
    
    @section('custom-scripts')
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}
    <script>

    $(document).ready(function() {
        $("li#js-li-tab").click(function() {
            window.location.href = $(this).attr('data-url');
        });

    });
    function validate() {
        var objList = $("input#js-checkbox-sub-category:checked");
        for (var i = 0; i < objList.length; i++) {
            $("div#js-div-sub-category").append($("<input type='hidden' name='sub_category[]' value=" + objList.eq(i).val() + ">"));
        }
        return true;
    }
    </script>
    @stop
@stop
