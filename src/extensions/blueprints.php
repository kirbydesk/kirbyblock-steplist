<?php

$columnsField = fn(string $breakpoint, $default) => [
	'extends' => 'pagewizard/fields/columns',
	'default' => $default,
	'label'   => 'pw.field.columns.' . $breakpoint,
	'help'    => 'pw.field.columns.' . $breakpoint . '.help',
];

$allItemStyleOptions = [
	'default'   => ['value' => 'default',   'text' => ['*' => 'kirbyblock-steplist.item-style.default']],
	'centered'  => ['value' => 'centered',  'text' => ['*' => 'kirbyblock-steplist.item-style.centered']],
	'connected' => ['value' => 'connected', 'text' => ['*' => 'kirbyblock-steplist.item-style.connected']],
	'minimal'   => ['value' => 'minimal',   'text' => ['*' => 'kirbyblock-steplist.item-style.minimal']],
];

return [
	'blocks/pwsteplist' => pwBlueprint::main('pwsteplist', function ($cfg) use ($columnsField, $allItemStyleOptions) {
		$defaults = $cfg['defaults'];
		$itemStyleConfig = $cfg['style']['item-style'] ?? [];
		$itemStyleOptionKeys = $itemStyleConfig['options'] ?? array_keys($allItemStyleOptions);
		$itemStyleOptions = array_values(array_intersect_key(
			$allItemStyleOptions,
			array_flip($itemStyleOptionKeys)
		));
		return [
			'name' => 'kirbyblock-steplist.name',
			'icon' => 'steplist',
			'contentFields' => array_merge(
				pwBlueprint::stdContent($cfg, ['tagline', 'heading', 'editor']),
				[
					'blocks' => [
						'extends'   => 'pagewizard/fields/blocks',
						'label'     => 'kirbyblock-steplist.items',
						'fieldsets' => ['pwsteplistitem'],
					],
				]
			),
			'layoutExtras' => [
				'headlineColumns' => ['extends' => 'pagewizard/headlines/columns'],
				'columnsSm'       => $columnsField('sm', $defaults['columns-sm']),
				'columnsMd'       => $columnsField('md', $defaults['columns-md']),
				'columnsLg'       => $columnsField('lg', $defaults['columns-lg']),
				'columnsXl'       => $columnsField('xl', $defaults['columns-xl']),
			],
			'styleExtras' => [
				'itemStyle' => count($itemStyleOptions) <= 1
					? ['type' => 'hidden', 'default' => $defaults['item-style'] ?? 'default']
					: [
						'label'    => ['*' => 'kirbyblock-steplist.item-style'],
						'help'     => ['*' => 'kirbyblock-steplist.item-style.help'],
						'type'     => 'toggles',
						'default'  => $defaults['item-style'] ?? 'default',
						'width'    => '1/1',
						'options'  => $itemStyleOptions,
					],
			],
		];
	}),

	'blocks/pwsteplistitem' => \Kirby\Data\Data::read(__DIR__ . '/../blueprints/item.yml'),
];
