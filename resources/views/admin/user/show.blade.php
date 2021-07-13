@extends('admin.layout.layout',['title'=> 'Show User'])
@section('css')
@endsection
@section('content')
    <div class="row-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="grid simple">
                    <div class="grid-title">
                        <h4>Show <span class="semi-bold">user</span></h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="grid-body">

                        <div class="margin-bottom-20">

                            @if($user->image)
                                <img src="{{ asset('uploads/user/'.$user->image) }}" width="100"
                                     class="center-block margin-bottom-20">
                            @else
                                <img src="{{ asset('uploads/user/avatar.png') }}" width="100"
                                     class="center-block margin-bottom-20">
                            @endif
                            <a class="btn btn-primary btn-small center-block" style="display: block;width: 75px;"
                               href="{{route('users.edit', [$role->name, $user->id])}}"><i
                                    class="fa fa-pencil"></i> Edit </a>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item"><span class="label label-primary">#ID</span> {{$user->id}}
                            </li>
                            <li class="list-group-item"><span class="label label-primary">Name</span> {{$user->name}}
                            </li>
                            <li class="list-group-item"><span class="label label-primary">Phone</span> {{$user->phone}}
                            </li>
                            <li class="list-group-item"><span class="label label-primary">Email</span> {{$user->email}}
                            </li>
                            <li class="list-group-item"><span
                                    class="label label-primary">Address</span> {{$user->address}}</li>
                            <li class="list-group-item"><span
                                    class="label label-primary">Country</span> {{ optional($user->country)->name}}
                            @if($role->name == 'STUDENT')
                                <li class="list-group-item"><span
                                        class="label label-primary">Level</span> {{ optional($user->level)->name}}
                                </li>
                            @endif
                            @if($role->name != 'EDITOR')
                                <li class="list-group-item"><span
                                        class="label label-primary">School</span> {{$user->school}}</li>
                            @endif
                            <li class="list-group-item"><span
                                    class="label label-primary">Created at</span> {{ $user->created_at }}</li>

                        </ul>
                    </div>
                </div>
            </div>
            @if($role->name == 'STUDENT')
                <div class="col-md-4">
                    <div class="grid simple">
                        <div class="grid-title">
                            <h4>Parent</h4>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                        </div>
                        <div class="grid-body">
                            <ul class="list-group">
                                <li class="list-group-item"><span
                                        class="label label-primary">#ID</span> {{$user->parent->first()->id}}
                                </li>
                                <li class="list-group-item"><span
                                        class="label label-primary">Name</span> {{$user->parent->first()->name}}
                                </li>
                                <li class="list-group-item"><span
                                        class="label label-primary">Phone</span> {{$user->parent->first()->phone}}
                                </li>
                                <li class="list-group-item"><span
                                        class="label label-primary">Email</span> {{$user->parent->first()->email}}
                                </li>
                                <li class="list-group-item"><a
                                        href="{{route('users.show', ['PARENT', $user->parent->first()->id])}}"
                                        class="btn btn-link no-padding"><i class="fa fa-chevron-right"></i> Details</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="grid simple">
                        <div class="grid-title">
                            <h4>Subscriptions</h4>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                        </div>
                        <div class="grid-body">
                            <ul class="list-group">
                                @foreach($user->validSubscriptions as $subscription)
                                    <li class="list-group-item">
                                        {{ $subscription->pack->name }}
                                        @if($subscription->token)
                                            <a href="{{route('tokens.edit', $subscription->token->id)}}"><p class="badge badge-primary">Token: {{$subscription->token->token}}</p></a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="grid simple">
                        <div class="grid-title">
                            <h4>Game Achievements</h4>
                            <div class="tools">
                                <a href="javascript:;" class="collapse"></a>
                                <a href="javascript:;" class="remove"></a>
                            </div>
                        </div>
                        <div class="grid-body">
                            Total
                            achievements: {{optional($user->achivement)->donuts + optional($user->achivement)->candy}}
                            <br>
                            Donuts: <p class="badge badge-primary">{{optional($user->achivement)->donuts}}</p><br>
                            Candies: <p class="badge badge-primary">{{optional($user->achivement)->candy}}</p><br>
                            Completed Chapters: <p
                                class="badge badge-primary">{{optional($user->achivement)->nb_completed_chapter}}</p>
                            <br>
                            Total time: <p class="badge badge-primary">{{optional($user->achivement)->total_time}}</p>
                            <br>
                        </div>
                    </div>
                </div>
        </div>
        @endif
        @if($role->name == 'PARENT')
            <div class="col-md-4">
                <div class="grid simple">
                    <div class="grid-title">
                        <h4>Students</h4>
                        <div class="tools">
                            <a href="javascript:;" class="collapse"></a>
                            <a href="javascript:;" class="remove"></a>
                        </div>
                    </div>
                    <div class="grid-body">
                        <ul class="list-group">
                            @foreach($user->students as $student)
                                <li class="list-group-item">
                                    <a href="{{route('users.show', ['STUDENT', $student->id])}}">
                                        <span class="badge badge-primary">#{{$student->id}}</span> {{ $student->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

    </div>



@endsection
@section('js')
    <script type="text/javascript">
    </script>
@endsection
