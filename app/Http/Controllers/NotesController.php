<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notes\IndexNotesRequest;
use App\Http\Requests\Notes\StoreNoteRequest;
use App\Http\Requests\Notes\UpdateNoteRequest;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Gate;
use Ramsey\Uuid\Uuid;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexNotesRequest $request
     * @return Application|NoteCollection|Factory|View
     */
    public function index(IndexNotesRequest $request)
    {
        if ($request->is('api/*')) {
            return new NoteCollection(Note::search($request)->paginate(5));
        } else {
            return view('layouts.frontend.pages.note.notes', [
                'notes' => new NoteCollection(Note::search($request)->paginate(5))//?
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Note $note
     * @return Application|Factory|View|NoteResource
     */
    public function show(Request $request, Note $note)
    {
        if ($request->is('api/*')) {
            return new NoteResource($note);
        } else {
            return view('layouts.frontend.pages.note.notes-show', compact('note'));
        }
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('layouts.frontend.pages.note.notes-edit');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param string $locale
     * @param Note $note
     * @return Application|Factory|View|Response|JsonResponse
     */
    public function edit(Request $request, string $locale, Note $note)
    {
        $response = Gate::inspect('update', $note);

        if ($response->allowed()) {
            if ($request->is('api/*')) {
                return \response(new NoteResource($note));
            } else {
                return view('layouts.frontend.pages.note.notes-edit', [
                    'note' => new NoteResource($note),
                ]);
            }
        } else {
            if ($request->is('api/*')) {
                return \response()->json([
                    'message' => $response->message()
                ], 403);
            } else {
                $request->session()->flash('message', ['type' => 'error', 'content' => $response->message()]);

                return redirect("/$locale/notes");
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNoteRequest $request
     * @param string $locale
     * @return Application|RedirectResponse|Redirector|NoteResource
     * @throws AuthorizationException
     */
    public function store(StoreNoteRequest $request, string $locale)
    {
        $this->authorize('create', [Note::class]);
        $user = $request->authUser();

        /** @var Note $note */
        $note = $user->notes()->create(array_merge($request->input('translations'), [
                'uid' => Uuid::uuid4(),
                'category' => $request->input('category'),
                'visibility' => $request->input('visibility'),
            ])
        );

        if ($shareWith = $request->input('share_user_email')) {
            $note->share($shareWith);
        }

        if ($request->is('api/*')) {
            return new NoteResource($note);
        } else {
            $request->session()->flash('message', ['type' => 'success', 'content' => 'Note created']);

            return redirect("/$locale/notes");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateNoteRequest $request
     * @param string $locale
     * @param Note $note
     * @return Application|RedirectResponse|Redirector|NoteResource
     */
    public function update(UpdateNoteRequest $request, string $locale, Note $note)
    {
        $note->update(array_merge($request->input('translations'), [
                'category' => $request->input('category'),//category_id
                'visibility' => $request->input('visibility'),
            ])
        );

        if ($shareWith = $request->input('share_user_email')) {
            $note->share($shareWith);
        }

        if ($request->is('api/*')) {
            return new NoteResource($note);
        } else {
            $request->session()->flash('message', ['type' => 'success', 'content' => 'Note updated']);

            return redirect("/$locale/notes");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param string $locale
     * @param Note $note
     * @return Application|RedirectResponse|Response|Redirector|JsonResponse
     * @throws Exception
     */
    public function destroy(Request $request, string $locale, Note $note)
    {
        $response = Gate::inspect('delete', $note);

        if ($response->allowed()) {
            $note->delete();
        }

        if ($request->is('api/*')) {
            return \response()->json([
                'message' => $response->message(),
            ], $response->allowed() ? 200 : 403);
        } else {
            $request->session()->flash('message', ['type' => $response->allowed() ? 'success' : 'error', 'content' => $response->message()]);

            return redirect("/$locale/notes?category=all");
        }
    }
}
