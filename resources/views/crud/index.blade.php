<x-layout></x-layout>
<div class="overflow-x-auto">
    <table class="table">
        <!-- head -->
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Sekolah</th>
                <th>Fase</th>
                <th>Kelas</th>
                <th>Email</th>
                <th>password</th>
                <th>No.Hp</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->nama_lengkap }}</td>
                    <td>{{ $user->sekolah }}</td>
                    <td>{{ $user->fase }}</td>
                    <td>{{ $user->kelas }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->password }}</td>
                    <td>{{ $user->no_hp }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        <a href="{{ route('crud.edit', $user->id) }}">Edit</a>
                        <form action="{{ route('crud.destroy', $user->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<button class="btn" onclick="my_modal_3.showModal()">Tambah Data</button>
<dialog id="my_modal_3" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button class="absolute right-4 top-2 outline-none">✕</button>
        </form>
        <h3 class="text-lg font-bold mb-4">Tambah Data</h3>
        <form action="{{ route('crud.store') }}" method="POST">
            @csrf
            <div class="flex mb-4 gap-2">
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="nama_lengkap">Nama</label>
                    <input type="text"
                        class="w-full border-2 rounded-lg outline-cyan-400 {{ $errors->has('nama_lengkap') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="nama_lengkap" id="nama_lengkap" value="{{ @old('nama_lengkap') }}">
                    @error('nama_lengkap')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="email">email</label>
                    <input type="text"
                        class="w-full border-2 rounded-lg outline-cyan-400 {{ $errors->has('email') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="email" id="email" value="{{ @old('email') }}">
                    @error('email')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex mb-4 gap-2">
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="sekolah">Sekolah</label>
                    <input type="text"
                        class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('sekolah') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="sekolah" id="sekolah" value="{{ @old('sekolah') }}">
                    @error('sekolah')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="password">password</label>
                    <input type="text"
                        class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('password') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="password" id="password" value="{{ @old('password') }}">
                    @error('password')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex mb-4 gap-2">
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="fase">Fase</label>
                    <input type="text"
                        class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('fase') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="fase" id="fase" value="{{ @old('fase') }}">
                    @error('fase')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="no_hp">no_hp</label>
                    <input type="text"
                        class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('no_hp') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="no_hp" id="no_hp" value="{{ @old('no_hp') }}">
                    @error('no_hp')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex mb-4 gap-2">
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="kelas">kelas</label>
                    <input type="text"
                        class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('kelas') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="kelas" id="kelas" value="{{ @old('kelas') }}">
                    @error('kelas')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex flex-col w-full">
                    <label class="mb-2" for="status">status</label>
                    <input type="text"
                        class="border-2 rounded-lg outline-cyan-400 {{ $errors->has('status') ? 'border-rose-300' : 'border-gray-300' }}"
                        name="status" id="status" value="{{ @old('status') }}">
                    @error('status')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-success">Kirim Data</button>
        </form>
    </div>
</dialog>


<script>
    document.getElementById('submit').addEventListener('click', function() {
        const form = document.querySelector('#my_modal_3 form[action]');
        form.submit(); // Submit the form
    });
</script>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure the modal is displayed if there are validation errors
            document.getElementById('my_modal_3').showModal();
        });
    </script>
@endif
