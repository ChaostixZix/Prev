<div class="links mb-5">
   <div class="row">
     @foreach ($links as $key)
     	<?= General::get_link($key->id, $key->user) ?>
     @endforeach
   </div>
 </div>