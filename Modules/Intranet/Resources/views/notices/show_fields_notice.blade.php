<div class="card-content">

    <div v-if="dataShow.image_banner == 'imagen_default_noticias.png'" class="card-img">
        <img width="200" :src="'{{ asset('assets/img') }}/'+dataShow.image_banner" alt="" style="margin: auto; height: inherit; width: auto; opacity: 0.7;">
    </div>
    <div v-else class="card-img" style="height: auto;">
        <img width="200" :src="'{{ asset('storage') }}/'+dataShow.image_banner" alt="">
    </div>
    <div class="card-desc">
        <h4 class="category">@{{dataShow.category?.name}}</h4>

        <h3>@{{dataShow.title}}</h3>
        <p>@{{dataShow.date_start}}</p>

        <p style="white-space: break-spaces;" v-html='dataShow.content'></p>
        <small><i class="fa fa-eye">@{{ dataShow.views }}</i></small>

        <div v-show="dataShow.annexes" class="mt-2">
            <viewer-attachement :list="dataShow.annexes" :key="dataShow.annexes"></viewer-attachement>
        </div>

    </div>
</div>

