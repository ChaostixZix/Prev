@extends('admin.layouts.app')
@section('title', __('Updates'))
@section('Js')
  <script src="{{ url('js/update.js') }}"></script>
@stop
@section('content')
<div class="nk-block-head">
    <div class="nk-block-between-md g-4">
        <div class="nk-block-head-content">
            <h2 class="nk-block-title fw-bold">{{ __('Software Update') }}</h2>
        </div>
    </div>
</div>
  <div class="settings-card">
    <div class="row">
      <div class="col-md-6">
        <div class="container mb-3 mb-lg-0 h-100">
          <div id="update-stats" class="h-100">
            <h5 class="nk-block-title">{{ __('Update stats') }}</h5>
          </div>
        </div>
      </div>
      <div class="col-md-6">
         <div class="container">
          @if (env('APP_MIGRATION') == 'yes')
              <div class="card card-inner card-bordered mb-4">
              <h4 class="nk-block-title fw-bold text-danger">{{ __('Database update reqiured!') }}
              </h4>
              <p class="mt-2">{{ __('Your database need to be upgraded') }}</p>
                <button class="button primary w-100 mt-3" id="migrate-update" route="{{ route('admin-update-migrate') }}">{{ __('Update Database now!') }}</button>
              </div>
          @endif
          <div class="card card-inner card-shadow bdrs-20 mb-4">
          @if (env('UPDATE_AVAILABLE') == 'yes')
            <h5 class="nk-block-title fw-bold text-muted">{{ __('Update available V'.env('UPDATE_VERSION')) }}
            </h5>
            <a href="https://codecanyon.net/item/prev-multi-user-extended-social-profile-for-instagram-twitter-facebook-tiktok-saas/27179684" target="_blank" class="button primary w-100 mt-3" >{{ __('Download update on codecanyon') }}</a>
          @else
            <form action="{{ route('admin-update-check') }}" method="post">
              @csrf
            <h5 class="nk-block-title fw-bold text-muted">{{ __('Check for update') }}
            </h5>
              <button class="button primary w-100 mt-3" type="submit">{{ __('Check for update') }}</button>
            </form>
          @endif
          </div>
          <div class="card card-inner card-shadow bdrs-20 mb-4">
            <form action="{{ route('admin-update-license-code') }}" method="post">
              @csrf
              <div class="form-group">
                <input type="text" name="license_code" class="form-control form-control-lg" placeholder="License key or purchase code" value="{{env('LICENSE_KEY')}}">
              </div>
              <h5>{{env('LICENSE_NAME') .(!empty(env('LICENSE_NAME')) && !empty(env('LICENSE_TYPE')) ? ' | ' : ''). env('LICENSE_TYPE')}}</h5>
              <button class="button primary w-100 mt-3" type="submit">{{ __('Update purchase license') }}</button>
            </form>
          </div>
        <div class="card card-inner card-shadow bdrs-20">
          <h4 class="nk-block-title fw-bold mt-4">{{ __('Manual') }}</h4>
          <p>{{ __('Upload your downloaded zip') }}</p>
          <p class="text-danger">{{ __('Please upload the publish folder in the purchase item.') }}</p>
            <form method="POST" action="{{ route('admin-update-manual') }}" enctype="multipart/form-data">
                @csrf
                   <div class="image-upload pages">
                        <label for="upload">{{ __('Click here or drop an zip') }}</label>
                        <input type="file" id="upload" name="zipFile" class="upload">
                   </div>
                   <button class="button primary w-100 mt-3">{{ __('Update!') }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
