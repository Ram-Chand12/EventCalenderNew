<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="assets/vendor/ckeditor5/build/ckeditor.js"></script>
  <title>CKE5 in Laravel</title>
</head>
<body>
    <h1>Welcome to CKEditor&nbsp;5 in Laravel</h1>
    <div id="editor"></div>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
</body>
</html>
