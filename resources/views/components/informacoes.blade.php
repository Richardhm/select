<div style="border-color: #bde521;" class="mt-2 rounded p-1 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border-2 dark:border-white w-full lg:w-[22%]" id="container_informacoes">
    <button style="border-color: #bde521;" class="py-2 w-full px-1 me-2 mb-2 text-sm font-medium dark:text-white text-white rounded-lg border-2 dark:border-gray-200 dark:bg-gray-500 dark:bg-opacity-10">
        Tabela de Origem
    </button>

    <form>
        <div class="w-full flex">
            <div class="ml-3 w-[32%]">
                <label for="estado" class="font-extrabold text-sm" style="color:#bde521;">UF</label>
                <select id="estado" style="color:#bde521" class="font-extrabold py-2 py-2 w-full dark:border-white text-xs px-1 me-2 mb-2 font-medium rounded-lg dark:bg-transparent dark:text-white">
                    <option value="" class="text-sm font-extrabold" style="color:#bde521;">Escolher UF</option>
                    @foreach($estados as $uf)
                        <option value="{{$uf->uf}}" class="text-sm font-extrabold" style="color:#bde521;">{{$uf->uf}}</option>
                    @endforeach
                </select>
            </div>
            <div class="ml-1 w-[60%]">
                <label for="cidade" class="text-sm font-extrabold" style="color:#bde521;">Cidade</label>
                <select id="cidade" style="color:#bde521" class="font-extrabold py-2 py-2 w-full dark:border-white text-xs px-1 me-2 mb-2 font-medium rounded-lg dark:bg-transparent dark:text-white">
                    <option value="" style="color:#bde521;" class="text-sm font-extrabold">Escolher Cidade</option>
                    @foreach($cidades as $cc)
                        <option value="{{$cc->id}}" class="text-sm font-extrabold" style="color:#bde521;">{{$cc->nome}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    <div class="flex flex-wrap justify-around" id="faixa_etarias">
        <div class="flex flex-col w-[40%]">
            <div class="mb-2 w-full">

                <span class="dark:text-white text-white text-sm">0 - 18</span>

                <div class="flex items-center align-center text-center">

                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-white minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input name="input_0_18" style="width:33%;" id="input_0_18" type="text" value="0" class="flex w-44 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 minus w-10 text-white flex-grow plus_0_18">+</button>
                    </div>
                </div>


            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">24 - 28</span>

                <div class="flex items-center align-center text-center">

                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input name="input_24_28" style="width:33%;" id="input_24_28" type="text" value="0" class="flex w-28 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">34 - 38</span>
                <div class="flex items-center">
                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" name="input_34_38" style="width:33%;" id="input_34_38" value="0" class="flex w-28 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">44 - 48</span>
                <div class="flex items-center">
                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" style="width:33%;" name="input_44_48" id="input_44_48" value="0" class="flex w-28 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">54 - 58</span>
                <div class="flex items-center">
                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" style="width:33%;" name="input_54_58" id="input_54_58" value="0" class="flex w-28 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex flex-col w-[40%]">

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">19 - 23</span>
                <div class="flex items-center">

                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" style="width:33%;" name="input_19_23" id="input_19_23" value="0" class="flex w-28 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">29 - 33</span>
                <div class="flex items-center">

                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" name="input_29_33" style="width:33%;" id="input_29_33" value="0" class="flex w-28 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">39 - 43</span>
                <div class="flex items-center">
                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" style="width:33%;" name="input_39_43" id="input_39_43" value="0" class="flex w-28 text-xs text-center flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">49 - 53</span>
                <div class="flex items-center">
                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" name="input_49_53" id="input_49_53" value="0" class="flex w-28 text-center text-xs flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input" style="width:33%;">
                        <button style="width:33%;" class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow">+</button>
                    </div>
                </div>
            </div>

            <div class="mb-2">
                <span class="dark:text-white text-white text-sm">Acima 59+</span>
                <div class="flex items-center w-full">
                    <div class="flex rounded overflow-hidden border border-white dark:border-gray-200 align-center text-center mx-auto faixa-etaria-buttons h-8">
                        <button class="bg-red-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">-</button>
                        <input type="text" name="input_59" id="input_59" value="0" class="flex w-28 text-center text-xs flex-grow border-none dark:bg-opacity-20 dark:bg-gray-300 dark:text-white text-black faixa-etaria-input" style="width:33%;">
                        <button class="bg-green-400 text-gray-500 minus w-10 text-white flex-grow" style="width:33%;">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
