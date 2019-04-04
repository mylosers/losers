@extends('layout.bst')

@section('content')
    <div class="container">
        <table class="table table-striped">
            @foreach($data as $k=>$v)
                <tr>
                    <td>{{$v}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

@section('footer')
    @parent
@endsection