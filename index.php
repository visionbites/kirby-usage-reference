<?php

use Kirby\Toolkit\I18n;

Kirby::plugin('visionbites/usage-reference', [
  'sections' => [
    'usageReference' => [
        'props' => [

            'headline' => function ($headline = null) {
				return I18n::translate($headline);
            },
            'template' => function ($template = null) {
                return $template;
            }
        ],

        'computed' => [
            'references' => function () {
                $uuid = $this->model()->uuid()->toString();
                $possibleReferences = kirby()->site()->index(true);

                if($this->template() !== null) {
                    $possibleReferences = $possibleReferences->filterBy('intendedTemplate', $this->template());
                }

                $usedItems =  $possibleReferences->filter(function ($child) use ($uuid) {
                    $content = $child->content();
                    $hasMatch = false;
                        foreach ($content->data() as $datum) {
							// if the content is not pretty printed, the uuid might be masked
							$maskedUuid = str_replace('/', '\/', $uuid);

                            if(str_contains($datum, $uuid) || str_contains($datum, $maskedUuid)) {
                                $hasMatch = true;
                            };
                        }
                    return $hasMatch;
                });

                $references = [];
                foreach ($usedItems as $item) {
                    $references[] = [
                        'title' => $item->title()->value,
                        'url' => $item->url(),
                        'breadcrumb' => $item->panel()->breadcrumb(),
                        'uuid' => $item->uuid()->toString(),
                        'template' => $item->intendedTemplate()->name(),
                    ];
                }

                return $references;
            }
        ]

    ]
  ]
]);
