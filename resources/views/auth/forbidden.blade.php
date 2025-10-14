<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>No autorizado</title>
	<link rel="icon" href="{{ asset('assets/img/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap" rel="stylesheet">
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    main>section>a>button {
        margin: 1rem 0 0 0;
        padding: 5px 2rem;
        color: #FFFFFF;
        background-color: #049c2c;
        border: none;
        border-radius: 10px;
    }

    main>section>a>button:hover {
        cursor: pointer;
        background-color: #0c9633;
    }
</style>

<body
    style="font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">
    <main style="height:99vh;display:flex;justify-content:center;align-items:center;flex-wrap:wrap-reverse">
        <section style="display:flex;flex-direction:column;align-items:center">
            <p style="font-size: 10rem; color:#1e326f;  font-family: 'Oswald', sans-serif;font-weight:500;">4<img src="{{ asset('assets/img/favicon.ico') }}">3</p>
            <strong>
                <p style="font-size: 2rem; color:#049c2c;">Forbidden</p>
            </strong>
            <strong>
                <p style="font-size: 1rem;color:#1e326f;">Lo sentimos, usted no se encuentra autorizado.</p>
            </strong>
            <a href="/">
                <button>Regresar</button>
            </a>
        </section>
        <section style="width:40vw">
            <img width="90%" src="{{ asset('assets/img/forbidden.svg') }}">
        </section>
    </main>
</body>

</html>
