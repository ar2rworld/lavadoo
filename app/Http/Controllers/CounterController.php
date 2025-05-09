<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $counters = Counter::all();
        return response()->json($counters);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('counter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $counter = Counter::create([
            'id' => 0,
            'number' => $validated['number'],
            'name' => $validated['name'],
            'deleted' => false,
        ]);

        return response()->json([
            'success' => true,
            'counter' => $counter,
        ]);
    }

    public function increment(Counter $counter)
    {
        $counter->increment('number');
        return response()->json([
            'success' => true,
            'id' => $counter->id,
            'name' => $counter->name,
            'number' => $counter->number,
        ]);
    }

    public function decrement(Counter $counter)
    {
        $counter->decrement('number');
        return response()->json([
            'success' => true,
            'id' => $counter->id,
            'number' => $counter->number,
            'name' => $counter->name,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $counter = Counter::find($id);

        if ($counter) {
            $counter->name = $validated['name'];
            $counter->save();
            $counter->updated_at = now();

            return response()->json($counter);
        }

        return response()->json(['error' => 'Counter not found'], 404);
    }


    public function destroy(Counter $counter)
    {
        $counter->deleted_at = now();
        $counter->delete();
        return response()->json(['success' => true]);
    }
}
