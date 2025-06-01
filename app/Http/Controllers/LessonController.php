<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of available lessons.
     */
    public function index(Request $request)
    {
        $query = Lesson::query();

        // Apply filters if provided
        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $lessons = $query->paginate(10)->withQueryString();

        return view('lessons.index', [
            'lessons' => $lessons
        ]);
    }

    /**
     * Display the specified lesson.
     */
    public function show(Lesson $lesson)
    {
        // Get related lessons of the same difficulty level or adjacent levels
        $relatedLessons = Lesson::where('id', '!=', $lesson->id)
            ->where(function ($query) use ($lesson) {
                $query->where('difficulty_level', $lesson->difficulty_level)
                    ->orWhere(function ($q) use ($lesson) {
                        if ($lesson->difficulty_level === 'beginner') {
                            $q->where('difficulty_level', 'intermediate');
                        } elseif ($lesson->difficulty_level === 'advanced') {
                            $q->where('difficulty_level', 'intermediate');
                        } else {
                            $q->whereIn('difficulty_level', ['beginner', 'advanced']);
                        }
                    });
            })
            ->take(3)
            ->get();

        return view('lessons.show', [
            'lesson' => $lesson,
            'relatedLessons' => $relatedLessons
        ]);
    }
}
