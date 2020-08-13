@extends('admin.layouts.app')

@section('title', __('Create support'))
@section('content')
<div class="container wide-sm mt-5">
   <div class="nk-content-inner">
      <div class="nk-content-body">
         <div class="nk-content-wrap">
            <div class="nk-block-head nk-block-head-lg wide-sm m-auto text-left">
               <div class="nk-block-head-content card-shadow card card-inner bdrs-20">
                  <h5 class="nk-block-title fw-bold">{{ __('Create support ticket to your user') }}</h5>
               </div>
            </div>
            <!-- .nk-block-head -->
            <div class="nk-block mb-3">
               <div class="card card-shadow bdrs-20">
                  <div class="card-inner">
                     <form action="{{ route('admin.support.create') }}" method="post" class="form-contact">
                      @csrf
                        <div class="row g-4">
                           <div class="col-md-6">
                              <div class="custom-control custom-radio">
                                 <input type="radio" class="custom-control-input" name="type" id="type-general" checked="" value="enquiry">
                                 <label class="custom-control-label" for="type-general">{{ __('A general enquiry') }}</label>
                              </div>
                           </div>
                           <!-- .col -->
                           <div class="col-md-6">
                              <div class="custom-control custom-radio">
                                 <input type="radio" class="custom-control-input" name="type" id="type-problem" value="help">
                                 <label class="custom-control-label" for="type-problem">{{ __('I have a problem need help') }}</label>
                              </div>
                           </div>
                           <!-- .col -->
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="form-label"><span>{{ __('Category') }}</span></label>
                                 <div class="form-control-wrap">
                                    <select class="form-select" data-search="off" data-ui="lg" name="category">
                                       <option value="general">{{ __(' General ') }}</option>
                                       <option value="billing">{{ __('Billing') }}</option>
                                       <option value="technical">{{ __('Technical') }}</option>
                                   </select>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="form-label"><span>{{ __('Priority') }}</span></label>
                                 <div class="form-control-wrap">
                                    <select class="form-select" data-search="off" data-ui="lg" name="priority">
                                       <option value="normal">{{ __('Normal') }}</option>
                                       <option value="important">{{ __('Important') }}</option>
                                       <option value="high">{{ __('High Piority') }}</option>
                                   </select>
                                 </div>
                              </div>
                           </div>
                           <!-- .col -->
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label class="form-label"><span>{{ __('User') }}</span></label>
                                 <div class="form-control-wrap">
                                    <select class="form-select" data-search="on" data-ui="lg" name="user">
                                       @foreach ($users as $item)
                                           <option value="{{ strtolower($item->id) }}">{{ strtolower($item->username) }}</option>
                                       @endforeach
                                   </select>
                                 </div>
                              </div>
                           </div>
                           @guest
                           <div class="col-12">
                              <div class="form-group">
                                 <label class="form-label">{{ __('We need your email') }}</label>
                                 <div class="form-control-wrap">
                                    <input type="text" class="form-control form-control-lg" placeholder="{{ __('enter your email') }}" name="problem">
                                 </div>
                              </div>
                           </div>
                           @endguest
                           <!-- .col -->
                           <!-- .col -->
                           <div class="col-12">
                              <div class="form-group">
                                 <label class="form-label">{{ __('Describe the problem you have') }}</label>
                                 <div class="form-control-wrap">
                                    <input type="text" class="form-control form-control-lg" placeholder="{{ __('Write your problem...') }}" name="problem">
                                 </div>
                              </div>
                           </div>
                           <!-- .col -->
                           <div class="col-12">
                              <div class="form-group">
                                 <label class="form-label"><span>{{ __('Give us the details') }}</span><em class="icon ni ni-info text-gray"></em></label>
                                 <div class="form-control-wrap">
                                    <div class="form-editor-custom">
                                       <textarea class="form-control form-control-lg no-resize" placeholder="{{ __('Write your message') }}" name="message"></textarea>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <!-- .col -->
                           <div class="col-12">
                              <button type="submit" class="button w-100 primary">{{ __('Create') }}</button>
                           </div>
                           <!-- .col -->
                        </div>
                        <!-- .row -->
                     </form>
                     <!-- .form-contact -->
                  </div>
                  <!-- .card-inner -->
               </div>
               <!-- .card -->
            </div>
            <!-- .nk-block -->
         </div>
      </div>
   </div>
</div>
@endsection
