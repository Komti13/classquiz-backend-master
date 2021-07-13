@extends('admin.layout.layout',['title'=>'Avatars'])
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.css">
@endsection
@section('content')
    <div class="row-fluid">
        <div class="span12">
            <div class="grid simple">
                <div class="grid-title">
                    <h4>List <span class="semi-bold">Avatars</span></h4>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="#grid-config" data-toggle="modal" class="config"></a>
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                    </div>
                </div>
                <div class="grid-body ">
                    <form action="{{route("avatars.store")}}" method="post" class="dropzone">
                        @csrf
                        <div class="fallback">
                            <input name="name" type="file" multiple/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/dropzone.js"></script>
    <script>
        let fileNames = [];
        Dropzone.autoDiscover = false;
        var dropzone_default = new Dropzone(".dropzone", {
            url: '{{route("avatars.store")}}',
            acceptedFiles: 'image/*',
            init: function () {
                //add old files
                @foreach($avatars as $avatar)
                fileNames.push('{{$avatar->name}}');

                var mockFile = {name: '{{$avatar->name}}', size: 20000};
                this.emit("addedfile", mockFile);
                this.createThumbnailFromUrl(mockFile, '{{asset('uploads/avatar/'.$avatar->name)}}');
                this.emit("complete", mockFile);

                @endforeach
            },
            paramName: "name",
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            dictRemoveFileConfirmation: "Are you sure?",
            success: function (file, response) {
                file.serverName = response.name;
                fileNames.push(file.serverName);
            },
            removedfile: function (file) {
                console.log(file);
                for (var i = 0; i < fileNames.length; ++i) {
                    if (fileNames[i] == file.name || fileNames[i] == file.serverName) {
                        $.ajax({
                            url: '/avatars/' + fileNames[i],
                            data: {"_token": $('meta[name="csrf-token"]').attr('content')},
                            type: 'DELETE',
                            success: function (result) {
                                file.previewElement.remove();
                            }
                        });
                    }
                }

            }
        });
    </script>
@endsection
