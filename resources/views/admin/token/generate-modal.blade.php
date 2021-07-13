<div class="modal fade generate-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        {!! Form::open(['route' => 'tokens.batch-store']) !!}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Generate tokens</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('number', 'Number of tokens') !!}
                    {!! Form::text('number',null,['class'=>'form-control','placeholder'=>'Number']) !!}
                </div>
                <div class="input-append success date form-group" style="width: 96%;">
                    {!! Form::label('validity_start', 'Validity start') !!}
                    {!! Form::text('validity_start',null,['class'=>'form-control','placeholder'=>'Validity start', 'autocomplete' => 'off']) !!}
                    <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                </div>
                <div class="input-append success date form-group" style="width: 96%;">
                    {!! Form::label('validity_end', 'Validity end') !!}
                    {!! Form::text('validity_end',null,['class'=>'form-control','placeholder'=>'Validity end', 'autocomplete' => 'off']) !!}
                    <span class="add-on"><span class="arrow"></span><i class="fa fa-th"></i></span>
                </div>
                <div class="form-group">
                    {!! Form::label('value', 'Value') !!}
                    {!! Form::text('value',null,['class'=>'form-control','placeholder'=>'Value']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" >Save changes</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
