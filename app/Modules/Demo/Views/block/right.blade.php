<?php
use App\Library\PHPDev\FuncLib;
use App\Library\PHPDev\CGlobal;
use App\Library\PHPDev\ThumbImg;
use App\Library\PHPDev\Utility;
?>
<div class="home-right">
    <div class="title-home-right">
        <div class="gach">Tin nổi bật</div>
    </div>
    @foreach($same as $item)
        <div class="list">
            <a href="{{ FuncLib::buildLinkDetailDemo($item->demo_id, $item->demo_title) }}" title="{{ $item->demo_title }}">
                <div class="right-img">
                    @if($item->demo_image != '')
                        <img src="{{ ThumbImg::thumbBaseNormal(CGlobal::FOLDER_DEMO, $item->demo_id, $item->demo_image, 100, 100, '', true, true) }}" alt="" title="{{ $item->demo_title }}">
                    @endif
                </div>
                <div class="right-title">
                    <div class="title-card">{{ stripcslashes($item->demo_title) }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>
