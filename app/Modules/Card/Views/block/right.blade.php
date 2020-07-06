<div class="home-right">
    <div class="title-home-right">
        <div class="gach">Tin nổi bật</div>
    </div>
   @if(isset($item) )
        @foreach($card as $item)
            <div class="list">
                <a href="{{ route('card.show', $item->card_id) }}" title="{{ $item->card_title }}">
                    <div class="right-img">
                        <img id="img" class="thumb" src="{{ URL::asset('uploads/photo/img/' . $item->card_image) }}">
                    </div>
                    <div class="right-title">
                        <div class="title-card">{!! stripcslashes($item['card_title']) !!}</div>
                    </div>
                </a>
            </div>
        @endforeach
    @endif
</div>
