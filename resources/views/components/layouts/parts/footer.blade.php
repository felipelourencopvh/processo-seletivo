<footer class="bg-[#071c2c] py-10 text-[13.5px] text-[#9fb2c2]">
    <div class="mx-auto flex w-full max-w-[1240px] flex-wrap items-center justify-between gap-5 px-4 lg:px-8">
        <x-marca claro />
        <nav class="flex flex-wrap gap-x-6 gap-y-2">
            <a href="#" class="hover:text-white">Transparência</a>
            <a href="#" class="hover:text-white">Ouvidoria</a>
            <a href="https://falabr.cgu.gov.br/web/login?tipo=8&redirect=/manifestacao/criar?tipo=8" class="hover:text-white">
                Acesso à informação
            </a>
        </nav>
        <span>Recrutamento © {{ now()->year }}</span>
    </div>
</footer>
