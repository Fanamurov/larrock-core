@if(isset($allowUpdate))
    <th width="90" class="@if(isset($package->rows['title'])) uk-visible@s @endif"></th>
@endif
@if(isset($allowDestroy))
    <th width="90" class="uk-visible@s"></th>
@endif