@props(['items' => null]) <!-- Props quita de attributes items para que no se mergeen y se vean en la etiqueta a -->

@foreach ($items as $item)
    <a href="{{ $item->link }}" {{ $attributes->merge(['class' => 'font-medium']) }}>{{ $item->label }}</a>
@endforeach
