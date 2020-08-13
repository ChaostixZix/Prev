@extends('admin.layouts.app')

@section('title', __('Packages'))
@section('content')
<div class="nk-block-head">
    <div class="nk-block-between-md g-4">
        <div class="nk-block-head-content">
            <h2 class="nk-block-title fw-normal">{{ __('Packages') }}</h2>
        </div>
        <div class="container">
        <a class="button padded full secondary" href="{{ route('admin-add-package') }}">{{ __('Create package') }}</a></div>
    </div>
</div>
<div class="nk-block-head-content mt-4">
    <div class="nk-block-head-sub"><span>{{ __('All packages') }}</span></div>
</div>

<div class="row">
    @php 
        $free = $website->package_free;
        $planArray = (array) $website->package_free->settings;
        $planActives = count(array_filter($planArray));
    @endphp
        <div class="col-md-3">
             <div class="admin-pricing-box">
                 <p class="admin-pricing-box-title">{{ $free->name }}</p>
                 <div class="admin-pricing-box-content">
                   <div class="status-info success">
                     <p class="status-title bold">{{ __('NULL') }}</p>
                     <ul class="plan-items-list">
                         <li>
                            <span class="label">{{ __('Price') }}</span> - <span class="data">{{ $free->price->annual }}</span>
                        </li>
                         <li>
                            <span class="label">{{ __('Users on Plan') }}</span> - <span class="data">{{$free_count ?? '0'}}</span>
                        </li>
                         <li>
                            <span class="label">Status</span> - <span class="data {{ ($free->status) ? "" : "badge-warning" }}">{{ ($free->status) ? __('active') : __('Inactive') }}</span>
                        </li>
                         <li>
                            <span class="label">{{ __('Features on Plan') }}</span> - <span class="data">{{$planActives}}</span>
                        </li>
                     </ul>
                     <a href="{{ route('admin-packages') .'/edit/'. $free->id }}" class="button primary full mt-3">{{ __('Edit plan') }} <em class="icon ni ni-edit mt-4"></em></a>
                   </div>
                 </div>
            </div>
        </div>
    @foreach ($packages as $package)
        @php
            $planArray = (array) $package->settings;
            $planActives = count(array_filter($planArray));
        @endphp
        <div class="col-md-3">
             <div class="admin-pricing-box">
                 <p class="admin-pricing-box-title">{{ $package->name }}</p>
                 <div class="admin-pricing-box-content">
                   <div class="status-info success">
                     <p class="status-title bold">{{ Carbon\Carbon::parse($package->created_at)->toFormattedDateString()}}</p>
                     <ul class="plan-items-list">
                         <li>
                            <span class="label">{{ __('Price') }}</span> - <span class="data">{{ $package->price->annual }}</span>
                        </li>
                         <li>
                            <span class="label">{{ __('Users on Plan') }}</span> - <span class="data">{{$package->total_package ?? '0'}}</span>
                        </li>
                         <li>
                            <span class="label">Status</span> - <span class="data">{{ ($package->status == 1) ? __('active') : __('Inactive') }}</span>
                        </li>
                         <li>
                            <span class="label">{{ __('Features on Plan') }}</span> - <span class="data">{{$planActives}}</span>
                        </li>
                     </ul>
                     <a href="{{ route('admin-packages') .'/edit/'. $package->id }}" class="button primary full mt-3">{{ __('Edit plan') }} <em class="icon ni ni-edit mt-4"></em></a>
                     <a href="#" data-toggle="modal" data-target="#delete-{{$package->id}}" class="button void full text-danger mt-3">{{ __('Delete') }} <em class="icon ni ni-cross"></em></a>
                   </div>
                 </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" id="delete-{{$package->id}}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
                    <div class="modal-body modal-body-lg">
                        <form action="{{ route('admin-delete-package') }}" method="post">
                            @csrf
                            <input type="hidden" value="{{$package->id}}" name="package_id">
                             <h4 class="bold text-danger">{{ __('TYPE DELETE') }}</h4>
                             <p class="text-danger">{{ __('Note that all users under this plan will be moved to free plan') }}</p>
                             <div class="form-group mt-5">
                                 <input type="text" class="form-control form-control-lg" name="delete" placeholder="{{ __('DELETE') }}" autocomplete="off">
                             </div>
                            <div class="form-group mt-5">
                             <button type="submit" class="btn btn-dark btn-block">{{ __('Submit') }}</button>
                            </div>
                        </form>
                    </div><!-- .modal-body -->
                </div><!-- .modal-content -->
            </div><!-- .modal-dialog -->
        </div>
    @endforeach
</div>
@endsection
