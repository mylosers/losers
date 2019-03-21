{{$page}}<br>
@foreach($arr as $v)
    <ul>
        <li>{{$v->id}}</li>
        <li>{{$v->name}}</li>
        <li>{{$v->age}}</li>
    </ul>
@endforeach