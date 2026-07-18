# T8 — AWS Rekognition visual moderation (Trovador)

Async, resilient, real-time moderation of uploaded **images and videos**.
Audio notes, text comments and regular files are never moderated.

## Flow

```
User uploads image/video
        │
        ▼
AttachmentServiceProvider::createAttachment()
        │  saves Attachment with moderation_status = 'processing'
        │  dispatches ModerateAttachmentJob (queued)
        ▼
ModerateAttachmentJob (worker)
        │  RekognitionModerationService::moderate()
        │     image  → DetectModerationLabels on the file
        │     video  → sample N frames (FFmpeg) → DetectModerationLabels each, worst wins
        │  decide by confidence band:
        │     ≥ reject_threshold (85) → 'rejected'
        │     ≥ review_threshold (70) → 'pending_review'
        │     else                    → 'approved'
        │  persist status/score/labels on the attachment
        ▼
NotificationServiceProvider::publishRawEvent(username, 'attachment-moderated', …)
        │  (Pusher/Reverb, on the user's existing private channel)
        ▼
Websockets.js  channel.bind('attachment-moderated')  → toast + drop rejected preview
```

## Resilience (as approved in the proposal)

- **3 attempts**, exponential **backoff 30s / 2min / 5min** (`config/rekognition.php`).
- **5-minute global timeout** per attempt (`$timeout`).
- On permanent failure → `moderation_status = 'failed'`, user notified, logged.

## Admin-configurable (client requirement)

Panel → **AI Settings → Content Moderation** tab:
- Enable/disable moderation
- Auto-reject threshold (%)
- Manual-review threshold (%)
- Notify user toggle

Stored in the `ai.*` settings group; service reads settings first, falls back to
`config/rekognition.php` / `.env`.

## Files

| File | Purpose |
|------|---------|
| `config/rekognition.php` | credentials + fallback thresholds + job knobs |
| `database/migrations/2231_07_14_000001_trovador_attachment_moderation.php` | adds `moderation_*` columns to `attachments` |
| `database/migrations/2231_07_14_000002_trovador_moderation_settings.php` | registers `ai.moderation_*` settings |
| `app/Services/Moderation/RekognitionModerationService.php` | AWS call + decision logic |
| `app/Jobs/ModerateAttachmentJob.php` | async worker, retries/timeout/failure |
| `app/Providers/NotificationServiceProvider.php` | `publishRawEvent()` helper (added) |
| `app/Providers/AttachmentServiceProvider.php` | dispatch hook in `createAttachment()` |
| `app/Model/Attachment.php` | fillable + casts for moderation columns |
| `app/Settings/AISettings.php` | moderation settings properties |
| `app/Filament/Pages/Settings/ManageAiSettings.php` | Content Moderation tab |
| `public/js/Websockets.js` | `attachment-moderated` listener |

## How to test (on the VPS, after setup)

1. `php artisan migrate` (runs both new migrations).
2. Ensure `QUEUE_CONNECTION=redis` and a worker is running:
   `php artisan queue:work --queue=default`
3. Ensure Reverb is running: `php artisan reverb:start` and `BROADCAST_DRIVER=reverb`.
4. Log in, open post/story/reel creator, upload:
   - a normal photo → toast **"publicado correctamente"** (approved)
   - a known-explicit test image → toast **rejected**, preview removed
5. Lower the review threshold in the panel to force `pending_review` and re-test.
6. Kill the worker mid-upload / use a bad AWS key to see retry → `failed` toast.

## Open items (post-demo, not needed to demo the pipeline)

- **Feed/display filtering:** `rejected` (and optionally `pending_review`) attachments
  are flagged but not yet filtered out of every feed/query. Needs wiring into the
  post/reel visibility logic and the post `status`. Tracked as a follow-up.
- **Hard-delete on reject:** currently rejected files are hidden, not deleted from
  storage. Add a config toggle if the client wants the bytes removed.
- **Design decision to confirm with client:** moderation is **asynchronous**
  (upload succeeds, result arrives in real time) rather than a hard synchronous
  block-before-save. This matches the approved proposal (backoff/timeout/realtime),
  but the brief's literal wording said "NO guardar". Confirm async is acceptable.
