<!DOCTYPE html>
<html>
<head>
    <title>Admin | Manage Featured Games</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #111827;
            color: white;
            font-family: Arial, sans-serif;
            padding: 32px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
        }

        .back-link {
            color: #60a5fa;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* TABLE BOX */
        .table-wrapper {
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 10px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #374151;
            color: #d1d5db;
            text-transform: uppercase;
            font-size: 12px;
        }

        th, td {
            padding: 16px;
            text-align: left;
        }

        tbody tr {
            border-top: 1px solid #374151;
            transition: 0.2s;
        }

        tbody tr:hover {
            background: #2b3545;
        }

        .center {
            text-align: center;
        }

        /* STATUS BADGES */
        .badge {
            padding: 4px 10px;
            font-size: 12px;
            border-radius: 6px;
        }

        .featured {
            background: #78350f;
            color: #fcd34d;
        }

        .standard {
            background: #4b5563;
            color: #d1d5db;
        }

        /* BUTTONS */
        .btn {
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: 0.2s;
            color: white;
        }

        .btn-feature {
            background: #16a34a;
        }

        .btn-feature:hover {
            background: #15803d;
        }

        .btn-remove {
            background: #dc2626;
        }

        .btn-remove:hover {
            background: #b91c1c;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1 class="title">Manage Featured Games</h1>

        <a href="{{ route('store.index') }}" class="back-link">
            ← Back to Store
        </a>
    </div>

    <div class="table-wrapper">

        <table>
            <thead>
                <tr>
                    <th>Game Title</th>
                    <th>Current Status</th>
                    <th class="center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($allGames as $game)
                    <tr>
                        <td>{{ $game->title }}</td>

                        <td>
                            @if($game->is_featured)
                                <span class="badge featured">Featured</span>
                            @else
                                <span class="badge standard">Standard</span>
                            @endif
                        </td>

                        <td class="center">
                            <form action="{{ route('admin.toggle', $game->id) }}" method="POST">
                                @csrf

                                <button type="submit"
                                    class="btn {{ $game->is_featured ? 'btn-remove' : 'btn-feature' }}">
                                    {{ $game->is_featured ? 'Remove' : 'Feature This' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>

</div>

</body>
</html>