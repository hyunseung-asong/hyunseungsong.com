<?php
/**
 * Canonical list of RiftMind services (used by Services index + cookie tracking).
 */
function service_catalog(): array {
    return [
        'macro-map' => [
            'title' => 'Macro Map Coach',
            'short' => 'Wave timing, rotations, and objective setup—explained live while you play.',
            'image' => 'images/services/macro-map.svg',
            'href' => 'services/macro-map.php',
        ],
        'lane-phase' => [
            'title' => 'Lane Phase Trainer',
            'short' => 'CS windows, trade patterns, and recall timings tailored to your matchup.',
            'image' => 'images/services/lane-phase.svg',
            'href' => 'services/lane-phase.php',
        ],
        'vod-review' => [
            'title' => 'VOD Review Assistant',
            'short' => 'Upload a replay and get chapterized notes: mistakes, fixes, and drills.',
            'image' => 'images/services/vod-review.svg',
            'href' => 'services/vod-review.php',
        ],
        'champ-pool' => [
            'title' => 'Champion Pool Builder',
            'short' => 'Curate a small, climb-ready pool with bans, counters, and practice plans.',
            'image' => 'images/services/champ-pool.svg',
            'href' => 'services/champ-pool.php',
        ],
        'jungle-pathing' => [
            'title' => 'Jungle Pathing Radar',
            'short' => 'Clear routes, gank windows, and counter-jungle risk alerts on the fly.',
            'image' => 'images/services/jungle-pathing.svg',
            'href' => 'services/jungle-pathing.php',
        ],
        'vision-control' => [
            'title' => 'Vision Control Lab',
            'short' => 'Ward placement goals, sweep timings, and “why we’re blind” callouts.',
            'image' => 'images/services/vision-control.svg',
            'href' => 'services/vision-control.php',
        ],
        'teamfighting' => [
            'title' => 'Teamfight Simulator',
            'short' => 'Target selection, spacing drills, and ability sequencing for messy fights.',
            'image' => 'images/services/teamfighting.svg',
            'href' => 'services/teamfighting.php',
        ],
        'draft-coach' => [
            'title' => 'Draft Coach',
            'short' => 'Pick/ban heuristics, comp win conditions, and lane assignment suggestions.',
            'image' => 'images/services/draft-coach.svg',
            'href' => 'services/draft-coach.php',
        ],
        'tilt-control' => [
            'title' => 'Mental Game Coach',
            'short' => 'Between-games resets, focus cues, and streak discipline—without the fluff.',
            'image' => 'images/services/tilt-control.svg',
            'href' => 'services/tilt-control.php',
        ],
        'rank-analytics' => [
            'title' => 'Rank Analytics',
            'short' => 'Trend dashboards for roles, champs, and mistake categories across your climb.',
            'image' => 'images/services/rank-analytics.svg',
            'href' => 'services/rank-analytics.php',
        ],
    ];
}

function service_by_slug(string $slug): ?array {
    $all = service_catalog();
    return $all[$slug] ?? null;
}
