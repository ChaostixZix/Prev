@extends('layouts.app')
@section('content')
<div class="shadow-new mt-4 section-body">
  <div class="row">
    <div class="col-md-6">
        @if (count($links_limit) > 0 && $package->settings->links)
      <div class="section-head">
        <p>{{ __('Links') }}</p>
        <em class="icon ni ni-notes-alt"></em>
      </div>
      <div class="section-inner">
        <div class="links mb-4 {{($package->settings->links_style) ? "" : __('free')}}">
          <div class="row">
            @foreach ($links_limit as $key)
              {!! General::get_link($key->id, $key->user) !!}
            @endforeach
          </div>
        </div>
      </div>
        @endif
        @if (count($portfolios_limit) > 0 && $package->settings->portfolio)
      <div class="section-head mt-2">
        <p>{{ __('Portfolio') }}</p>
        <em class="icon ni ni-briefcase"></em>
      </div>
      <div class="section-inner">
         <div class="portfolio-item mb-5">
           <div class="row">
            @foreach ($portfolios_limit as $item)
             <div class="col-lg-6">
               <div class="portfolio">
                <a href="{{!empty($cm['portfolio']) ? url($user->username.'/'.$cm['portfolio']['title'].'/'.$item->slug) : ''}}" class="portfolio-img">
                  <img src="{{url('img/user/portfolio/'.$item->image)}}" alt=" " class="portfolio-img">
                </a>
                  <div class="portfolio-wrap"> 
                    <h3 class="portfolio-title">{{ Str::limit($item->settings->name, $limit = 15, $end = '...') }}</h3>
                    <span class="portfolio-subtitle"><?= clean(str_replace('{{title}}', $item->settings->name, Str::limit($item->settings->note, $limit = 30, $end = '...')), 'titles') ?></span>
                  </div>
                </div>
               </div>
             @endforeach
           </div>
         </div>
      </div>
        @endif
    </div>
    <div class="col-md-6">
      <div class="section-head">
        <p>{{ __('About') }}</p>
        <em class="icon ni ni-user-alt"></em>
      </div>
      <div class="section-inner margin">
        <p>{!! clean(Str::limit($user->about, $limit = 300, $end = '...'), 'titles') !!}</p>
      </div>
      <div class="section-about-small mt-4">
        <div class="row">
          <div class="col-lg-6">
            <div class="section-about-small-title">
              <em class="icon ni ni-bulb"></em>
              <p>{{ __('Location') }}</p>
            </div>
            <div class="section-about-small-desc">
              <h5>{{ $user->settings->location ?? '' }}</h5>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="section-about-small-title">
              <em class="icon ni ni-briefcase"></em>
              <p>{{ __('Email') }}</p>
            </div>
            <div class="section-about-small-desc">
              <h5>{{ strtolower($user->email) }}</h5>
            </div>
          </div>
        </div>
      </div>
    @if (count($skills_limits) > 0)
      <div class="section-head mt-6">
        <p>{{ __('Skills') }}</p>
        <em class="icon ni ni-user-award"></em>
      </div>

      <div class="section-inner mt-4">
        @foreach ($skills_limits as $items)
            <div class="skillbar-title"><span>{{ ucfirst($items->name) }}</span></div>
          <div class="skillbar">
            <div class="skillbar-bar" style="width: {{ str_replace('%', '', $items->bar) }}%"></div>
            <div class="skillbar-percent">{{ str_replace('%', '', $items->bar) }}%</div>
          </div> <!-- End Skill Bar -->
        @endforeach
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
