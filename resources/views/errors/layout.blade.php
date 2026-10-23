<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('codigo') — STX Gov · Recrutamento</title>
    <style>
        :root { color-scheme: light; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px;
               font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
               background: #f1f5f9; color: #0f172a; }
        .cartao { width: 100%; max-width: 30rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 16px;
                  box-shadow: 0 10px 30px -12px rgba(15,23,42,.25); padding: 32px; text-align: center; }
        .marca { display:inline-block; background:#071C2C; color:#fff; font-weight:700; letter-spacing:.08em;
                 font-size:12px; padding:6px 12px; border-radius:999px; }
        .codigo { font-size: 56px; font-weight: 800; color:#db2777; margin: 18px 0 4px; line-height:1; }
        h1 { font-size: 1.15rem; margin: 0 0 8px; }
        p { color:#475569; line-height:1.6; margin: 0 0 20px; }
        a.botao { display:inline-block; background:#db2777; color:#fff; text-decoration:none; font-weight:600;
                  padding:10px 18px; border-radius:10px; }
        a.botao:hover { background:#be185d; }
    </style>
</head>
<body>
    <main class="cartao">
        <span class="marca">STX Gov · Recrutamento</span>
        <div class="codigo">@yield('codigo')</div>
        <h1>@yield('titulo')</h1>
        <p>@yield('mensagem')</p>
        <a class="botao" href="{{ url('/') }}">Voltar ao início</a>
    </main>
</body>
</html>
