<?php

namespace App\Http\Controllers;

use App\Models\NotificationRead;
use App\Support\NotificationFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function __construct(private NotificationFeed $feed)
    {
    }

    /**
     * Dedicated notifications page, shared by admins and students.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $filter = $request->query('filter') === 'unread' ? 'unread' : 'all';
        $feed = $this->feed->for($user, 50, $filter);

        return view('notifications.index', [
            'feed'   => $feed,
            'filter' => $filter,
            'layout' => $user->isAdmin() ? 'layouts.app' : 'portal.layout',
        ]);
    }

    /**
     * Polled HTML + unread count for the bell panel or the full page.
     */
    public function feed(Request $request)
    {
        $mode = $request->query('mode') === 'page' ? 'page' : 'panel';
        $filter = $mode === 'page' && $request->query('filter') === 'unread' ? 'unread' : 'all';
        $feed = $this->feed->for($request->user(), $mode === 'page' ? 50 : 12, $filter);

        $html = view(
            $mode === 'page' ? 'components.notification-page-content' : 'components.notification-content',
            ['feed' => $feed, 'filter' => $filter]
        )->render();

        return response()
            ->json([
                'unread' => $feed['unread'],
                'count'  => $feed['items']->count(),
                'html'   => $html,
            ])
            ->header('Cache-Control', 'no-store, max-age=0');
    }

    /**
     * Mark one derived notification as read for the signed-in user.
     */
    public function read(Request $request)
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/'],
        ]);

        $user = $request->user();
        $feed = $this->feed->for($user, 50);

        abort_unless(
            $feed['items']->contains(fn (array $item) => $item['key'] === $validated['key']),
            404
        );

        NotificationRead::updateOrCreate(
            ['user_id' => $user->id, 'notification_key' => $validated['key']],
            ['read_at' => now()]
        );

        return response()->json([
            'unread' => $this->feed->for($user)['unread'],
        ]);
    }

    /**
     * Mark every currently derived notification as read.
     */
    public function readAll(Request $request)
    {
        $user = $request->user();
        $feed = $this->feed->for($user, 50);
        $keys = $feed['items']->pluck('key')->all();
        $now = now();

        DB::transaction(function () use ($user, $keys, $now) {
            $user->forceFill([
                'notifications_seen_at' => $now,
            ])->save();

            foreach ($keys as $key) {
                NotificationRead::updateOrCreate(
                    ['user_id' => $user->id, 'notification_key' => $key],
                    ['read_at' => $now]
                );
            }
        });

        return response()->json(['unread' => 0]);
    }

    /**
     * Backwards-compatible alias for the old bell behaviour.
     */
    public function seen(Request $request)
    {
        return $this->readAll($request);
    }
}
