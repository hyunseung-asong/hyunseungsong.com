<?php
/**
 * Long-form copy for individual service pages.
 */
function service_body(string $slug): ?array {
    $all = [
        'macro-map' => [
            'lede' => 'Turn foggy mid-game decisions into a simple checklist: waves, vision, tempo, objectives.',
            'paras' => [
                'Macro Map Coach watches the minimap context you usually ignore—lane states, death timers, and jungle proximity—and turns it into short, spoken-style prompts you can execute immediately.',
                'You’ll get fewer “random” fights and more structured setups: crash, reset, swap, or contest with a reason.',
            ],
            'bullets' => [
                'Side-lane assignment suggestions when Baron is on the table',
                'Objective timers linked to wave crash windows',
                '“Safe farm” windows vs “must move now” pings',
            ],
        ],
        'lane-phase' => [
            'lede' => 'Win lane with repeatable patterns: trades, recalls, and wave freezes that match your champion.',
            'paras' => [
                'Lane Phase Trainer compares your matchup to thousands of similar games and highlights the 2–3 habits that most often decide the first 10 minutes.',
                'You’ll practice one micro-goal per lane: a trade pattern, a CS benchmark, or a recall timing—then review how cleanly you hit it.',
            ],
            'bullets' => [
                'Level-based power spikes and all-in windows',
                'Crash vs slow-push plans for cannon waves',
                'Roaming checkpoints without throwing XP',
            ],
        ],
        'vod-review' => [
            'lede' => 'Stop rewatching blindly—get chapters, causes, and one drill per mistake.',
            'paras' => [
                'Upload a replay link or file and VOD Review Assistant segments the game into laning, skirmishes, objectives, and late-game fights.',
                'Each segment includes a plain-language explanation, the likely root cause, and a 5-minute practice drill you can queue with.',
            ],
            'bullets' => [
                'Death recap: what you could have done 3 seconds earlier',
                'Vision snapshots before key fights',
                'Exportable notes you can share with a duo or coach',
            ],
        ],
        'champ-pool' => [
            'lede' => 'A tight champion pool climbs faster—this service enforces discipline with data.',
            'paras' => [
                'Champion Pool Builder recommends a primary, a pocket pick, and a counter-ready option based on your comfort, ban rates, and team comps you actually play.',
                'You’ll see what to ban, what to dodge, and what to practice first when a patch shifts your mains.',
            ],
            'bullets' => [
                'Patch delta alerts for your pool',
                'Role-specific “safe blind” suggestions',
                'Weekly practice queue tailored to weaknesses',
            ],
        ],
        'jungle-pathing' => [
            'lede' => 'Clear fast, gank on time, and avoid the paths that get you invaded.',
            'paras' => [
                'Jungle Pathing Radar models enemy start guesses, lane priority, and scuttle windows so your first rotation isn’t a coin flip.',
                'You’ll get contingency routes when a lane loses prio or a counter-jungle threat appears on the map.',
            ],
            'bullets' => [
                'Three-path templates per patch jungle changes',
                'Objective setup routes (herald / drake)',
                'Counter-gank likelihood cues from lane states',
            ],
        ],
        'vision-control' => [
            'lede' => 'Vision wins messy games—this lab makes ward goals measurable.',
            'paras' => [
                'Vision Control Lab tracks your ward timing, sweep usage, and blind spots before deaths and objectives.',
                'You’ll get a small set of “must-have” wards for your role each phase of the game—no generic minimap memes.',
            ],
            'bullets' => [
                'Objective setup checklist (30s before spawn)',
                'Defensive tri-bush timing when behind',
                'Sweep routes that don’t grief your tempo',
            ],
        ],
        'teamfighting' => [
            'lede' => 'Teamfights feel chaotic until you know your job: spacing, target priority, and ability order.',
            'paras' => [
                'Teamfight Simulator breaks fights into entry, mid-fight, and cleanup—then tells you what your champion should be doing in each slice.',
                'You’ll rehearse ability sequencing on common engage patterns so you don’t panic-flash the wrong direction.',
            ],
            'bullets' => [
                'Front-to-back vs flank win conditions',
                '“Kill zone” overlays for skillshot champs',
                'Peel vs dive decision prompts for supports',
            ],
        ],
        'draft-coach' => [
            'lede' => 'Draft is half the game—get comp goals, flex picks, and ban priorities you can explain to your team.',
            'paras' => [
                'Draft Coach maps bans and picks to win conditions: pick, split, siege, or teamfight—and warns you when your comp becomes too one-dimensional.',
                'You’ll see suggested lane swaps and item pivots when the draft goes sideways.',
            ],
            'bullets' => [
                'Ban suggestions based on popular meta overlaps',
                'Synergy tags for duos and flex roles',
                'Late-draft “fix-it” picks when you’re short on damage or CC',
            ],
        ],
        'tilt-control' => [
            'lede' => 'Climbing is a streak sport—reset your mental between games like an athlete.',
            'paras' => [
                'Mental Game Coach uses short prompts between queues: breathing cadence, refocus cues, and a one-line plan for the next champ select.',
                'It’s not therapy—it’s performance hygiene for ranked sessions.',
            ],
            'bullets' => [
                'Post-loss debrief template (30 seconds)',
                'Queue discipline: when to stop for the day',
                'Focus mode for rematch / dodge decisions',
            ],
        ],
        'rank-analytics' => [
            'lede' => 'See your climb as a dashboard: roles, champs, and mistake categories over time.',
            'paras' => [
                'Rank Analytics aggregates your recent matches into trends—early deaths, vision deficits, CS at 10, and objective participation—so you know what actually moved your LP.',
                'Compare patches, roles, and “comfort picks” without spreadsheet work.',
            ],
            'bullets' => [
                'Rolling 20-game summaries',
                'Champion-specific mistake breakdowns',
                'Goal tracking (e.g., deaths before 15:00)',
            ],
        ],
    ];
    return $all[$slug] ?? null;
}
