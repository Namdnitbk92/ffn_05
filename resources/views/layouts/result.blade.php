@if(isset($success) && is_string($success))
    <div class="alert alert-info">
        <i class="checkmark icon"></i>&nbsp;{{ $success }}
    </div>
@endif
@if(isset($error) && is_string($error))
    <div class="alert alert-danger">
        <i class="warning circle icon"></i>&nbsp;{{ $error }}
    </div>
@endif
