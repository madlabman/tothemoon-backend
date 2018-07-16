@extends('layouts.app')

@section('content')
    <div class="uk-container">
        <h2 class="uk-text-center">
            FAQ
            @if (!empty($post))
                &nbsp;#{{ $post->id }}
            @endif
        </h2>
        <div class="uk-text-center">
            @if (!empty($post))
                <div class="ml-auto">
                    <a href="{{ url('/news/delete/' . $post->id) }}" class="uk-button uk-button-danger">Удалить</a>
                </div>
            @endif
        </div>
    </div>
    <div class="uk-container uk-padding">
        <form action="" class="uk-form-horizontal uk-width-1-2 uk-align-center" method="post">
            @csrf
            <fieldset class="uk-fieldset">

                <div class="uk-margin">
                    <label class="uk-form-label">Вопрос</label>
                    <div class="uk-form-controls">
                        <input type="text" name="question" class="uk-input" value="@if (!empty($post)){{ $post->question }}@endif">
                    </div>
                    @if ($errors->has('question'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('question') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="uk-margin">
                    <textarea class="uk-textarea" id="content" name="answer" rows="10">@if (!empty($post)){{ $post->answer }}@endif</textarea>
                    @if ($errors->has('answer'))
                        <div class="uk-alert-danger" uk-alert>
                            <strong>{{ $errors->first('answer') }}</strong>
                        </div>
                    @endif
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.8.0/tinymce.min.js"></script>
                <script>
                    tinyMCE.init({
                        selector: '#content',
                        plugins: [
                            'anchor',
                            'autosave',
                            'fullscreen',
                            'lists',
                            'link',
                            'media',
                            'table',
                            'textcolor',
                            'wordcount',
                        ],
                        skin: 'lightgray',
                        theme: 'modern',
                        language_url: '/js/lang/ru.js',
                        // plugins: [
                        //     "a11ychecker advcode advlist anchor autolink codesample colorpicker contextmenu fullscreen help image imagetools",
                        //     " lists link linkchecker media mediaembed noneditable powerpaste preview",
                        //     " searchreplace table template textcolor tinymcespellchecker visualblocks wordcount"
                        // ],
                    })
                </script>

            </fieldset>

            <button type="submit" class="uk-button uk-button-primary">
                @if(!empty($post))
                    Обновить
                @else
                    Создать
                @endif
            </button>
        </form>
    </div>
@endsection
