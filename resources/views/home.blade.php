@extends('layouts.app')

@section('content')
<div class="container">
    @forelse($posts as $post) <!-- Pega as informações das noticias e do usuario lá no homeController -->
        @can('read', $post)
            <h1>{{$post->title}}</h1>
            <p>{{$post->description}}</p>
            <br>
            <b>Author: {{$post->user->name}}</b>
            <a href="{{url("/post/$post->id/update")}}">Editar</a>
        @endcan
    @empty
        <p>Nenhum post encontrado</p>

    @endforelse
</div>
@endsection
