<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo }} — STX Gov · Recrutamento</title>
    <style>
        :root { color-scheme: light; }
        body { margin: 0; font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif; background: #f8fafc; color: #0f172a; }
        header { background: #071C2C; color: #fff; padding: 14px 24px; font-weight: 700; letter-spacing: .08em; }
        main { max-width: 32rem; margin: 48px auto; padding: 0 16px; }
        h1 { font-size: 1.25rem; }
        p { color: #475569; line-height: 1.6; }
        a { color: #F36F21; }
    </style>
</head>
<body>
    <header>STX Gov · Recrutamento</header>
    <main>
        <h1>{{ $titulo }}</h1>
        <p>{{ $mensagem }}</p>
        @if ($portal)
            <p><a href="{{ $portal }}">Voltar ao SIGA</a></p>
        @endif
    </main>
</body>
</html>
