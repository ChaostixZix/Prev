<div class="portfolio-item mb-5">
   <div class="row">
     @foreach ($portfolios as $item)
     <div class="col-md-4">
       <div class="portfolio">
        <a data-href href="{{!empty($cm['portfolio']) ? url($user->username.'/'.$cm['portfolio']['title'].'/'.$item->slug) : ''}}" class="portfolio-img">
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