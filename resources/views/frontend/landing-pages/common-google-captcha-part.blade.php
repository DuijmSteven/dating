@if(config('app.env') === 'local')
    <script src="https://www.google.com/recaptcha/api.js?render=6Lcb0N8UAAAAADUTgOIB9jcrz2xM60BPNjeK3qWL"></script>

@elseif(\config('app.name') == 'altijdsex.nl' && config('app.env') === 'production')
    <script src="https://www.google.com/recaptcha/api.js?render=6LdHptgUAAAAACP5lA0778MuyBsjs6oEnQcWo0T1"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LdHptgUAAAAACP5lA0778MuyBsjs6oEnQcWo0T1', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>
@elseif(\config('app.name') == 'altijdsex.nl' && config('app.env') === 'staging')
    <script src="https://www.google.com/recaptcha/api.js?render=6Ldx0N8UAAAAABj1wlIcdnxtgCxrprg3DPMsDtkj"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6Ldx0N8UAAAAABj1wlIcdnxtgCxrprg3DPMsDtkj', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>

@elseif(\config('app.name') == 'liefdesdate.nl' && config('app.env') === 'production')
    <script src="https://www.google.com/recaptcha/api.js?render=6Les2dYZAAAAAPxu6emr65nygAIwEGvvU7RASKWz"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6Les2dYZAAAAAPxu6emr65nygAIwEGvvU7RASKWz', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>
@elseif(\config('app.name') == 'liefdesdate.nl' && config('app.env') === 'staging')
    <script src="https://www.google.com/recaptcha/api.js?render=6Le9gtIZAAAAAD0V29NclSJdawf-8A8F5wBAdWot"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6Le9gtIZAAAAAD0V29NclSJdawf-8A8F5wBAdWot', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>
@elseif(\config('app.name') == 'sweetalk.nl' && config('app.env') === 'production')
    <script src="https://www.google.com/recaptcha/api.js?render=6LfSZV0aAAAAAIaUyZ_pjaqXtG3mVocGJl5rXaC5"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LfSZV0aAAAAAIaUyZ_pjaqXtG3mVocGJl5rXaC5', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>
@elseif(\config('app.name') == 'sweetalk.nl' && config('app.env') === 'staging')
    <script src="https://www.google.com/recaptcha/api.js?render=6LeTaF0aAAAAAKf5R8aZMClUgssSrst9JbA9Daos"></script>

    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6LeTaF0aAAAAAKf5R8aZMClUgssSrst9JbA9Daos', {action: 'homepage'}).then(function(token) {
            });
        });
    </script>
@endif