<div class="w-[230px] relative ... mx-8 my-8 z-[9999]" id="dropdownButton">
    <div class="border-solid border-gray-400 border-[1px] px-5 py-1 rounded-xl cursor-pointer flex justify-between items-center font-bold"
        onclick="toggleDropdown()">
        <span id="selectedKurikulum" class="text-xs">Pilih Kurikulum</span>
        <i class="fas fa-chevron-up transition-transform duration-500 text-xs" id="dropdownArrow"></i>
    </div>
    <div class="absolute w-full rounded-lg bg-white shadow-md hidden z-[9999]" id="dropdown">
        <input type="radio" name="radio" id="drop1" value="Kurikulum K13" onchange="updateSelection(this)">
        <label for="drop1"
            class="w-full flex justify-between items-center p-2 hover:bg-[#eee] hover:rounded-lg cursor-pointer"
            onclick="k13()">
            <span class="text-xs">Kurikulum K13</span>
            <i class="fas fa-check iconChecked hidden text-xs"></i>
        </label>

        <input type="radio" name="radio" id="drop2" value="Kurikulum Merdeka" onchange="updateSelection(this)">
        <label for="drop2"
            class="w-full flex justify-between items-center p-2 hover:bg-[#eee] hover:rounded-lg cursor-pointer"
            onclick="merdeka()">
            <span class="text-xs">Kurikulum Merdeka</span>
            <i class="fas fa-check iconChecked hidden text-xs"></i>
        </label>
    </div>
</div>
