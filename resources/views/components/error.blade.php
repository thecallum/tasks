@if ($errors)
    <div class="has-margin-top-15">
        @foreach($errors->all() as $error)
            <p class="has-text-danger">{{ $error }}</p>
        @endforeach
    </div>
@endif