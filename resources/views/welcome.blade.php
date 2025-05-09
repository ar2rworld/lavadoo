@extends('layouts.app')

@section('content')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body class="bg-gray-100 text-gray-900 p-6">
    <div class="relative min-h-screen bg-gray-50 p-6">

        <div class="sticky top-0 z-10 bg-gray-100/90 backdrop-blur shadow-md p-4 rounded mb-6 flex flex-col gap-4 max-w-xl mx-auto">
        <form id="create-counter-form" class="sticky top-0 z-10 bg-white p-3 rounded shadow flex items-center gap-2 mb-4">
            @csrf

            <label for="name" class="text-sm font-medium">Name:</label>
            <input type="text" name="name" id="name" required class="border px-2 py-1 rounded w-32" />

            <label for="number" class="text-sm font-medium">Number:</label>
            <input type="number" name="number" id="number" required class="border px-2 py-1 rounded w-24" />

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add</button>
        </form>


        <form id="edit-counter-form" class="sticky top-20 z-10 bg-yellow-100 p-3 rounded shadow flex items-center gap-2 mb-4 hidden">
            @csrf
            @method('PUT')

            <input type="hidden" name="id" id="edit-id">

            <label for="edit-name" class="text-sm font-medium">Name:</label>
            <input type="text" name="name" id="edit-name" required class="border px-2 py-1 rounded w-32" />

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
        </form>


        </div>

        <ul id="counters-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            @foreach ($counters as $counter)
                <li data-id="{{ $counter->id }}" class="counter-item bg-white p-4 shadow rounded flex flex-col justify-between">
                    <span class="counter-text mb-2 font-semibold">{{ $counter->name }} — {{ $counter->number }}</span>
                    <div class="flex gap-2 mt-auto">
                        <button class="edit-btn flex-1 bg-blue-100 text-blue-700 rounded px-2 py-1">Edit</button>
                        <button class="delete-btn flex-1 bg-red-100 text-red-700 rounded px-2 py-1">🗑️</button>
                        <button class="decrement-btn flex-1 bg-yellow-100 text-yellow-700 rounded px-2 py-1">➖</button>
                        <button class="plus-btn flex-1 bg-green-100 text-green-700 rounded px-2 py-1">➕</button>
                    </div>
                </li>
            @endforeach
        </ul>


    </div>

    <div id="error-msg" class="text-red-600 mb-3 hidden"></div>

    <script>
        $('#create-counter-form').on('submit', function (e) {
        e.preventDefault();
        $('#error-msg').addClass('hidden').text('');

        $.ajax({
            url: '{{ route("counters.store") }}',
            method: 'POST',
            data: {
                id: 0,
                name: $('#name').val(),
                number: $('#number').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                    if (response.success) {
                        const newCounter = htmlNewCounter(response.counter);
                        $('#counters-list').append(newCounter);
                        $('#create-counter-form')[0].reset();
                    } else {
                        $('#error-msg').removeClass('hidden').text('Unexpected response.');
                    }
                },
                error: function (xhr) {
                    let err = xhr.responseJSON.errors;
                    let firstError = Object.values(err)[0][0];
                    $('#error-msg').removeClass('hidden').text(firstError);
                }
            });
        });
    </script>
    <script>
    $(document).on('click', '.delete-btn', function () {
    const id = $(this).closest('li').data('id');
    const $li = $(this).closest('li');

        $.ajax({
            url: `/counters/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function () {
                $li.remove();
            },
            error: function () {
                alert('Failed to delete counter.');
            }
        });
    });


    // Handle edit button click
    $(document).on('click', '.edit-btn', function () {
        const $li = $(this).closest('li');
        editingId = $li.data('id');

        const currentText = $li.find('span').text();
        const [currentName, currentNumber] = currentText.split(' — ');

        $('#edit-id').val(editingId);
        $('#edit-name').val(currentName.trim());
        $('#edit-number').val(currentNumber.trim());
        $('#edit-counter-form').removeClass('hidden');
    });

    // Handle form submission
    $('#edit-counter-form').on('submit', function (e) {
        e.preventDefault();

        const id = $('#edit-id').val();
        const name = $('#edit-name').val();

        $.ajax({
            url: `/counters/${id}`,
            method: 'POST',
            data: {
                _method: 'PUT',
                _token: '{{ csrf_token() }}',
                name: name,
            },
            success: function (response) {
                // Update text in list
                $(`li[data-id="${id}"] span`).text(`${response.name} — ${response.number}`);
                $('#edit-counter-form').addClass('hidden');
            },
            error: function () {
                alert('Failed to update counter.');
            }
        });
    });

    $(document).on('click', '.plus-btn', function () {
        const $li = $(this).closest('li');
        const id = $li.data('id');

        $.ajax({
            url: `/counters/${id}/increment`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function (response) {
                $li.find('.counter-text').text(`${response.name} — ${response.number}`);
            },
            error: function () {
                console.error('Failed to increment counter.');
            }
        });
    });

    $(document).on('click', '.decrement-btn', function () {
        const $li = $(this).closest('li');
        const id = $li.data('id');

        $.ajax({
            url: `/counters/${id}/decrement`,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
                if (response.success) {
                    $li.find('.counter-text').text(`${response.name} — ${response.number}`);
                }
            },
            error: function () {
                alert('Failed to decrement counter.');
            }
        });
    });

    function htmlNewCounter(counter) {
        return `
            <li data-id="${counter.id}" class="counter-item bg-white p-4 shadow rounded flex flex-col justify-between">
                <span class="counter-text mb-2 font-semibold">${counter.name} — ${counter.number}</span>
                <div class="flex gap-2 mt-auto">
                    <button class="edit-btn flex-1 bg-blue-100 text-blue-700 rounded px-2 py-1">Edit</button>
                    <button class="delete-btn flex-1 bg-red-100 text-red-700 rounded px-2 py-1">🗑️</button>
                    <button class="decrement-btn flex-1 bg-yellow-100 text-yellow-700 rounded px-2 py-1">➖</button>
                    <button class="plus-btn flex-1 bg-green-100 text-green-700 rounded px-2 py-1">➕</button>
                </div>
            </li>
        `;
    }

    function updateCountersList(counters) {
        const $listItems = $('#counters-list li');
        const currentMap = {};

        // Build a map of current counters by ID
        $listItems.each(function () {
            const id = $(this).data('id');
            const text = $(this).find('.counter-text').text();
            const [name, numberStr] = text.split(' — ');
            currentMap[id] = {
                name: name.trim(),
                number: parseInt(numberStr.trim(), 10)
            };
        });

        let hasChanged = false;

        if (Object.keys(currentMap).length !== counters.length) {
            hasChanged = true;
        } else {
            for (const counter of counters) {
                const current = currentMap[counter.id];
                if (!current || current.name !== counter.name || current.number !== counter.number) {
                    hasChanged = true;
                    break;
                }
            }
        }

        if (hasChanged) {
            let html = '';
            counters.forEach(counter => {
                html += htmlNewCounter(counter);
            });
            $('#counters-list').html(html);
        }
    }


    function fetchCounters() {
        $.ajax({
            url: '{{ route("counters.index") }}',
            method: 'GET',
            success: function (data) {
                console.log("fetch data:", data);
                updateCountersList(data);
            },
            error: function () {
                console.warn('Failed to fetch counters');
            }
        });
    }

    const fetchDelay = '{{ env("FETCH_DELAY", 10000) }}'; // Fallback to 10000ms if not set

    setInterval(fetchCounters, fetchDelay);

</script>
</body>
@endsection
