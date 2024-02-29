<?php

use Kirby\Toolkit\I18n;
use Visionbites\UsageReference\Services\ReferenceService;

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
					$refService = new ReferenceService();
					return $refService->findReferencesForUuid($uuid, $this->template());
				}
			]

		]
	]
]);
