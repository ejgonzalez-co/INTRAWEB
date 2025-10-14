<div class="accordion" id="{{ $panelId }}">
	<ul class="nav nav-tabs">
      <?php
      
         $nombresPaneles = explode(",", $nombrePanel);
         $paneles = explode(",", $panel);

         for ($i=0; $i < count($paneles); $i++) { ?>
            
            <li class="nav-item">
               <strong><a class="nav-link {{ $i == 0 ? 'active' : '' }}" href="#" data-toggle="collapse" data-target="#{{ $paneles[$i] }}" aria-controls="{{ $paneles[$i] }}" role="collapse" aria-selected="{{ $i === 0 ? 'true' : 'false' }}">{{ $nombresPaneles[$i] }}</a></strong>
            </li>

            <?php
         }
      ?>
	</ul>
  
   {{ $slot }}
</div>
