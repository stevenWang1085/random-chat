<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>friend list</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
{{--    <link--}}
{{--        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
    <!-- Google Fonts -->
{{--    <link--}}
{{--        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
{{--    <!-- MDB -->--}}
{{--    <link--}}
{{--        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.css"--}}
{{--        rel="stylesheet"--}}
{{--    />--}}
    @vite('resources/js/friend.js')
</head>
<body id="friend" class="friend" style="background-color: #eee; padding-top: 56px">

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-p34f1UUtsS3wqzfto5wAAmdvj+osOnFyQFpp4Ua3gs/ZVWx6oOypYoCJhGGScy+8" crossorigin="anonymous"></script>
</html>
<script>
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)

        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
</script>

