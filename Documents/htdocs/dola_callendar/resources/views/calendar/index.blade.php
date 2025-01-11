<!DOCTYPE html>
<html>
<head>
    <title>Minha Agenda</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .no-events {
            color: #888;
            font-style: italic;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Minha Agenda</h1>

    @if (count($events) > 0)
        <table>
            <thead>
                <tr>
                    <th>Evento</th>
                    <th>Data e Hora</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->getSummary() }}</td>
                        <td>
                            @php
                                // Configura o locale para português
                                \Carbon\Carbon::setLocale('pt_BR');
                                // Formata a data e hora com o dia da semana dinâmico
                                echo \Carbon\Carbon::parse($event->getStart()->getDateTime())->translatedFormat('d/m/Y H:i l');
                            @endphp
                        </td>
                        <td>{{ $event->getDescription() ?? 'Sem descrição' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="no-events">Nenhum evento cadastrado no momento, volte mais tarde!</p>
    @endif
</body>
</html>