<x-app title="Crear usuario">
    <section class="container my-5">
        <div class="card">
            <div class="card-header">
                <h2 class="h4">Crear usuario</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <x-users.form :roles="$roles"/>
                </form>
            </div>
        </div>
    </section>

    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('photo').addEventListener('change', function(event) {
                    const input = event.target;
                    const file = input.files[0];
                    const preview = document.getElementById('preview-image');

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };

                        reader.readAsDataURL(file);
                    } else {
                        preview.style.display = 'none';
                    }
                });
            });
        </script>
    </x-slot:scripts>
</x-app>
