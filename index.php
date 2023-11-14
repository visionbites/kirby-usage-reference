<?php

use Kirby\Toolkit\I18n;
use Kirby\Toolkit\A;

Kirby::plugin('visionbites/usage-reference', [
	'options' => [
		'expire' => 15,
		'cache' => true,
	],
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
					$cache = kirby()->cache('visionbites.usage-reference');
					$expire = option('visionbites.usage-reference.expire') <= 1 ? 1 : option('visionbites.usage-reference.expire');
					$data = $cache->get($uuid);

					if (!$data) {

						if ($this->template() !== null) {
							$cacheTag = 'index.' . $this->template();

							$possibleReferences = $cache->get($cacheTag);

							if(!$possibleReferences) {
								$possibleReferences = kirby()->site()->index(true);
								$possibleReferences = $possibleReferences->filterBy('intendedTemplate', $this->template());
								$possibleReferences = $possibleReferences->toArray();

								$cache->set($cacheTag, $possibleReferences, $expire);
							}
						} else {
							$possibleReferences = $cache->get('index');

							if(!$possibleReferences) {
								$possibleReferences = kirby()->site()->index(true);

								$possibleReferences = $possibleReferences->toArray();

								$cache->set('index', $possibleReferences, $expire);
							}
						}

						$usedItems = A::filter(
							$possibleReferences,
							function ($child, $key) use ($uuid) {

								$content = $child['content'];
								$hasMatch = false;

								foreach ($content as $datum) {
									// if the content is not pretty printed, the uuid might be masked
									$maskedUuid = str_replace('/', '\/', $uuid);

									if (str_contains($datum, $uuid) || str_contains($datum, $maskedUuid)) {
										$hasMatch = true;
									};
								}
								return $hasMatch;
							}
						);

						$references = [];
						foreach ($usedItems as $item) {
							$refpage = site()->findPageOrDraft($item['uri']);

							$references[] = [
								'title' => $refpage->title()->value,
								'url' => $refpage->url(),
								'breadcrumb' => $refpage->panel()->breadcrumb(),
								'uuid' => $refpage->uuid()->toString(),
								'template' => $refpage->intendedTemplate()->name(),
							];
						}
						$data = $references;

						$cache->set($uuid, $data, $expire);
					}

					return $data;
				}
			]
		]
	]
]);
