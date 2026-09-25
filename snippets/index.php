<?php

// Config
$config   = pwConfig::load('pwsteplist');
$settings = $config['content'];

// Custom Background
pwSnippet::customCss($block);

// Section + Grid open
echo pwSnippet::sectionOpen('steplist', $block, $settings);
echo pwSnippet::gridOpen($block);

// Tagline
if (!empty($settings['tagline'])):
	snippet('tagline', ['content' => $block]);
endif;

// Heading
if (!empty($settings['heading'])):
	snippet('heading', ['content' => $block]);
endif;

// Editor
if (!empty($settings['editor'])):
	snippet('editor', ['content' => $block]);
endif;

// Blocks (items)
$items = $block->blocks()->toBlocks();
if ($items->count() > 0):

	$itemStyle       = $block->itemstyle()->or('default')->value();
	$itemNumberAlign = $block->itemnumberalign()->or('center')->value();
	echo '<div data-block="items"';
	echo ' data-columns-sm="'.$block->columnssm()->value().'"';
	echo ' data-columns-md="'.$block->columnsmd()->value().'"';
	echo ' data-columns-lg="'.$block->columnslg()->value().'"';
	echo ' data-columns-xl="'.$block->columnsxl()->value().'"';
	echo ' data-item-style="'.$itemStyle.'"';
	echo ' data-number-align="'.$itemNumberAlign.'"';
	echo ' data-shape="'.($config['defaults']['item-shape'] ?? 'round').'"';
	echo '>'."\n";

	$number = 1;
	$total = $items->count();
	foreach ($items as $item):
		$isFirst = $number === 1;
		$isLast  = $number === $total;

		echo '<div data-block="item"'.($isFirst ? ' data-first="true"' : '').($isLast ? ' data-last="true"' : '').'>'."\n";

			// Auto number bubble
			echo '<div data-field="number">'.$number.'</div>';

			// Content
			echo '<div data-field="content">'."\n";

				// Heading
				echo '<div data-field="heading">'.$item->heading()->value().'</div>';

				// Description
				echo '<div data-field="text">'.$item->description()->value().'</div>';

			echo '</div>'."\n"; // End Content
		echo '</div>'."\n"; // End Item
		$number++;
	endforeach;

	echo '</div>'."\n"; // End Items
endif;

// Close
echo pwSnippet::gridClose();
echo pwSnippet::sectionClose();
