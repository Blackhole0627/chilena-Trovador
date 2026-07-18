<?php

/*
|--------------------------------------------------------------------------
| Trovador — AWS Rekognition content moderation
|--------------------------------------------------------------------------
|
| Credentials and *fallback* defaults for the visual moderation pipeline
| (brief task T8). The thresholds here are only used when the admin has
| not configured them in the panel (AI Settings → Content Moderation),
| which stores them in the `ai.*` settings group. See
| RekognitionModerationService::thresholds().
|
*/

return [

    // Master switch. When false, every attachment is auto-approved and no
    // AWS call is made (useful for local/dev without AWS credentials).
    'enabled' => env('REKOGNITION_ENABLED', true),

    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'version' => env('REKOGNITION_API_VERSION', 'latest'),
    ],

    /*
    | Confidence bands (percent, 0-100). Rekognition returns a moderation
    | confidence for the most explicit label it detects.
    |
    |   score >= reject_threshold   -> rejected (hidden, user notified)
    |   score >= review_threshold   -> pending manual review (user notified)
    |   score <  review_threshold   -> approved automatically
    |
    | These match the client's spec (>85 reject, 70-85 review, <70 approve)
    | but are overridable from the admin panel at runtime.
    */
    'reject_threshold' => (float) env('REKOGNITION_REJECT_THRESHOLD', 85),
    'review_threshold' => (float) env('REKOGNITION_REVIEW_THRESHOLD', 70),

    /*
    | For videos we sample frames rather than moderate the whole file
    | synchronously (Rekognition image API works on stills). This is the
    | number of evenly-spaced frames extracted with FFmpeg and checked.
    | The highest score across sampled frames wins.
    */
    'video_frame_samples' => (int) env('REKOGNITION_VIDEO_FRAMES', 5),

    /*
    | Resilience knobs for the queued job. Backoff is exponential per the
    | approved proposal: 30s, 2min, 5min.
    */
    'job' => [
        'tries'   => (int) env('REKOGNITION_JOB_TRIES', 3),
        'timeout' => (int) env('REKOGNITION_JOB_TIMEOUT', 300), // 5 min global timeout
        'backoff' => [30, 120, 300],
    ],
];
