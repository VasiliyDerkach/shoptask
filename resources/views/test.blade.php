{{ date('d.m.Y H:i:s') }}

<h1>
    {{ $title }}
</h1>

@if ($number > 5)
    Ваше число больше 5
@else
    Ваше число меньше либо равно 5
@endif

<ul>
@for ($i = 0; $i < 10; $i++)
    <li>
        {{ $i }}
    </li>
@endfor
</ul>

<ul>
@foreach ($numbers as $number)
    
    <li>
        {{ $number }}
        @if ($loop->first)
            (это первая запись)
        @endif
        @if ($loop->last)
            (это последняя запись)
        @endif
    </li>
@endforeach
</ul>

@empty ($cities)
    Список городов пустой
@endempty

<br>

@isset ($number)
    Переменная number определена
@endisset

<br>

@auth
    Вы авторизованы
@endauth

@guest
    Вы гость
@endguest