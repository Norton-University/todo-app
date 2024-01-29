<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $todos = Todo::query()
            ->where('user_id', auth()->id())
            ->when($request->term, function ($query, $term) {
                $query->where('title', 'LIKE', '%' . $term . '%');
            })
            ->when(!$request->input('all-dates'), function ($query) {
                $query->whereDate('created_at', today());
            })
            ->orderBy('created_at')->get();
        return Inertia::render('Dashboard', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'completed' => 'nullable|boolean'
        ]);
        auth()->user()->todos()->create($request->all());
        return redirect()->back()->with('message', 'Todo Created Successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'completed' => 'nullable|boolean'
        ]);
        $todo = auth()->user()->todos()->find($id);
        if (!$todo) {
            return redirect()->back()->with('message', 'Todo Not Found');
        }
        $todo->update($request->all());
        return redirect()->back()->with('message', 'Todo Updated Successfully');
    }

    public function toggleCompleted(Request $request, $id)
    {
        $todo = auth()->user()->todos()->find($id);
        if (!$todo) {
            return redirect()->back()->with('message', 'Todo Not Found');
        }
        $todo->update([
            'completed' => !$todo->completed
        ]);
        return redirect()->back()->with('message', 'Todo Updated Successfully');
    }

    public function destroy($id)
    {
        $todo = auth()->user()->todos()->find($id);
        if (!$todo) {
            return redirect()->back()->with('message', 'Todo Not Found');
        }
        $todo->delete();
        return redirect()->back()->with('message', 'Todo Deleted Successfully');
    }
}
