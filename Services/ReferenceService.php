<?php

namespace Visionbites\UsageReference\Services;

use Kirby\Toolkit\A;

class ReferenceService
{
	public function findReferencesForUuid($uuid, string $template = null)
	{
		$cache = kirby()->cache('visionbites.usage-reference');
		$expire = option('visionbites.usage-reference.expire') <= 1 ? 1 : option('visionbites.usage-reference.expire');
		$data = $cache->get($uuid);

		if (!$data) {

			if ($template !== null) {
				$cacheTag = 'index.' . $template;

				$possibleReferences = $cache->get($cacheTag);

				if (!$possibleReferences) {
					$possibleReferences = kirby()->site()->index(true);
					$possibleReferences = $possibleReferences->filterBy('intendedTemplate', $template);
					$possibleReferences = $possibleReferences->toArray();

					$cache->set($cacheTag, $possibleReferences, $expire);
				}
			} else {
				$possibleReferences = $cache->get('index');

				if (!$possibleReferences) {
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

						if (is_null($datum) || trim($datum) === "") continue;

						if (str_contains($datum, $uuid) || str_contains($datum, $maskedUuid)) {
							$hasMatch = true;
						};
					}
					return $hasMatch;
				}
			);

			$references = [];
			foreach ($usedItems as $item) {
				$refPage = site()->findPageOrDraft($item['uri']) ?? site()->draft($item['uri']);

				$references[] = [
					'title' => $refPage->title()->value ?? '',
					'url' => $refPage->url(),
					'slug' => $refPage->slug(),
					'breadcrumb' => $refPage->panel()->breadcrumb(),
					'uuid' => $refPage->uuid()->toString(),
					'template' => $refPage->intendedTemplate()->name(),
                    'status' => $refPage->isDraft() ? 'draft' : ($refPage->isUnlisted() ? 'unlisted': 'listed'),
				];
			}
			$data = $references;

			$cache->set($uuid, $data, $expire);
		}

		return $data;
	}

}
