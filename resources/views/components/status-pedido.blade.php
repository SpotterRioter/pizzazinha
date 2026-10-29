@props(['status'])

@php
    $mapa = [
        'pendente' => ['Pendente', 'bg-amber-50 text-amber-700 ring-amber-200', 'bg-amber-500'],
        'em_preparo' => ['Em preparo', 'bg-sky-50 text-sky-700 ring-sky-200', 'bg-sky-500'],
        'saiu_entrega' => ['Saiu para entrega', 'bg-violet-50 text-violet-700 ring-violet-200', 'bg-violet-500'],
        'entregue' => ['Entregue', 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'bg-emerald-500'],
        'cancelado' => ['Cancelado', 'bg-stone-100 text-stone-500 ring-stone-200', 'bg-stone-400'],
    ];
    [$rotulo, $cores, $ponto] = $mapa[$status] ?? [ucfirst($status), 'bg-stone-100 text-stone-600 ring-stone-200', 'bg-stone-400'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-medium ring-1 ring-inset {$cores}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $ponto }}"></span>
    {{ $rotulo }}
</span>
