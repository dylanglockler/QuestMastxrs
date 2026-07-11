<?php

namespace Database\Seeders;

use App\Models\Hunt;
use Illuminate\Database\Seeder;

class HuntSeeder extends Seeder
{
    public function run(): void
    {
        $hunt = Hunt::firstOrCreate(
            ['slug' => 'riverbend-ramble'],
            [
                'title' => 'The Riverbend Ramble',
                'tagline' => 'Three clues, one river, zero excuses to stay inside.',
                'description' => "We hid three laminated cards along the Riverbend greenway. Solve each riddle, walk to the spot, find the next clue taped somewhere sneaky (but not illegal — don't make us come get it back).",
                'city' => 'Riverbend',
                'neighborhood' => 'Old Mill District',
                'status' => 'active',
                'starting_hint' => 'Start where the joggers start: the big stone arch at the north end of the greenway.',
                'published_at' => now(),
            ]
        );

        if ($hunt->clues()->exists()) {
            return;
        }

        $clues = [
            [
                'order' => 1,
                'title' => 'The Arch',
                'riddle_text' => "I've watched a thousand shoes go by, but I've never taken a single step. Look low, where the stone meets the dirt, on the side the sun forgets.",
                'location_note' => 'Near the entrance arch, shaded side, worth crouching for.',
                'hints' => [
                    'It is attached to something, not just sitting loose.',
                    'The clue is taped to the underside of something, not on top.',
                    'There is a small bench right next to the arch — check underneath it.',
                ],
            ],
            [
                'order' => 2,
                'title' => 'The Old Mill',
                'riddle_text' => 'Once I ground the town its bread, now I just stand here looking dead. Three windows in, count from the left — check what the ivy has kept.',
                'location_note' => 'The brick mill building halfway down the greenway.',
                'hints' => [
                    'The ivy is doing a very bad job of hiding it, honestly.',
                    "You're looking for the third window from the left side of the building.",
                    'It is tucked behind the ivy at about knee height, not up high.',
                ],
            ],
            [
                'order' => 3,
                'title' => 'The Last Bend',
                'riddle_text' => 'Where the river bends and the path gives up, find the bench that faces the wrong way — someone wanted a better view of something else entirely.',
                'location_note' => 'The last bench before the greenway trail ends at the parking lot.',
                'hints' => [
                    'Most benches face the river. This one does not.',
                    'It faces a mural on the flood wall instead.',
                    'The clue is clipped under the armrest closest to the mural.',
                ],
            ],
        ];

        foreach ($clues as $clueData) {
            $hints = $clueData['hints'];
            unset($clueData['hints']);

            $clue = $hunt->clues()->create($clueData);

            foreach ($hints as $i => $text) {
                $clue->hints()->create([
                    'order' => $i + 1,
                    'text' => $text,
                ]);
            }
        }
    }
}
