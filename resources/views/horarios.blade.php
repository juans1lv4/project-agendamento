<!DOCTYPE html>
<html>
<head>
    <title>Horários Disponíveis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h1 class="mb-4">Verificar Horários</h1>
        
        <form method="GET" action="{{ url('/horarios-disponiveis') }}" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="data" class="form-label">Escolha uma data:</label>
                    <input type="date" name="data" id="data" class="form-control" required 
                           min="{{ now()->format('Y-m-d') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        @isset($horarios)
            <div class="mt-4">
                <h2>Horários disponíveis em {{ request('data') }}:</h2>
                <ul class="list-group">
                    @forelse($horarios as $horario)
                        <li class="list-group-item">{{ $horario }}</li>
                    @empty
                        <li class="list-group-item text-danger">Nenhum horário disponível!</li>
                    @endforelse
                </ul>
            </div>
            
        @endisset
    </div>
</body>
</html>